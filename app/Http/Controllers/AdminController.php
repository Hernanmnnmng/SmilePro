<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Controller for admin/management functions
 * Handles user management, role updates, and system administration
 */
class AdminController extends Controller
{
    /**
     * Show all users in the system
     */
    public function index()
    {
        try {
            $users = User::orderBy('name')->get();
            return view('admin.index', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error loading admin index', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Er is een fout opgetreden bij het laden van de gebruikerslijst.');
        }
    }

    /**
     * Show the form for creating a new user (all roles)
     */
    public function createUser()
    {
        try {
            return view('admin.create-user');
        } catch (\Exception $e) {
            Log::error('Error loading create user form', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.index')->with('error', 'Er is een fout opgetreden bij het laden van het formulier.');
        }
    }

    /**
     * Store a newly created user (all roles)
     */
    public function storeUser(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:patient,tandarts,mondhygienist,assistent,management',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            Log::info('User created successfully', [
                'new_user_id' => $user->id,
                'new_user_name' => $user->name,
                'new_user_role' => $user->role,
                'created_by' => auth()->id()
            ]);

            return redirect()->route('admin.index')->with('success', 'Gebruiker succesvol aangemaakt!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating user', [
                'request_data' => $request->except('password', 'password_confirmation'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Er is een fout opgetreden bij het aanmaken van de gebruiker.')->withInput();
        }
    }

    /**
     * Show all employees (tandarts, mondhygienist, assistent)
     * Excludes patients and management from the list
     */
    public function medewerkers()
    {
        try {
            $medewerkers = User::whereIn('role', ['tandarts', 'mondhygienist', 'assistent'])->orderBy('name')->get();
            return view('admin.medewerkers', compact('medewerkers'));
        } catch (\Exception $e) {
            Log::error('Error loading medewerkers', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('dashboard')->with('error', 'Er is een fout opgetreden bij het laden van de medewerkers.');
        }
    }

    /**
     * Show the form for creating a new employee
     */
    public function create()
    {
        try {
            return view('admin.create');
        } catch (\Exception $e) {
            Log::error('Error loading create employee form', [
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.medewerkers')->with('error', 'Er is een fout opgetreden bij het laden van het formulier.');
        }
    }

    /**
     * Store a newly created employee
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role' => 'required|in:tandarts,mondhygienist,assistent',
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            Log::info('Employee created successfully', [
                'new_user_id' => $user->id,
                'new_user_name' => $user->name,
                'new_user_role' => $user->role,
                'created_by' => auth()->id()
            ]);

            return redirect()->route('admin.medewerkers')->with('success', 'Medewerker succesvol aangemaakt!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating employee', [
                'request_data' => $request->except('password', 'password_confirmation'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Er is een fout opgetreden bij het aanmaken van de medewerker.')->withInput();
        }
    }

    /**
     * Update a user's role
     */
    public function updateRole(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            $request->validate([
                'role' => 'required|in:patient,tandarts,mondhygienist,assistent,management',
            ]);
            
            $user->role = $request->role;
            $user->save();
            
            Log::info('User role updated', [
                'user_id' => $id,
                'new_role' => $request->role,
                'updated_by' => auth()->id()
            ]);
            
            return redirect()->route('admin.index')->with('success', 'Rol bijgewerkt!');
        } catch (ModelNotFoundException $e) {
            Log::warning('User not found for role update', ['user_id' => $id]);
            return redirect()->route('admin.index')->with('error', 'Gebruiker niet gevonden.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating user role', [
                'user_id' => $id,
                'request_data' => $request->all(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.index')->with('error', 'Er is een fout opgetreden bij het bijwerken van de rol.');
        }
    }

    /**
     * Show availability slots for a specific user
     */
    public function showAvailabilities($id)
    {
        try {
            $user = User::findOrFail($id);
            $availabilities = Availability::where('user_id', $id)->get();
            return view('admin.availabilities', compact('user', 'availabilities'));
        } catch (ModelNotFoundException $e) {
            Log::warning('User not found for availabilities view', ['user_id' => $id]);
            return redirect()->route('admin.index')->with('error', 'Gebruiker niet gevonden.');
        } catch (\Exception $e) {
            Log::error('Error loading user availabilities', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.index')->with('error', 'Er is een fout opgetreden bij het laden van de beschikbaarheid.');
        }
    }

    /**
     * Show the user edit form
     */
    public function edit($id)
    {
        try {
            $user = User::findOrFail($id);
            return view('admin.edit', compact('user'));
        } catch (ModelNotFoundException $e) {
            Log::warning('User not found for edit', ['user_id' => $id]);
            return redirect()->route('admin.index')->with('error', 'Gebruiker niet gevonden.');
        } catch (\Exception $e) {
            Log::error('Error loading user edit form', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.index')->with('error', 'Er is een fout opgetreden bij het laden van het bewerkformulier.');
        }
    }

    /**
     * Update user information (name, email, password, role)
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validationRules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            ];

            // Only validate password if it's provided
            if ($request->filled('password')) {
                $validationRules['password'] = 'required|string|min:8|confirmed';
            }

            // Validate role if provided (for employees)
            if ($request->filled('role')) {
                $validationRules['role'] = 'required|in:tandarts,mondhygienist,assistent';
            }

            $request->validate($validationRules);

            $user->name = $request->name;
            $user->email = $request->email;
            
            // Update role if provided (for employees)
            if ($request->filled('role')) {
                $user->role = $request->role;
            }
            
            // Only update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();
            
            Log::info('User updated successfully', [
                'user_id' => $id,
                'updated_by' => auth()->id()
            ]);

            // Redirect to medewerkers page if this is an employee, otherwise to admin index
            $isEmployee = in_array($user->role, ['tandarts', 'mondhygienist', 'assistent']);
            $redirectRoute = $isEmployee ? 'admin.medewerkers' : 'admin.index';
            
            return redirect()->route($redirectRoute)->with('success', 'Gebruiker bijgewerkt!');
        } catch (ModelNotFoundException $e) {
            Log::warning('User not found for update', ['user_id' => $id]);
            return redirect()->route('admin.medewerkers')->with('error', 'Gebruiker niet gevonden.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating user', [
                'user_id' => $id,
                'request_data' => $request->except('password'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Er is een fout opgetreden bij het bijwerken van de gebruiker.')->withInput();
        }
    }

    /**
     * Delete a user from the system
     * Prevents users from deleting themselves
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->id === auth()->id()) {
                $isEmployee = in_array($user->role, ['tandarts', 'mondhygienist', 'assistent']);
                $redirectRoute = $isEmployee ? 'admin.medewerkers' : 'admin.index';
                return redirect()->route($redirectRoute)->with('error', 'Je kunt jezelf niet verwijderen!');
            }

            $userName = $user->name;
            $userRole = $user->role;
            $user->delete();
            
            Log::info('User deleted successfully', [
                'deleted_user_id' => $id,
                'deleted_user_name' => $userName,
                'deleted_user_role' => $userRole,
                'deleted_by' => auth()->id()
            ]);

            // Redirect to medewerkers page if this was an employee, otherwise to admin index
            $isEmployee = in_array($userRole, ['tandarts', 'mondhygienist', 'assistent']);
            $redirectRoute = $isEmployee ? 'admin.medewerkers' : 'admin.index';
            
            return redirect()->route($redirectRoute)->with('success', 'Gebruiker verwijderd!');
        } catch (ModelNotFoundException $e) {
            Log::warning('User not found for deletion', ['user_id' => $id]);
            return redirect()->route('admin.medewerkers')->with('error', 'Gebruiker niet gevonden.');
        } catch (\Exception $e) {
            Log::error('Error deleting user', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.medewerkers')->with('error', 'Er is een fout opgetreden bij het verwijderen van de gebruiker.');
        }
    }
}

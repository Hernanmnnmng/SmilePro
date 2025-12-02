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
            $users = User::all();
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
     * Show all employees (tandarts, mondhygienist, assistent)
     * Excludes patients and management from the list
     */
    public function medewerkers()
    {
        try {
            $medewerkers = User::whereIn('role', ['tandarts', 'mondhygienist', 'assistent'])->get();
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
     * Update user information (name, email, password)
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

            $request->validate($validationRules);

            $user->name = $request->name;
            $user->email = $request->email;
            
            // Only update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            $user->save();
            
            Log::info('User updated successfully', [
                'user_id' => $id,
                'updated_by' => auth()->id()
            ]);

            return redirect()->route('admin.index')->with('success', 'Gebruiker bijgewerkt!');
        } catch (ModelNotFoundException $e) {
            Log::warning('User not found for update', ['user_id' => $id]);
            return redirect()->route('admin.index')->with('error', 'Gebruiker niet gevonden.');
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
                return redirect()->route('admin.index')->with('error', 'Je kunt jezelf niet verwijderen!');
            }

            $userName = $user->name;
            $user->delete();
            
            Log::info('User deleted successfully', [
                'deleted_user_id' => $id,
                'deleted_user_name' => $userName,
                'deleted_by' => auth()->id()
            ]);

            return redirect()->route('admin.index')->with('success', 'Gebruiker verwijderd!');
        } catch (ModelNotFoundException $e) {
            Log::warning('User not found for deletion', ['user_id' => $id]);
            return redirect()->route('admin.index')->with('error', 'Gebruiker niet gevonden.');
        } catch (\Exception $e) {
            Log::error('Error deleting user', [
                'user_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('admin.index')->with('error', 'Er is een fout opgetreden bij het verwijderen van de gebruiker.');
        }
    }
}

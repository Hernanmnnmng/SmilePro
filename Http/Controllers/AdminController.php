<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.index', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'role' => 'required|in:patient,tandarts,mondhygienist,assistent,management',
        ]);
        $user->role = $request->role;
        $user->save();
        return redirect()->route('admin.index')->with('success', 'Rol bijgewerkt!');
    }

    public function showAvailabilities($id)
    {
        $user = User::findOrFail($id);
        $availabilities = Availability::where('user_id', $id)->get();
        return view('admin.availabilities', compact('user', 'availabilities'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
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

        return redirect()->route('admin.index')->with('success', 'Gebruiker bijgewerkt!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.index')->with('error', 'Je kunt jezelf niet verwijderen!');
        }

        $user->delete();
        return redirect()->route('admin.index')->with('success', 'Gebruiker verwijderd!');
    }
}

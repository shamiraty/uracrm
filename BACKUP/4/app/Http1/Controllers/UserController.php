<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all(); // Fetch all users
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all(); // Get all roles
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'branch_id' => 'nullable|exists:branches,id',
            'designation' => 'nullable|string|max:255',
            'rank' => 'nullable|string|max:255',
            'status' => 'required|string',
            'phone_number' => 'nullable|string|max:255'
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']); // Hash password before saving

    $user = User::create($validatedData); // Create the user with validated data
    $user->assignRole($request->role); // Assign the selected role to the new user

    return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user')); // Return the edit form for the user
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, User $user)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
    //         'password' => 'sometimes|string|min:6',
    //         'branch_id' => 'nullable|exists:branches,id',
    //         'designation' => 'nullable|string|max:255',
    //         'rank' => 'nullable|string|max:255',
    //         'status' => 'required|string',
    //         'phone_number' => 'nullable|string|max:255',
    //         'role' => 'required|string|exists:roles,name'
    //     ]);

    //     if (!empty($validatedData['password'])) { // Check if password was entered and needs to be updated
    //         $validatedData['password'] = Hash::make($validatedData['password']);
    //     } else {
    //         unset($validatedData['password']); // Remove password from array if not being updated
    //     }

    //     $user->update($validatedData); // Update user data

    //     return redirect()->route('users.index')->with('success', 'User updated successfully.');
    // }


    public function update(Request $request, User $user)
{
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'password' => 'sometimes|string|min:6',
        'branch_id' => 'nullable|exists:branches,id',
        'designation' => 'nullable|string|max:255',
        'rank' => 'nullable|string|max:255',
        'status' => 'required|string',
        'phone_number' => 'nullable|string|max:255',
        'role' => 'required|string|exists:roles,name'
    ]);

    if (!empty($validatedData['password'])) {
        $validatedData['password'] = Hash::make($validatedData['password']);
    } else {
        unset($validatedData['password']);
    }

    try {
        $user->update($validatedData);
        if ($request->has('role')) {
            $user->syncRoles($request->role);
        }
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    } catch (\Exception $e) {
        Log::error('User update failed', ['error' => $e->getMessage()]);
        return back()->with('error', 'Failed to update user.');
    }
}

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete(); // Delete the user

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}

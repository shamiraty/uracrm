<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Display all roles
    public function index()
    {
        $permissions = Permission::all();
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles', 'permissions'));
    }

    // Show form to create a new role
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    // Store a new role
    public function store(Request $request)
    {
        // Check if the role name already exists
        $existingRole = Role::where('name', $request->name)->first();
        
        if ($existingRole) {
            return redirect()->route('roles.index')->with('error', 'Role name already exists!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
    
        try {
            $role = Role::create(['name' => $request->name]);
    
            // Sync only valid permissions
            $validPermissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($validPermissions);
    
            return redirect()->route('roles.index')->with('success', 'Role created successfully!');
        } catch (\Spatie\Permission\Exceptions\RoleAlreadyExists $e) {
            return redirect()->back()->withErrors(['name' => 'The role "' . $request->name . '" already exists.']);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }

    // Show form to edit a role
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    // Update a specific role
    public function update(Request $request, Role $role)
    {
        // Check if the role name already exists, excluding the current role
        $existingRole = Role::where('name', $request->name)
                            ->where('id', '!=', $role->id)
                            ->first();
        
        if ($existingRole) {
            return redirect()->route('roles.index')->with('error', 'Role name already exists!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
    
        try {
            $role->update(['name' => $request->name]);
    
            // Sync only valid permissions
            $permissions = Permission::whereIn('id', $request->permissions)->get();
            $role->syncPermissions($permissions);
    
            return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An unexpected error occurred: ' . $e->getMessage()]);
        }
    }

    // Delete the specified role
    public function destroy(Role $role)
    {
        if ($role->delete()) {
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
        }
        return back()->withErrors('Error deleting role.');
    }
}

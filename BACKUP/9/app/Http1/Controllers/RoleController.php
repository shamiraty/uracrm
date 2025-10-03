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
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
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
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
    }

    // Show form to edit a role
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id' // ensuring each item in the array exists in the 'permissions' table
        ]);

        $role->name = $request->name;
        $role->save();

        // Assuming permissions are passed as IDs
        $permissions = Permission::findMany($request->permissions);
        $role->syncPermissions($permissions);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
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


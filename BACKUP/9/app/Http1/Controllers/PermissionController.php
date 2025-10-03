<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    // Display all permissions
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    // Show form to create a new permission
    public function create()
    {
        return view('permissions.create');
    }

    // Store a new permission
    public function store(Request $request)
    {
        Permission::create(['name' => $request->name]);
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully!');
    }

    // Show form to edit a permission
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    // Update the specified permission
    public function update(Request $request, Permission $permission)
    {
        $permission->name = $request->name;
        $permission->save();
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully!');
    }

    // Delete the specified permission
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully!');
    }
}

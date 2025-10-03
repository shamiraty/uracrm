<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all();
        return view('departments.index', compact('departments'));
    }

    public function create()
    {
        return view('departments.create');
    }

    public function store(Request $request)
    {
        // Validate that all fields are required and 'name' is unique
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name', // Ensure 'name' is unique
            'description' => 'required|string', // Made description required as well
        ]);

        // Check if the department name already exists
        $existingDepartment = Department::where('name', $request->name)->first();
        if ($existingDepartment) {
            return redirect()->route('departments.index')->with('error', 'Department name already exists!');
        }

        // Create the new department
        Department::create($request->all());
        return redirect()->route('departments.index')->with('success', 'Department created successfully.');
    }

    public function show(Department $department)
    {
        return view('departments.show', compact('department'));
    }

    public function edit(Department $department)
    {
        return view('departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        // Validate that all fields are required and 'name' is unique, excluding the current department
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id, // Exclude the current department's 'name'
            'description' => 'required|string', // Made description required as well
        ]);

        // Check if the department name already exists (excluding the current department)
        $existingDepartment = Department::where('name', $request->name)
                                        ->where('id', '!=', $department->id) // Exclude the current department
                                        ->first();
        if ($existingDepartment) {
            return redirect()->route('departments.index')->with('error', 'Department name already exists!');
        }

        // Update the existing department
        $department->update($request->all());
        return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
    }
}

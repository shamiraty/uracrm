<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\District;
use App\Models\Region;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        $districts = District::all(); // Get all districts
        $regions = Region::all(); // Get all regions
        return view('branches.index', compact('branches', 'districts', 'regions'));
    }

    public function create()
    {
        $districts = District::all();
        $regions = Region::all();
        return view('branches.create', compact('districts', 'regions'));
    }

    public function store(Request $request)
    {
        // Validate that all fields are required
        $request->validate([
            'name' => 'required|string|max:255|unique:branches,name', // Ensure 'name' is unique
            'district_id' => 'required|exists:districts,id',
            'region_id' => 'required|exists:regions,id',
        ]);

        // Check if the branch name already exists
        $existingBranch = Branch::where('name', $request->name)->first();
        if ($existingBranch) {
            return redirect()->route('branches.index')->with('error', 'Branch name already exists!');
        }

        // Create the new branch
        Branch::create($request->all());
        return redirect()->route('branches.index')->with('success', 'Branch created successfully.');
    }

    public function show(Branch $branch)
    {
        return view('branches.show', compact('branch'));
    }

    public function edit(Branch $branch)
    {
        $districts = District::all();
        $regions = Region::all();
        return view('branches.edit', compact('branch', 'districts', 'regions'));
    }

    public function update(Request $request, Branch $branch)
    {
        // Validate that all fields are required
        $request->validate([
            'name' => 'required|string|max:255|unique:branches,name,' . $branch->id, // Ensure 'name' is unique, excluding the current branch
            'district_id' => 'required|exists:districts,id',
            'region_id' => 'required|exists:regions,id',
        ]);

        // Check if the branch name already exists (excluding the current branch)
        $existingBranch = Branch::where('name', $request->name)->where('id', '!=', $branch->id)->first();
        if ($existingBranch) {
            return redirect()->route('branches.index')->with('error', 'Branch name already exists!');
        }

        // Update the existing branch
        $branch->update($request->all());
        return redirect()->route('branches.index')->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('branches.index')->with('success', 'Branch deleted successfully.');
    }
}

<?php

// app/Http/Controllers/RepresentativeController.php

namespace App\Http\Controllers;

use App\Models\Representative;
use Illuminate\Http\Request;

class RepresentativeController extends Controller
{
    public function index()
    {
        $representatives = Representative::with(['user', 'department', 'branch', 'district', 'region'])->get();
        return view('representatives.index', compact('representatives'));
    }

    public function create()
    {
        $users = \App\Models\User::all();
        $departments = \App\Models\Department::all();
        $branches = \App\Models\Branch::all();
        $districts = \App\Models\District::all();
        $regions = \App\Models\Region::all();
        return view('representatives.create', compact('users', 'departments', 'branches', 'districts', 'regions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'department_id' => 'required',
            'branch_id' => 'required',
            'district_id' => 'required',
            'region_id' => 'required',
        ]);

        Representative::create($request->all());
        return redirect()->route('representatives.index')->with('success', 'Representative created successfully.');
    }

    public function show(Representative $representative)
    {
        return view('representatives.show', compact('representative'));
    }

    public function edit(Representative $representative)
    {
        return view('representatives.edit', compact('representative'));
    }

    public function update(Request $request, Representative $representative)
    {
        $request->validate([
            'user_id' => 'required',
            'department_id' => 'required',
            'branch_id' => 'required',
            'district_id' => 'required',
            'region_id' => 'required',
        ]);

        $representative->update($request->all());
        return redirect()->route('representatives.index')->with('success', 'Representative updated successfully.');
    }

    public function destroy(Representative $representative)
    {
        $representative->delete();
        return redirect()->route('representatives.index')->with('success', 'Representative deleted successfully.');
    }
}

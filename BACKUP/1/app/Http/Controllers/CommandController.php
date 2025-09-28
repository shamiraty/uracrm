<?php

namespace App\Http\Controllers;

use App\Models\Command;
use App\Models\Region;
use App\Models\Branch;
use App\Models\District;
use Illuminate\Http\Request;

class CommandController extends Controller
{
    public function index()
    {
        $commands = Command::with(['region', 'branch', 'district'])->get();
        $regions = Region::all();
        $branches = Branch::all();
        $districts = District::all();

        return view('commands.index', compact('commands', 'regions', 'branches', 'districts'));
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255|unique:commands,name',
            'region_id' => 'required|exists:regions,id',
            'branch_id' => 'required|exists:branches,id',
            'district_id' => 'required|exists:districts,id',
        ]);

        // Create the command using only validated data
        Command::create($request->only(['name', 'region_id', 'branch_id', 'district_id']));
        return redirect()->route('commands.index')->with('success', 'Command created successfully.');
    }

    public function update(Request $request, Command $command)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'region_id' => 'required|exists:regions,id',
            'branch_id' => 'required|exists:branches,id',
            'district_id' => 'required|exists:districts,id',
        ]);

        // Update the command using only validated data
        $command->update($request->only(['name', 'region_id', 'branch_id', 'district_id']));
        return redirect()->route('commands.index')->with('success', 'Command updated successfully.');
    }

    public function destroy(Command $command)
    {
        // Delete the command
        $command->delete();
        return redirect()->route('commands.index')->with('success', 'Command deleted successfully.');
    }
}

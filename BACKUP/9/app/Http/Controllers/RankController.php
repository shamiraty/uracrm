<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rank;

class RankController extends Controller
{
    // Display the form with the list of ranks
    public function create()
    {
        $ranks = Rank::all();
        return view('ranks.create', compact('ranks'));
    }

    // Store a new rank
    public function store(Request $request)
    {
        // Check if the rank name already exists in the database
        $existingRank = Rank::where('name', $request->name)->first();
        
        if ($existingRank) {
            return redirect()->route('ranks.create')->with('error', 'Rank name already exists!');
        }

        // If not exists, create the new rank
        Rank::create([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('ranks.create')->with('success', 'Rank added successfully!');
    }

    // Show the form for editing a specific rank
    public function edit($id)
    {
        $rank = Rank::findOrFail($id);
        return response()->json($rank);
    }

    // Update a specific rank
    public function update(Request $request, $id)
    {
        // Find the rank by ID
        $rank = Rank::findOrFail($id);

        // Check if the rank name already exists (excluding the current rank)
        $existingRank = Rank::where('name', $request->name)
                            ->where('id', '!=', $id)
                            ->first();

        if ($existingRank) {
            return redirect()->route('ranks.create')->with('error', 'Rank name already exists!');
        }

        // If not exists, update the rank name
        $rank->update([
            'name' => $request->input('name'),
        ]);

        return redirect()->route('ranks.create')->with('success', 'Rank updated successfully!');
    }

    // Delete a specific rank
    public function destroy($id)
    {
        $rank = Rank::findOrFail($id);
        $rank->delete();

        return response()->json(['success' => true, 'message' => 'Rank deleted successfully!']);
    }
}

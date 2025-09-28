<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
// use Maatwebsite\Excel\Facades\Excel; // Uncomment if you're using Laravel-Excel

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Member::query();

        // Implement search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('ClientId', 'like', '%' . $searchTerm . '%')
                  ->orWhere('Name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('checkNo', 'like', '%' . $searchTerm . '%');
        }

        // Limit to 100 records
        $members = $query->limit(100)->get();

        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ClientId' => 'required|unique:members,ClientId',
            'Name' => 'required',
            // Add validation rules for other fields if needed
        ]);

        Member::create($request->all());
        return redirect()->route('uramembers.index')->with('success', 'Member added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'ClientId' => 'required|unique:members,ClientId,' . $member->id,
            'Name' => 'required',
            // Add validation rules for other fields if needed
        ]);

        $member->update($request->all());
        return redirect()->route('uramembers.index')->with('success', 'Member updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('uramembers.index')->with('success', 'Member deleted successfully!');
    }

    /**
     * Import members from a CSV file.
     */
    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt|max:9048',
        ]);

        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $filePath = $file->getRealPath();

            $data = array_map('str_getcsv', file($filePath));

            // Skip header row if present
            if (isset($data[0]) && is_array($data[0]) && count($data[0]) > 0 && strtolower(trim($data[0][0])) === 'clientid') {
                $header = array_shift($data);
            }

            $importedCount = 0;
            $updatedCount = 0;
            $skippedCount = 0;

            foreach ($data as $row) {
                // Ensure row has enough columns (at least ClientId and Name for this setup)
                // We expect at least 2 columns (ClientId, Name). Other columns are optional.
                if (count($row) < 2) {
                    Log::warning("Skipping malformed row (not enough columns): " . implode(',', $row));
                    $skippedCount++;
                    continue;
                }

                $memberData = [
                    'ClientId' => $row[0],
                    'Name' => $row[1],
                    'AccountNumber' => $row[2] ?? null,
                    'checkNo' => $row[3] ?? null,
                    'Gender' => $row[4] ?? null,
                    'phone' => $row[5] ?? null, // Keep phone here if you want it imported even if not displayed
                ];

                // Validate individual row data
                $validator = Validator::make($memberData, [
                    'ClientId' => 'required',
                    'Name' => 'required',
                ]);

                if ($validator->fails()) {
                    Log::error("Validation failed for row: " . implode(',', $row) . " Errors: " . $validator->errors()->first());
                    $skippedCount++;
                    continue;
                }

                try {
                    $member = Member::where('ClientId', $memberData['ClientId'])->first();

                    if ($member) {
                        // Check if data has changed
                        $isChanged = false;
                        foreach ($memberData as $key => $value) {
                            // Trim values for comparison to avoid issues with extra whitespace
                            if (trim($member->{$key}) != trim($value)) {
                                $isChanged = true;
                                break;
                            }
                        }

                        if ($isChanged) {
                            $member->update($memberData);
                            $updatedCount++;
                        } else {
                            $skippedCount++; // Data is identical, skip update
                        }
                    } else {
                        Member::create($memberData);
                        $importedCount++;
                    }
                } catch (\Exception $e) {
                    Log::error("Error importing row: " . implode(',', $row) . " Error: " . $e->getMessage());
                    $skippedCount++;
                }
            }
            return redirect()->route('uramembers.index')->with('success', "CSV imported successfully! New records: $importedCount, Updated records: $updatedCount, Skipped records: $skippedCount.");
        }

        return redirect()->route('uramembers.index')->with('error', 'No file uploaded or an error occurred.');
    }
}
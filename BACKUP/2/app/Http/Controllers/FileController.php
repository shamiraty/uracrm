<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileSeries;
use App\Models\Keyword;
use App\Models\Department; // Assume you have this model
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index() {
        $files = File::all();
        return view('files.index', compact('files'));
    }

    public function create() {
        $fileSeries = FileSeries::all();
        $keywords = Keyword::all();
        $departments = Department::all(); // Assume you have this data
        return view('files.create', compact('fileSeries', 'keywords', 'departments'));
    }


    public function store(Request $request) {

            $validated = $request->validate([
                'file_series_id' => 'required|exists:file_series,id',
                'keyword1_id' => 'required|exists:keywords,id',
                'keyword2_id' => 'required|exists:keywords,id',
                'running_number' => 'required|numeric',
                'file_part' => 'required|string',
                'file_subject' => 'required|string|max:255',
                'reference_number' => 'required|string|max:255',
                'department_id' => 'required|exists:departments,id',
                'branch_id' => 'sometimes|exists:branches,id',
                'is_active' => 'sometimes|boolean'
            ]);

            $validated['branch_id'] = $validated['branch_id'] ?? auth()->user()->branch_id;
            $validated['is_active'] = $request->has('is_active') ? (bool) $request->input('is_active') : true;

            $file = File::create($validated);

            return redirect()->route('files.index')->with('success', 'File created successfully!');
       
    }

public function show($id)
{

    $file = File::with(['folios' => function ($query) {
        $query->orderBy('created_at', 'desc'); // Fetch folios in descending order
    }])->find($id);

    if (!$file) {
        return redirect()->route('files.index')->withErrors('File not found.');
    }

    return view('files.show', compact('file'));
}


    public function edit(File $file) {
        $fileSeries = FileSeries::all();
        $keywords = Keyword::all();
        $departments = Department::all(); // Assume you have this data
        return view('files.edit', compact('file', 'fileSeries', 'keywords', 'departments'));
    }

    public function update(Request $request, File $file) {
        $request->validate([
            // Add validation rules
        ]);
        $file->update($request->all());
        return redirect()->route('files.index')->with('success', 'File updated successfully!');
    }

    public function destroy(File $file) {
        $file->delete();
        return redirect()->route('files.index')->with('success', 'File deleted successfully!');
    }
}

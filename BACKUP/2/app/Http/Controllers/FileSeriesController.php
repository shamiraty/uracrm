<?php

namespace App\Http\Controllers;

use App\Models\FileSeries;
use Illuminate\Http\Request;

class FileSeriesController extends Controller
{

    public function index() {
        $fileSeries = FileSeries::all();
        return view('file_series.index', compact('fileSeries'));
    }

    public function create() {
        return view('file_series.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:file_series,name',
            'code' => 'required|string|unique:file_series,code'
        ]);

        FileSeries::create($request->all());

        return redirect()->route('file_series.index')->with('success', 'File series created successfully!');
    }



    public function show(FileSeries $fileSeries) {
        return view('file_series.show', compact('fileSeries'));
    }

    public function edit(FileSeries $fileSeries) {
        return view('file_series.edit', compact('fileSeries'));
    }

    public function update(Request $request, FileSeries $fileSeries)
    {
        $request->validate([
            'name' => 'required|string|unique:file_series,name,' . $fileSeries->id,
            'code' => 'required|string|unique:file_series,code,' . $fileSeries->id
        ]);

        $fileSeries->update($request->all());

        return redirect()->route('file_series.index')->with('success', 'File series updated successfully!');
    }

    public function destroy(FileSeries $fileSeries) {
        $fileSeries->delete();
        return redirect()->route('file_series.index')->with('success', 'File series deleted successfully!');
    }
}


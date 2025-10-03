<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use Illuminate\Http\Request;
use App\Imports\KeywordsImport;
use Maatwebsite\Excel\Facades\Excel;
class KeywordController extends Controller
{
    public function index() {
        $keywords = Keyword::all();
        return view('keywords.index', compact('keywords'));
    }

    public function create() {
        return view('keywords.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|unique:keywords,name',
        ]);
        $keyword = Keyword::create($request->all());
        return redirect()->route('keywords.index')->with('success', 'Keyword created successfully!');
    }

    public function show(Keyword $keyword) {
        return view('keywords.show', compact('keyword'));
    }

    public function edit(Keyword $keyword) {
        return view('keywords.edit', compact('keyword'));
    }

    public function update(Request $request, Keyword $keyword) {
        $request->validate([
            'name' => 'required|string|unique:keywords,name,' . $keyword->id,
        ]);
        $keyword->update($request->all());
        return redirect()->route('keywords.index')->with('success', 'Keyword updated successfully!');
    }

    public function destroy(Keyword $keyword) {
        $keyword->delete();
        return redirect()->route('keywords.index')->with('success', 'Keyword deleted successfully!');
    }

    public function import(Request $request)
{
    $request->validate([
        'keyword_file' => 'required|file|mimes:csv,txt'
    ]);

    Excel::import(new KeywordsImport, request()->file('keyword_file'));

    return redirect()->route('keywords.index')->with('success', 'Keywords imported successfully!');
}
public function showImportForm()
{
    return view('keywords.import');
}

}

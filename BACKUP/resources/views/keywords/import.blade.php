@extends('layouts.app')

@section('content')
    <h1>Import Keywords</h1>
    <form action="{{ route('keywords.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="keyword_file" class="form-label">Upload CSV File</label>
            <input type="file" class="form-control" id="keyword_file" name="keyword_file" required>
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>
@endsection

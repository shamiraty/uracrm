<!-- resources/views/members/upload_form.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Upload Employee Data</h2>

        <!-- Check for success message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="excel">Upload Excel File:</label>
                <input type="file" name="excel" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
@endsection

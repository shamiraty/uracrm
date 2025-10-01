@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Session messages -->
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- File upload form -->
    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Upload Member Data</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- File input field -->
                <div class="mb-3">
                    <label for="excel" class="form-label">Choose Excel or CSV File</label>
                    <input type="file" name="excel" id="excel" class="form-control" accept=".xlsx,.xls,.csv">
                    @error('excel')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
</div>
@endsection

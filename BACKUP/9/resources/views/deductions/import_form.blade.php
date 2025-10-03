@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center">
    <div class="card shadow-sm p-5 col-md-12">
        <h6 class="text-center mb-3"><i class="fas fa-file-import"></i> Import Deduction Details</h6>
        <p class="text-center">Select the check date to import deduction records.</p>

        <form action="{{ route('deductions.import') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="date" class="form-label fw-bold">Check Date:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    <input type="date" id="date" name="date" class="form-control" required>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-upload"></i> Import Deductions
            </button>
        </form>
    </div>
</div>
@endsection

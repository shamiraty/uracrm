@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Upload Payroll File</h1>
    <form action="{{ route('payroll.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="payroll_file">Payroll Excel File:</label>
            <input type="file" class="form-control" id="payroll_file" name="payroll_file" required>
        </div>
        <button type="submit" class="btn btn-primary">Import</button>
    </form>
</div>
@endsection

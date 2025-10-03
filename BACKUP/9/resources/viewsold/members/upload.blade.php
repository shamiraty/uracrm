<!-- resources/views/upload.blade.php -->

@extends('layouts.app')

@section('content')
<form action="{{ route('members.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="excel" required>
    <button type="submit">Upload Excel</button>
</form>
@endsection

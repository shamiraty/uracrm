{{-- resources/views/representatives/show.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Representative Details</h1>
    <div><strong>User:</strong> {{ $representative->user->name }}</div>
    <div><strong>Department:</strong> {{ $representative->department->name }}</div>
    <div><strong>Branch:</strong> {{ $representative->branch->name }}</div>
    <div><strong>District:</strong> {{ $representative->district->name }}</div>
    <div><strong>Region:</strong> {{ $representative->region->name }}</div>
    <a href="{{ route('representatives.index') }}" class="btn btn-primary">Back to List</a>
</div>
@endsection

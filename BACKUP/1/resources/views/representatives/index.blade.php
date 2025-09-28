{{-- resources/views/representatives/index.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Representatives</h1>
    <a href="{{ route('representatives.create') }}" class="btn btn-primary">Add New Representative</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Branch</th>
                <th>District</th>
                <th>Region</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($representatives as $representative)
                <tr>
                    <td>{{ $representative->id }}</td>
                    <td>{{ $representative->user->name }}</td>
                    <td>{{ $representative->department->name }}</td>
                    <td>{{ $representative->branch->name }}</td>
                    <td>{{ $representative->district->name }}</td>
                    <td>{{ $representative->region->name }}</td>
                    <td>
                        <a href="{{ route('representatives.show', $representative->id) }}" class="btn btn-info">View</a>
                        <a href="{{ route('representatives.edit', $representative->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('representatives.destroy', $representative->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

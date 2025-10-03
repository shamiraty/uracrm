@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Permissions</h1>
    <a href="{{ route('permissions.create') }}" class="btn btn-primary">Add New Permission</a>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $permission)
            <tr>
                <td>{{ $permission->name }}</td>
                <td>
                    <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-info">Edit</a>
                    <form action="{{ route('permissions.destroy', $permission) }}" method="POST" style="display:inline-block;">
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

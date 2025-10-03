@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Role</h1>
    <form method="POST" action="{{ route('roles.update', $role) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Role Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
        </div>
        <div class="form-group">
            <label for="permissions">Assign Permissions:</label>
            <select multiple class="form-control" id="permissions" name="permissions[]">
                @foreach($permissions as $permission)
                <option value="{{ $permission->id }}"
                        @if($role->permissions->contains($permission)) selected @endif>
                    {{ $permission->name }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Role</button>
    </form>
</div>
@endsection

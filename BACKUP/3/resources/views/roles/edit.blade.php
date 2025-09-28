@extends('layouts.app')
@section('content')
<div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Roles</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('enquiries.index') }}">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Edit Role</li>
            </ol>
        </nav>
    </div>
</div>
<div class="container">
<div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">Edit Role</h4>
                </div>
                <div class="card-body">
    <form method="POST" action="{{ route('roles.update', $role) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Role Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $role->name }}" required>
        </div>
        <div class="form-group">
            <label for="permissions">Assign Permissions:</label>
            <select  class="form-control" id="permissions" name="permissions[]">
                @foreach($permissions as $permission)
                <option value="{{ $permission->id }}"
                        @if($role->permissions->contains($permission)) selected @endif>
                    {{ $permission->name }}
                </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm mt-3">Update Role</button>
    </form>
</div>
@endsection

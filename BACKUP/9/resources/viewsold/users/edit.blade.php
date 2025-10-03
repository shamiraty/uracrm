{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit User</h1>
    @include('users.form', ['action' => route('users.update', $user->id), 'method' => 'PUT'])
</div>
@endsection --}}


@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($user) ? 'Edit User' : 'Create User' }}</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT') <!-- Laravel's Blade directive to spoof PUT requests -->
        @isset($method)
            @method($method)
        @endisset

        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required>
        </div>

        @if(!isset($user))
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
        @endif

        <div class="form-group">
            <label for="designation">Designation:</label>
            <input type="text" class="form-control" id="designation" name="designation" value="{{ old('designation', $user->designation ?? '') }}">
        </div>

        <div class="form-group">
            <label for="rank">Rank:</label>
            <input type="text" class="form-control" id="rank" name="rank" value="{{ old('rank', $user->rank ?? '') }}">
        </div>

        <div class="form-group">
            <label for="status">Status:</label>
            <select class="form-control" id="status" name="status">
                <option value="active" {{ (old('status', $user->status ?? '') == 'active') ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ (old('status', $user->status ?? '') == 'inactive') ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="form-group">
            <label for="phone_number">Phone Number:</label>
            <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}">
        </div>

        <div class="form-group">
            <label for="role">Role:</label>
            <select class="form-control" id="role" name="role">
                @foreach(\Spatie\Permission\Models\Role::all() as $role)
                    <option value="{{ $role->name }}" {{ (old('role', $user->role ?? '') == $role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update' : 'Create' }}</button>
    </form>
</div>
@endsection

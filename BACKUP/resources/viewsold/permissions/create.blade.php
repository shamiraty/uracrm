@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($permission) ? 'Edit' : 'Create' }} Permission</h1>
    <form method="POST" action="{{ isset($permission) ? route('permissions.update', $permission) : route('permissions.store') }}">
        @csrf
        @if(isset($permission))
            @method('PUT')
        @endif
        <div class="form-group">
            <label for="name">Permission Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name ?? '' }}" required>
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($permission) ? 'Update' : 'Create' }}</button>
    </form>
</div>
@endsection


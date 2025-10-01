{{-- resources/views/representatives/edit.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Representative</h1>
    <form action="{{ route('representatives.update', $representative->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="user_id">User:</label>
            <select id="user_id" name="user_id" class="form-control">
                @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}" {{ $representative->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <!-- Add dropdowns for department, branch, district, and region similarly, with pre-selected values -->
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

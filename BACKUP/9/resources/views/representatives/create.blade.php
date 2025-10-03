{{-- resources/views/representatives/create.blade.php --}}

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Representative</h1>
    <form action="{{ route('representatives.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="user_id" class="form-label">User:</label>
            <select id="user_id" name="user_id" class="form-control" required>
                @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="department_id" class="form-label">Department:</label>
            <select id="department_id" name="department_id" class="form-control" required>
                @foreach(\App\Models\Department::all() as $department)
                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="branch_id" class="form-label">Branch:</label>
            <select id="branch_id" name="branch_id" class="form-control" required>
                @foreach(\App\Models\Branch::all() as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="district_id" class="form-label">District:</label>
            <select id="district_id" name="district_id" class="form-control" required>
                @foreach(\App\Models\District::all() as $district)
                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="region_id" class="form-label">Region:</label>
            <select id="region_id" name="region_id" class="form-control" required>
                @foreach(\App\Models\Region::all() as $region)
                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>
@endsection

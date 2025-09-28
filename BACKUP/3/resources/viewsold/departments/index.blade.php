{{-- resources/views/departments/index.blade.php --}}

@extends('layouts.app')

@section('content')
<h1>Departments</h1>
<a href="{{ route('departments.create') }}" class="btn btn-primary mb-3">Add New Department</a>

<div class="card">
    <div class="card-header">
        Department List
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Department Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departments as $department)
                    <tr>
                        <td>{{ $loop->iteration }}</td> <!-- Automatically displays the row number -->
                        <td>{{ $department->name }}</td>
                        <td>
                            <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('departments.show', $department) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

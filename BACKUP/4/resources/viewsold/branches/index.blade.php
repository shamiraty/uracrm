{{-- resources/views/branches/index.blade.php --}}

@extends('layouts.app')

@section('content')
<h1>Branches</h1>
<a href="{{ route('branches.create') }}" class="btn btn-primary mb-3">Create New Branch</a>

<div class="card">
    <div class="card-header">
        Branch List
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Branch Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($branches as $branch)
                    <tr>
                        <td>{{ $loop->iteration }}</td> <!-- Automatically displays the row number -->
                        <td>{{ $branch->name }}</td>
                        <td>
                            <a href="{{ route('branches.edit', $branch) }}" class="btn btn-warning btn-sm">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('branches.show', $branch) }}" class="btn btn-info btn-sm">
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

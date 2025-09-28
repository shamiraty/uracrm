@extends('layouts.app')

@section('content')
<div class="page-breadcrumb d-flex align-items-center mb-4">
    <div class="breadcrumb-title pe-3">Permission</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('enquiries.index') }}">
                        <i class="bx bx-home-alt"></i>
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Create Permission</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0">{{ isset($permission) ? 'Edit' : 'Create' }} Permission</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($permission) ? route('permissions.update', $permission) : route('permissions.store') }}">
                        @csrf
                        @if(isset($permission))
                            @method('PUT')
                        @endif
                        <div class="form-group mb-4">
                            <label for="name">Permission Name:</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $permission->name ?? '' }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-plus-circle"></i> {{ isset($permission) ? 'Update' : 'Create' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

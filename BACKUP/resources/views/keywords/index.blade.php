@extends('layouts.app')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
    <h6 class="fw-semibold mb-0">Keywords</h6>
    <ul class="d-flex align-items-center gap-2">
      <li class="fw-medium">
        <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
          <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
          Keywords
        </a>
      </li>
      <li>-</li>
      <li class="fw-medium">Keywords</li>
    </ul>
  </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card basic-data-table">
        <div class="card-header">
            <h5 class="card-title mb-0"> <a href="#" class="btn btn-primary radius-30 mt-2 mt-lg-0">
                <i class="bx bxs-plus-square"></i> Keywords
            </a></h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
        <table class="table border-primary-table mb-0" id="dataTable" data-page-length='10'>
        <thead>
            <tr>
                <th>Name</th>
                <th>Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($keywords as $keyword)
                <tr>
                    <td>{{ $keyword->name }}</td>
                    <td>{{ $keyword->code }}</td>
                    <td>
                        <a href="{{ route('keywords.edit', $keyword) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form action="{{ route('keywords.destroy', $keyword) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table></div></div></div>
@endsection

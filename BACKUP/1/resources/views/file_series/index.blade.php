@extends('layouts.app')

@section('content')
  

        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <h6 class="fw-semibold mb-0">File Series</h6>
            <ul class="d-flex align-items-center gap-2">
              <li class="fw-medium">
                <a href="#" class="d-flex align-items-center gap-1 hover-text-primary">
                  <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                  File Series
                </a>
              </li>
              <li>-</li>
              <li class="fw-medium">File Series</li>
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
                        <i class="bx bxs-plus-square"></i> File Series
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
            @foreach ($fileSeries as $series)
                <tr>
                    <td>{{ $series->name }}</td>
                    <td>{{ $series->code }}</td>
                    <td>
                        <a href="{{ route('file_series.edit', $series) }}" class="btn btn-sm btn-secondary">Edit</a>
                        <form action="{{ route('file_series.destroy', $series) }}" method="POST" style="display: inline;">
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

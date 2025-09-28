@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-6">
        </div>
        <div class="col-md-6 text-end">
            {{-- Removed: Add New Member Button and its modal trigger --}}
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#importCsvModal">
                <i class="fas fa-upload"></i> Import CSV
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <form action="{{ route('uramembers.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by Client ID, Name, or Check No..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Search</button>
                <a href="{{ route('uramembers.index') }}" class="btn btn-outline-danger ms-2">Clear</a>
            </form>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                 <table class="table border-primary-table mb-0" id="dataTable">
                    <thead>
                        <tr>
                            <th>Client ID</th>
                            <th>Name</th>
                            <th>Account Number</th>
                            <th>Check No</th>
                            <th>Gender</th>
                            {{-- Removed: Phone column --}}
                            {{-- Removed: Actions column --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($members as $member)
                            <tr>
                                <td>{{ $member->ClientId }}</td>
                                <td>{{ $member->Name }}</td>
                                <td>{{ $member->AccountNumber }}</td>
                                <td>{{ $member->checkNo }}</td>
                                <td>{{ $member->Gender }}</td>
                                {{-- Removed: Phone data --}}
                                {{-- Removed: Actions buttons (Edit, Delete) --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Removed: Add Member Modal --}}
{{-- Removed: Edit Member Modal --}}
{{-- Removed: Delete Member Modal --}}

<div class="modal fade" id="importCsvModal" tabindex="-1" aria-labelledby="importCsvModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importCsvModalLabel">Import Members from CSV</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('uramembers.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="csvFile" class="form-label">Choose CSV File</label>
                        <input class="form-control" type="file" id="csvFile" name="csv_file" accept=".csv, .txt" required>
                    </div>
                    <small class="text-muted">Ensure your CSV columns are in the order: ClientId, Name, Account Number, checkNo, Gender, phone (optional).</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')

@endpush
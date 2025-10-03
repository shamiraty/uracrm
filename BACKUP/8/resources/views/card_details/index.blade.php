@extends('layouts.app')

@section('content')
    {{-- Success and Error Message Display --}}
    @if (session('success'))
        <div class="alert alert-success">
            {!! session('success') !!} {{-- Use {!! !!} to render bold/HTML from controller message --}}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    {{-- Top Action Buttons: Sync from API and Export to CSV --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        {{-- Form to trigger data synchronization from an external API --}}
        <form action="{{ route('card-details.sync') }}" method="POST">
            @csrf {{-- CSRF token for security --}}
            <button type="submit" class="btn btn-primary">Sync from API (Load New Data)</button>
        </form>

        {{-- Form to trigger CSV export. It passes current filter parameters. --}}
        <form action="{{ route('card-details.exportCsv') }}" method="GET" class="ms-2">
            {{-- Hidden inputs to pass the currently applied filter values to the export route --}}
            @foreach($filters as $key => $value)
                @if($value) {{-- Only include if the filter has a value --}}
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            <button type="submit" class="btn btn-success">Export to CSV</button>
        </form>
    </div>

    

    {{-- Filter Form Section inside an Accordion --}}
    <div class="accordion" id="filterAccordion">
        <div class="accordion-item mt-3">
            <h6 class="accordion-header" id="headingFilters">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters">
                    <span>Filter Card Details</span>
                </button>
            </h6>
            <div id="collapseFilters" class="accordion-collapse collapse" aria-labelledby="headingFilters" data-bs-parent="#filterAccordion">
                <div class="accordion-body">
                    {{-- Form for applying filters. Uses GET method to keep filters in URL. --}}
                    <form action="{{ route('card-details.index') }}" method="GET">
                        <div class="row g-3">
                            {{-- Status Filter --}}
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="all" {{ ($filters['status'] ?? 'all') == 'all' ? 'selected' : '' }}>All Statuses</option>
                                    <option value="Applied" {{ ($filters['status'] ?? '') == 'Applied' ? 'selected' : '' }}>Applied</option>
                                    <option value="Rejected" {{ ($filters['status'] ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="Printed" {{ ($filters['status'] ?? '') == 'Printed' ? 'selected' : '' }}>Printed</option>
                                    <option value="Issued" {{ ($filters['status'] ?? '') == 'Issued' ? 'selected' : '' }}>Issued</option>
                                    <option value="Received" {{ ($filters['status'] ?? '') == 'Received' ? 'selected' : '' }}>Received</option>
                                </select>
                            </div>
                            {{-- Mkoa wa Kipolisi Filter --}}
                            <div class="col-md-3">
                                <label for="mkoa_wa_kipolisi" class="form-label">Region</label>
                                <select class="form-select" id="mkoa_wa_kipolisi" name="mkoa_wa_kipolisi">
                                    <option value="all" {{ ($filters['mkoa_wa_kipolisi'] ?? 'all') == 'all' ? 'selected' : '' }}>All Regions</option>
                                    {{-- Loop through unique regions fetched from the controller --}}
                                    @foreach($mikoaWaKipolisi as $mkoa)
                                        <option value="{{ $mkoa }}" {{ ($filters['mkoa_wa_kipolisi'] ?? '') == $mkoa ? 'selected' : '' }}>{{ $mkoa }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Registered Date (From) Filter --}}
                            <div class="col-md-3">
                                <label for="start_date" class="form-label">Date (From)</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $filters['start_date'] ?? '' }}">
                            </div>
                            {{-- Registered Date (To) Filter --}}
                            <div class="col-md-3">
                                <label for="end_date" class="form-label">Date (To)</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $filters['end_date'] ?? '' }}">
                            </div>
                            {{-- Filter Action Buttons --}}
                            <div class="col-12">
                                <button type="submit" class="btn btn-dark btn-sm">Apply Filters</button>
                                {{-- Link to reset filters by navigating back to the index without any query parameters --}}
                                <a href="{{ route('card-details.index') }}" class="btn btn-outline-dark btn-sm ms-2">Reset Filters</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

 

    {{-- Conditional Display for Card Details Table --}}
    @if($cardDetails->isEmpty())
        <div class="alert alert-info text-center mt-4">
            No card details found in your local database. Click "Sync from API" to load or adjust filters.
        </div>
    @else
        <div class="card shadow-sm basic-data-table mt-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-primary-table mb-0" id="dataTable">
                        <thead class="table-primary">
                            <tr>
                                <th>ID</th>
                                <th>Check Number</th>
                                <th>Client ID</th>
                                <th>Name</th>
                                <th>Region</th>
                                <th>District</th>
                                <th>Application</th>
                                <th>Loaded</th>
                                <th>Card Approval</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cardDetails as $card)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $card->check_namba ?? 'N/A' }}</td>
                                    <td>{{ $card->member->ClientId ?? 'N/A' }}</td>
                                    <td>{{ $card->jina_kamili ?? 'N/A' }}</td>
                                    <td>{{ $card->mkoa_wa_kipolisi ?? 'N/A' }}</td>
                                    <td>{{ $card->wilaya_ya_kipolisi ?? 'N/A' }}</td>
                                    <td>{{ $card->registered_date?->format('F j, Y') }}</td>
                                    <td>{{ $card->created_at?->format('F j, Y') }}</td>
                                    <td>{{ $card->updated_at?->format('F j, Y') }}</td>
                                    <td>{{ $card->namba_ya_simu ?? 'N/A' }}</td>
                                    <td>
                                        {{-- Dynamic badge styling based on card status --}}
                                        <span class="badge {{
                                            $card->status == 'Applied' ? 'bg-secondary' :
                                            ($card->status == 'Processing' ? 'bg-info' :
                                            ($card->status == 'Rejected' ? 'bg-danger' :
                                            ($card->status == 'Printed' ? 'bg-primary' :
                                            ($card->status == 'Issued' ? 'bg-success' :
                                            ($card->status == 'Received' ? 'bg-success' : 'bg-light text-dark')))))
                                        }}">
                                            {{ $card->status ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- Dropdown for actions (View, Edit, Change Status, Delete) --}}
                                        <div class="dropdown ms-auto">
                                            <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                                                <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                                            </a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('card-details.show', $card->id) }}"><i class="mdi mdi-eye me-2"></i>View Detail</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="{{ route('card-details.edit', $card->id) }}"><i class="mdi mdi-pencil me-2"></i>Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="{{ route('card-details.showStatusUpdateForm', $card->id) }}"><i class="mdi mdi-pencil-box-multiple me-2"></i>Change Status</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('card-details.destroy', $card->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this card detail from LOCAL DB AND PRIMARY API?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger"><i class="mdi mdi-delete me-2"></i>Delete</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
@endpush
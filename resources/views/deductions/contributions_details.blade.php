@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fw-bold mb-2" style="color: #17479E;">
                <i class="fas fa-chart-line me-2"></i>Contribution Details
            </h2>
            <p class="text-muted mb-0">Individual member contribution history and breakdown</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-sm dropdown-toggle rounded-pill shadow-sm" style="background: #17479E; color: white;" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download me-1"></i>Export Options
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                    <li>
                        <a class="dropdown-item rounded-2" href="{{ route('deductions.export.csv', ['checkNumber' => $checkNumber]) }}">
                            <i class="fas fa-file-csv me-2 text-success"></i>Export to CSV
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item rounded-2" href="{{ route('exportMemberContributionPdf', ['checkNumber' => $checkNumber]) }}" target="_blank">
                            <i class="fas fa-file-pdf me-2 text-danger"></i>Export to PDF
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Employee Information Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); border: none; padding: 1rem 1.5rem;">
            <h5 class="mb-0 text-white fw-bold">
                <i class="fas fa-user me-2"></i>Member Information
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2"><strong style="color: #17479E;">Full Name:</strong> {{ $firstName }} {{ $middleName }} {{ $lastName }}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-2"><strong style="color: #17479E;">Check Number:</strong> {{ $checkNumber }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contribution History Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #17479E 0%, #4facfe 100%); border: none; padding: 1.25rem 1.5rem;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white fw-bold">
                    <i class="fas fa-table me-2"></i>Contribution History
                </h5>
                <span class="badge bg-white px-3 py-2 fw-bold" style="color: #17479E;">{{ count($deductionTypes) }} Types</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 modern-table">
                    <thead style="background-color: #f8f9fa;">
                        <tr>
                            <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">Date</th>
                            @foreach($deductionTypes as $type)
                                <th class="border-0 py-3 px-4 fw-semibold text-muted text-uppercase" style="font-size: 0.75rem;">{{ strtoupper($type) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $columnTotals = array_fill_keys($deductionTypes, 0);
                        @endphp

                        @foreach($formattedData as $year => $months)
                            <tr>
                                <td colspan="{{ count($deductionTypes) + 1 }}" class="fw-bold py-3 px-4">{{ $year }}</td>
                            </tr>
                            @foreach($months as $month => $deductions)
                                <tr>
                                    <td class="align-middle px-4">{{ $month }}</td>
                                    @foreach($deductionTypes as $type)
                                        @php
                                            $amount = $deductions[$type] ?? 0;
                                            $columnTotals[$type] += $amount;
                                        @endphp
                                        <td class="align-middle px-4">{{ number_format($amount, 2) }}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    <tfoot style="background-color: #f8f9fa;">
                        <tr>
                            <th class="py-3 px-4">TOTAL</th>
                            @foreach($deductionTypes as $type)
                                <th class="py-3 px-4">{{ number_format($columnTotals[$type], 2) }}</th>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.modern-table tbody tr {
    border-bottom: 1px solid rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.modern-table tbody tr:hover {
    background-color: rgba(23, 71, 158, 0.03);
}

.dropdown-item:hover {
    background-color: rgba(23, 71, 158, 0.1);
}
</style>

@endsection

@extends('layouts.app')

@section('content')
<style>
    /* Custom styling for tables */
    #trends th, #trends td {
        border: 1px solid #dee2e6; /* Light border for table cells */
        padding: 12px; /* Added padding for better readability */
        text-align: left; /* Align text to the left */
    }

    .accordion-header {
        padding: 10px;
        background-color: #f8f9fa; /* Light grey background for headers */
        border-bottom: 1px solid #dee2e6; /* Add a border for separation */
    }

    .accordion-header:hover {
        background-color: #e2e6ea; /* Darker grey on hover */
    }

    .nav-tabs {
        display: flex; /* Use flexbox for full width */
        justify-content: space-around; /* Distribute tabs evenly */
        width: 100%; /* Full width */
        padding: 0; /* Remove default padding */
        margin: 0; /* Remove default margin */
        list-style-type: none; /* Remove default list styles */
    }

    .nav-item {
        flex-grow: 1; /* Allow each tab to grow equally */
        text-align: center; /* Center align text */
    }

    .nav-link {
        padding: 10px; /* More padding for a better click area */
        font-weight: bold; /* Bold text for better emphasis */
        color: #007bff; /* Bootstrap primary color */
        transition: background-color 0.2s; /* Smooth background transition */
    }

    .nav-link.active {
        background-color: #007bff; /* Active tab color */
        color: white; /* White text for active tab */
    }

    .nav-link:hover {
        background-color: #e9ecef; /* Hover effect for tabs */
    }

    .tab-content {
        background-color: #fff; /* White background for content */
        border-radius: 0.25rem; /* Rounded corners */
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1); /* Subtle shadow */
        padding: 20px; /* Padding for content */
    }

    .card-header {
        background-color: #007bff; /* Card header color */
        color: white; /* White text for card header */
    }

    /* Responsive styling for tables */
    @media (max-width: 768px) {
        #trends th, #trends td {
            padding: 8px; /* Reduced padding on smaller screens */
        }
    }
</style>

<div class="container-fluid">
    <!-- Header message indicating trends are for the current date -->
   <!-- Check if startDate and endDate are available -->
@if (isset($startDate) && isset($endDate))
    <!-- Form has been submitted, show the selected start and end dates -->
    <div class="alert alert-success text-center">
        <strong>Showing trends from {{ \Carbon\Carbon::parse($startDate)->format('l, F j, Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('l, F j, Y') }}</strong>
    </div>
@else
    <!-- Form has not been submitted, show the current date trends -->
    <div class="alert alert-info text-center">
        <strong>Trends are for today: {{ \Carbon\Carbon::now()->format('l, F j, Y') }}</strong>
    </div>
@endif



    <form method="GET" action="{{ route('trends') }}" id="filterForm">
    <div class="row mb-3">
        <div class="col-md-4">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date', date('Y-m-d')) }}">
        </div>
        <div class="col-md-4">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date', date('Y-m-d')) }}">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-secondary btn-sm w-100" id="submitBtn">Filter</button>
        </div>
    </div>
</form>
<!-- Spinner Modal -->
<div class="modal fade" id="spinnerModal" tabindex="-1" role="dialog" aria-labelledby="spinnerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content text-center">
            <div class="modal-body">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Processing...</p>
            </div>
        </div>
    </div>
</div>





<div class="card mb-3">
    <div class="card-header bg-primary text-white">Overall Registered Enquiry</div>
    <div class="card-body">
    <h6>Overall Enquiry Status</h6>
        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Count</th>
                    <th>Sum</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $overallStatusMetrics = [];
                    foreach ($regions as $region) {
                        foreach ($region->districts as $district) {
                            foreach ($districtMetrics[$region->id][$district->id]['statusMetrics'] as $status => $metrics) {
                                if (!isset($overallStatusMetrics[$status])) {
                                    $overallStatusMetrics[$status] = ['count' => 0, 'sum' => 0];
                                }
                                $overallStatusMetrics[$status]['count'] += $metrics['count'];
                                $overallStatusMetrics[$status]['sum'] += $metrics['sum'];
                            }
                        }
                    }
                @endphp
                @foreach ($overallStatusMetrics as $status => $metrics)
                    <tr>
                        <td>{{ $status }}</td>
                        <td>{{ $metrics['count'] }}</td>
                        <td>{{ number_format($metrics['sum'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th>{{ array_sum(array_column($overallStatusMetrics, 'count')) }}</th>
                    <th>{{ number_format(array_sum(array_column($overallStatusMetrics, 'sum')), 2) }}</th>
                </tr>
            </tfoot>
        </table>

        <h6>Overall Enquiry Type</h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Count</th>
                    <th>Sum</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $overallTypeMetrics = [];
                    foreach ($regions as $region) {
                        foreach ($region->districts as $district) {
                            foreach ($districtMetrics[$region->id][$district->id]['typeMetrics'] as $type => $typeMetrics) {
                                if (!isset($overallTypeMetrics[$type])) {
                                    $overallTypeMetrics[$type] = ['count' => 0, 'sum' => 0];
                                }
                                $overallTypeMetrics[$type]['count'] += $typeMetrics['count'];
                                $overallTypeMetrics[$type]['sum'] += $typeMetrics['sum'];
                            }
                        }
                    }
                @endphp
                @foreach ($overallTypeMetrics as $type => $typeMetrics)
                    <tr>
                        <td>{{ $type }}</td>
                        <td>{{ $typeMetrics['count'] }}</td>
                        <td>{{ number_format($typeMetrics['sum'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <th>{{ array_sum(array_column($overallTypeMetrics, 'count')) }}</th>
                    <th>{{ number_format(array_sum(array_column($overallTypeMetrics, 'sum')), 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>






<div class="card mb-3">
    <div class="card-header bg-primary text-white">Registered Enquiries by Regions</div>
    <div class="card-body">
        <ul class="nav nav-tabs nav-primary" role="tablist">
            @foreach ($regionMetrics as $regionId => $metrics)
                <li class="nav-item" role="presentation">
                    <a class="nav-link @if ($loop->first) active @endif" data-bs-toggle="tab" href="#region-{{ $regionId }}" role="tab" aria-selected="@if ($loop->first) true @else false @endif">
                        <div class="d-flex align-items-center">
                            <div class="tab-icon"><i class='bx bx-map font-18 me-1'></i></div>
                            <div class="tab-title">{{ $regions->find($regionId)->name }}</div>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content py-3" id="pills-tabContent">
            @foreach ($regionMetrics as $regionId => $metrics)
                <div class="tab-pane fade @if ($loop->first) show active @endif" id="region-{{ $regionId }}" role="tabpanel">
                    <h6>Enquiry Status Metrics</h6>
                    <table class="table table-bordered mb-4">
                        <thead>
                            <tr>
                                <th>Status</th>
                                <th>Count</th>
                                <th>Sum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $statusTotalCount = 0;
                                $statusTotalSum = 0;
                            @endphp
                            @foreach ($metrics['statusMetrics'] as $status => $metric)
                                <tr>
                                    <td>{{ $status }}</td>
                                    <td>{{ $metric['count'] }}</td>
                                    <td>{{ number_format($metric['sum'], 2) }}</td> <!-- Format sum -->
                                </tr>
                                @php
                                    $statusTotalCount += $metric['count'];
                                    $statusTotalSum += $metric['sum'];
                                @endphp
                            @endforeach
                            <tr>
                                <td><strong>Subtotal</strong></td>
                                <td><strong>{{ $statusTotalCount }}</strong></td>
                                <td><strong>{{ number_format($statusTotalSum, 2) }}</strong></td> <!-- Format sum -->
                            </tr>
                        </tbody>
                    </table>

                    <h6>Enquiry Type Metrics</h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Count</th>
                                <th>Sum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $typeTotalCount = 0;
                                $typeTotalSum = 0;
                            @endphp
                            @foreach ($metrics['typeMetrics'] as $type => $typeMetric)
                                <tr>
                                    <td>{{ $type }}</td>
                                    <td>{{ $typeMetric['count'] }}</td>
                                    <td>{{ number_format($typeMetric['sum'], 2) }}</td> <!-- Format sum -->
                                </tr>
                                @php
                                    $typeTotalCount += $typeMetric['count'];
                                    $typeTotalSum += $typeMetric['sum'];
                                @endphp
                            @endforeach
                            <tr>
                                <td><strong>Subtotal</strong></td>
                                <td><strong>{{ $typeTotalCount }}</strong></td>
                                <td><strong>{{ number_format($typeTotalSum, 2) }}</strong></td> <!-- Format sum -->
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header bg-primary text-white">Registered Enquiries by Districts</div>
    <div class="card-body">
        <div class="accordion" id="accordionRegions">
            @foreach ($regions as $region)
                <div class="accordion-item bg-white">
                    <h2 class="accordion-header" id="heading{{ $region->id }}">
                        <button class="accordion-button text-uppercase bg-light " type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $region->id }}" aria-expanded="true" aria-controls="collapse{{ $region->id }}">
                        <span class="badge badge-primary text-primary ">{{ $loop->iteration }} {{ $region->name }}</span>
                        </button>
                    </h2>

                    <div id="collapse{{ $region->id }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $region->id }}" data-bs-parent="#accordionRegions">
                        <div class="accordion-body">
                            <div class="row row-cols-1 row-cols-md-1 row-cols-lg-2">
                                <div class="col">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs nav-primary" role="tablist">
                                                @foreach ($region->districts as $district)
                                                    <li class="nav-item" role="presentation">
                                                        <a class="nav-link @if ($loop->first) active @endif" data-bs-toggle="tab" href="#tab-{{ $district->id }}" role="tab" aria-selected="@if ($loop->first) true @else false @endif">
                                                            <div class="d-flex align-items-center justify-content-center">
                                                                <div class="tab-icon"><i class='bx bx-home font-18 me-1'></i></div>
                                                                <div class="tab-title">{{ $district->name }}</div>
                                                            </div>
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>

                                            <div class="tab-content py-3">
                                                @foreach ($region->districts as $district)
                                                    <div class="tab-pane fade @if ($loop->first) show active @endif" id="tab-{{ $district->id }}" role="tabpanel">
                                                        <h6>Enquiry Status Metrics</h6>
                                                        <table class="table table-bordered mb-4">
                                                            <thead>
                                                                <tr>
                                                                    <th>Status</th>
                                                                    <th>Count</th>
                                                                    <th>Sum</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($districtMetrics[$region->id][$district->id]['statusMetrics'] as $status => $metrics)
                                                                    <tr>
                                                                        <td>{{ $status }}</td>
                                                                        <td>{{ $metrics['count'] }}</td>
                                                                        <td>{{ number_format($metrics['sum'], 2) }}</td> <!-- Format sum -->
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th>Subtotal</th>
                                                                    <th>{{ array_sum(array_column($districtMetrics[$region->id][$district->id]['statusMetrics'], 'count')) }}</th>
                                                                    <th>{{ number_format(array_sum(array_column($districtMetrics[$region->id][$district->id]['statusMetrics'], 'sum')), 2) }}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>

                                                        <h6>Enquiry Type Metrics</h6>
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Type</th>
                                                                    <th>Count</th>
                                                                    <th>Sum</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($districtMetrics[$region->id][$district->id]['typeMetrics'] as $type => $typeMetrics)
                                                                    <tr>
                                                                        <td>{{ $type }}</td>
                                                                        <td>{{ $typeMetrics['count'] }}</td>
                                                                        <td>{{ number_format($typeMetrics['sum'], 2) }}</td> <!-- Format sum -->
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th>Subtotal</th>
                                                                    <th>{{ array_sum(array_column($districtMetrics[$region->id][$district->id]['typeMetrics'], 'count')) }}</th>
                                                                    <th>{{ number_format(array_sum(array_column($districtMetrics[$region->id][$district->id]['typeMetrics'], 'sum')), 2) }}</th>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

            </div>
        </div>
    </div>
</div>









<script>
    document.getElementById('filterForm').addEventListener('submit', function() {
        // Show the spinner modal
        $('#spinnerModal').modal('show');
           
    });

</script>
@endsection

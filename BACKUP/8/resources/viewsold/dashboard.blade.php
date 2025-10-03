
@extends('layouts.app')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    #example2 th, #example2 td {
        border: 1px solid #dee2e6; /* Light border for table cells */
        padding: 8px; /* Adds padding inside cells for better readability */
        text-align: left; /* Aligns text to the left inside cells */
    }

    #example2 {
        border-left: 1px solid #dee2e6; /* Left border */
        border-right: 1px solid #dee2e6; /* Right border */
    }
</style>

<div class="row">
    <div class="col-12 col-lg-6">
        <div class="card radius-10">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">LOAN APPLICATIONS</h6>
                    </div>
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                            <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('loan_trends') }}">View more</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div>
                    pending...
                    <canvas id="" style="height:250px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card radius-10">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">ENQUIRIES</h6>
                    </div>
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                            <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('trends') }}">View more</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div>
                pending...
                    <canvas id="" style="height:250px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card radius-10">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0">ENQUIRIES</h6>
                    </div>
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                            <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('trends') }}">View more</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div>
                    <canvas id="chart3" style="height:250px;"></canvas>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($enquiryTypeFrequency as $type)
                    <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                        {{ ucfirst($type->type) }} <span class="badge bg-primary rounded-pill">{{ $type->frequency }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card radius-10">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0 text-uppercase">Loan Applications</h6>
                    </div>
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                            <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ url('loan_trends') }}">View more</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div>
                    <canvas id="chart4" style="height:250px;"></canvas>
                </div>
            </div>
            <ul class="list-group list-group-flush">
                @foreach($loanApplicationStatusFrequency as $status)
                    <li class="list-group-item d-flex bg-transparent justify-content-between align-items-center">
                        {{ ucfirst($status->status) }} <span class="badge bg-success rounded-pill">{{ $status->frequency }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="card radius-10">
            <div class="card-body">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 g-3">
                    <div class="col">
                        <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary font-14">ALL ENQUIRIES</p>
                                        <h5 class="my-0">{{ $enquiryFrequencyAllTime->frequency }}</h5>
                                    </div>
                                    <div class="text-primary ms-auto font-30">
                                        <i class='bx bx-file-blank'></i> <!-- Icon representing all enquiries -->
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1" id="chart4"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary font-14">ALL LOAN APPLICATIONS</p>
                                        <h5 class="my-0">{{ $loanApplicationFrequencyAllTime->frequency }}</h5>
                                    </div>
                                    <div class="text-danger ms-auto font-30">
                                        <i class='bx bx-money'></i> <!-- Updated icon for loan applications (not in dollars) -->
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1" id="chart5"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary font-14">NEW MEMBERSHIPS</p>
                                        <h5 class="my-0">{{ $enquiryTypeMembership->frequency }}</h5>
                                    </div>
                                    <div class="text-success ms-auto font-30">
                                        <i class='bx bx-user-plus'></i> <!-- Updated icon for new memberships -->
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1" id="chart6"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary font-14">ALL SHARES</p>
                                        <h5 class="my-0">{{ $enquiryTypeShare->frequency }}</h5>
                                    </div>
                                    <div class="text-warning ms-auto font-30">
                                        <i class='bx bx-share'></i> <!-- Updated icon for shares (Hisa kwenye bank) -->
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1" id="chart7"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card radius-10 overflow-hidden mb-0 shadow-none border">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <p class="mb-0 text-secondary font-14">ALL DEDUCTIONS</p>
                                        <h5 class="my-0">{{ $enquiryTypeDeduction->frequency }}</h5>
                                    </div>
                                    <div class="text-info ms-auto font-30">
                                        <i class='bx bx-minus-circle'></i> <!-- Updated icon for deductions (kupunguza makato) -->
                                    </div>
                                </div>
                            </div>
                            <div class="mt-1" id="chart8"></div>
                        </div>
                    </div>
                </div><!--end row-->
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="card radius-10">
        <div class="card-header bg-transparent">
            <div class="d-flex align-items-center">
                <div>
                    <h6 class="mb-0">RECENT ENQUIRIES</h6>
                </div>
                <div class="dropdown ms-auto">
                    <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown">
                        <i class='bx bx-dots-horizontal-rounded font-22 text-option'></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="javascript:;">View more</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle mt-3 mb-0 table table-striped table-bordered table-hover table-border" id="example2">
                    <thead class="table-light">
                        <tr>
                            <th>SN</th>
                            <th>Date Received</th>
                            <th>Force No</th>
                            <th>Account Number</th>
                            <th>Bank Name</th>
                            <th>Full Name</th>
                            <th>District</th>
                            <th>Phone</th>
                            <th>Region</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($enquiries as $index => $enquiry)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td class="text-lowercase">{{ $enquiry->date_received }}</td>
                            <td class="text-lowercase">{{ $enquiry->force_no }}</td>
                            <td class="text-lowercase">{{ $enquiry->account_number }}</td>
                            <td class="text-uppercase">{{ $enquiry->bank_name }}</td>
                            <td class="text-lowercase">{{ $enquiry->full_name }}</td>
                            <td class="text-lowercase">{{ $enquiry->district }}</td>
                            <td class="text-lowercase">{{ $enquiry->phone }}</td>
                            <td class="text-uppercase">{{ $enquiry->region }}</td>
                            <td class="text-lower">
                                @if($enquiry->status == 'approved')
                                    <span class="badge bg-success p-2 text-white">
                                        <i class="fa fa-check-circle"></i> Approved
                                    </span>
                                @elseif($enquiry->status == 'rejected')
                                    <span class="badge bg-danger p-2 text-white">
                                        <i class="fa fa-times-circle"></i> Rejected
                                    </span>
                                @elseif($enquiry->status == 'assigned')
                                    <span class="badge bg-warning p-2 text-dark">
                                        <i class="fa fa-tasks"></i> Assigned
                                    </span>
                                @else
                                    <span class="badge bg-secondary p-2 text-white">
                                        <i class="fa fa-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script>
    // Prepare data for the Enquiry Types Frequency chart
    var enquiryLabels = @json($enquiryTypeFrequency->pluck('type'));
    var enquiryData = @json($enquiryTypeFrequency->pluck('frequency'));

    // Enquiry Types Frequency Chart
    var ctx1 = document.getElementById('chart3').getContext('2d');
    var enquiryChart = new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: enquiryLabels.length ? enquiryLabels : ['No Data'],
            datasets: [{
                data: enquiryData.length ? enquiryData : [0],
                backgroundColor: [
                    '#0d6efd',
                    '#6f42c1',
                    '#d63384',
                    '#fd7e14',
                    '#15ca20',
                    '#0dcaf0'
                ],
                borderWidth: 1.5
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    display: true,
                },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.raw || 0;
                            return label + ': ' + value;
                        }
                    }
                },
                // Add plugin to draw text inside the donut chart
                beforeDraw: function(chart) {
                    var ctx = chart.ctx;
                    ctx.save();
                    var width = chart.width,
                        height = chart.height,
                        centerX = width / 2,
                        centerY = height / 2;

                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    var activeElement = chart.tooltip._active && chart.tooltip._active[0];

                    if (activeElement) {
                        // Increase font size on hover
                        ctx.font = 'bold 20px Arial';  // Increase size and make it bold
                        
                        // Get the hovered data
                        var datasetIndex = activeElement.datasetIndex;
                        var index = activeElement.index;
                        var label = chart.data.labels[index];
                        var value = chart.data.datasets[datasetIndex].data[index];

                        // Draw the label and value inside the chart
                        ctx.fillText(label + ': ' + value, centerX, centerY);
                    } else {
                        // Default size when no segment is hovered
                        ctx.font = '16px Arial';
                        ctx.fillText('', centerX, centerY);
                    }

                    ctx.restore();
                }
            },
            hover: {
                onHover: function(e) {
                    var activePoints = enquiryChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
                    if (activePoints.length > 0) {
                        // Trigger re-render to show the label inside the chart with larger size
                        enquiryChart.update();
                    }
                }
            }
        }
    });

    // Prepare data for the Loan Application Status Frequency chart
    var loanLabels = @json($loanApplicationStatusFrequency->pluck('status'));
    var loanData = @json($loanApplicationStatusFrequency->pluck('frequency'));

    // Loan Application Status Frequency Chart
    var ctx2 = document.getElementById('chart4').getContext('2d');
    var loanChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: loanLabels.length ? loanLabels : ['No Data'],
            datasets: [{
                data: loanData.length ? loanData : [0],
                backgroundColor: [
                    '#fd7e14',
                    '#0dcaf0',
                    '#15ca20',
                    '#d63384',
                    '#6f42c1'
                ],
                borderWidth: 1.5
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    display: true,
                },
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.raw || 0;
                            return label + ': ' + value;
                        }
                    }
                },
                // Add plugin to draw text inside the donut chart
                beforeDraw: function(chart) {
                    var ctx = chart.ctx;
                    ctx.save();
                    var width = chart.width,
                        height = chart.height,
                        centerX = width / 2,
                        centerY = height / 2;

                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';

                    var activeElement = chart.tooltip._active && chart.tooltip._active[0];

                    if (activeElement) {
                        // Increase font size on hover
                        ctx.font = 'bold 20px Arial';  // Increase size and make it bold
                        
                        // Get the hovered data
                        var datasetIndex = activeElement.datasetIndex;
                        var index = activeElement.index;
                        var label = chart.data.labels[index];
                        var value = chart.data.datasets[datasetIndex].data[index];

                        // Draw the label and value inside the chart
                        ctx.fillText(label + ': ' + value, centerX, centerY);
                    } else {
                        // Default size when no segment is hovered
                        ctx.font = '16px Arial';
                        ctx.fillText('', centerX, centerY);
                    }

                    ctx.restore();
                }
            },
            hover: {
                onHover: function(e) {
                    var activePoints = loanChart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
                    if (activePoints.length > 0) {
                        // Trigger re-render to show the label inside the chart with larger size
                        loanChart.update();
                    }
                }
            }
        }
    });
</script>



@endsection









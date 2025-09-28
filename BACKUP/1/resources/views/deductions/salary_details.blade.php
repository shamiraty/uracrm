@extends('layouts.app')

@section('content')




<div class="container">
    <div class="card">
        <div class="card-header">
            <h6 class="text-uppercase  card-title">Salary Deduction Details for Check Number: {{ $checkNumber }} [ {{ $firstName }} {{ $middleName }}  {{ $lastName }} ]</p>
        
        <div class="dropdown">
    <a class="dropdown-toggle btn btn-primary btn-sm" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Actions
    </a>

    <ul class="dropdown-menu">
        <li>
            <!-- Export to CSV button inside the dropdown -->
            <a class="dropdown-item mb-3" href="{{ route('salary_detail.export.csv', ['checkNumber' => $checkNumber]) }}">
                <i class="bx bx-download font-22 text-primary">Export to CSV</i> 
            </a><hr>
            <a class="dropdown-item mt-3" href="{{ route('exportSalaryDetailPdf', ['checkNumber' => $checkNumber]) }}"target="blank">
                <i class="bx bx-download font-22 text-primary"> Export to PDF</i>
            </a>
        </li>
    </ul>
</div></div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table  border-primary table-bordered" style="background-color: rgba(255, 255, 255, 0.5);">

                    <thead class="">
                        <tr>
                            <th>DATE</th>
                            @foreach($deductionTypes as $type)
                                <th>{{ $type }} DEDUCTION</th>
                                <th class="text-uppercase light">{{ $type }} Balance</th> <!-- Added Balance column for each deduction -->
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $columnTotals = array_fill_keys($deductionTypes, 0); // Initialize totals array
                            $balanceTotals = array_fill_keys($deductionTypes, 0); // Initialize balance totals array
                        @endphp

                        @foreach($formattedData as $year => $months)
                            <tr class="table-light">
                                <td colspan="{{ count($deductionTypes) * 2 + 1 }}"><strong>{{ $year }}</strong></td>
                            </tr>
                            @foreach($months as $month => $deductions)
                                <tr>
                                    <td>{{ $month }}</td>
                                    @foreach($deductionTypes as $type)
                                        @php 
                                            $amount = $deductions[$type] ?? 0;
                                            $balance = $deductions[$type . '_balance'] ?? 0;
                                            $columnTotals[$type] += $amount;
                                            $balanceTotals[$type] += $balance;
                                        @endphp
                                        <td>{{ number_format($amount, 2) }}</td>
                                        <td>{{ number_format($balance, 2) }}</td> <!-- Balance for each deduction -->
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                  <tfoot>
    <tr class="">
        <th>Total</th>
        @foreach($deductionTypes as $type)
            <th>{{ number_format($columnTotals[$type], 2) }}</th>
            <th class="bg-light">N/A</th> <!-- Badala ya kujumlisha balanceAmount, weka N/A au acha tupu -->
        @endforeach
    </tr>
</tfoot>

                </table>
            </div>
        </div>
    </div>
</div>
@endsection

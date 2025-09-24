
@extends('layouts.app')

@section('content')
<style>
    #example th, #example td {
        border: 1px solid #dee2e6;
        padding: 8px;
        text-align: left;
    }
       /* Add space between the export buttons and the table */
       .dt-buttons {
        margin-bottom: 15px; /* Adjust this value as needed */
    }
</style>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Member Details</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    
                    <li class="breadcrumb-item active" aria-current="page">Amortization Schedule</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <!-- Corrected navigation using an anchor tag styled as a button -->
                <a href="{{ route('members.processedLoans') }}" class="btn btn-primary">
                    <i class="bx bx-share"></i> Back to list
                </a>
                <!-- Print button using JavaScript to call window.print() -->
                <button type="button" class="btn btn-dark" onclick="window.print();">
                    <i class="fa fa-print"></i> Print
                </button>
                <!-- Placeholder for PDF export; actual functionality requires backend logic -->
                <button type="button" class="btn btn-danger" onclick="exportPDF();">
                    <i class="fa fa-file-pdf-o"></i> Export as PDF
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="invoice overflow-auto">
                <div style="min-width: 400px">
                    <header>
                        <div class="row">
                            <div class="col">
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/uralogo.png') }}" width="60" alt="logo"/>
                                </a>
                            </div>
                            <div class="col company-details">
                                <h5 class="name">CHECK NO:{{ $member->checkNumber }}</h5>
                                {{-- <div>{{ $member->checkNumber }}</div> --}}
                                {{-- <div>{{ $member->email }}</div>
                                <div>{{ $member->phoneNumber }}</div> --}}
                            </div>
                        </div>
                    </header>
                    <main>
                        <div class="row contacts">
                            <div class="col invoice-to">
                                <div class="text-gray-light">LOAN TO:</div>
                                <h6 class="to">{{ $member->fullName }}</h6>
                                <div class="address">{{ $member->address }}</div>
                                <div class="email"><a href="mailto:{{ $member->email }}">{{ $member->email }}</a></div>
                            </div>
                            <div class="col invoice-details">
                                <h7 class="invoice-id">LOAN ID {{ $member->id }}</h7>
                                <div class="date">Date of Schedule: {{ now()->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="text-left">EMI (TSh)</th>
                                    <th class="text-right">Interest (TSh)</th>
                                    <th class="text-right">Principal (TSh)</th>
                                    <th class="text-right">Balance (TSh)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalInterest = 0;
                                    $totalPrincipal = 0;
                                @endphp
                                @foreach($amortizationSchedule as $index => $item)
                                    @php
                                        $totalInterest += $item['Interest'];
                                        $totalPrincipal += $item['Principal'];
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="text-left">{{ number_format($item['EMI'], 2) }}</td>
                                        <td class="text-right">{{ number_format($item['Interest'], 2) }}</td>
                                        <td class="text-right">{{ number_format($item['Principal'], 2) }}</td>
                                        <td class="text-right">{{ number_format($item['Balance'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">Total Interest</td>
                                    <td class="text-right">TSHS {{ number_format($totalInterest, 2) }}</td>


                                </tr>
                                <tr>
                                    <td colspan="2"></td>
                                    <td colspan="2">Total Principal</td>

                                    <td class="text-right">TSHS {{ number_format($totalPrincipal, 2) }}</td>

                                </tr>
                            </tfoot>
                        </table>
                        </div>
                        </main>
                        </div>
                        <div class="thanks">Thank you!</div>
                        <div class="notices">
                            <div>NOTICE:</div>
                            <div class="notice">Finance charges may apply to late payments.</div>
                        </div>
                    </main>
                    <footer>
                        Loan details are valid as created digitally.
                    </footer>
                </div>
            </div>
        </div>
    </div>

@endsection


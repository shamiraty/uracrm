{{-- resources/views/mortgage/result.blade.php --}}
@extends('layouts.app')

@section('content')
{{-- <div class="container">
    <h1>Loan Details</h1>
    <p>Loanable Take-Home Pay:TSHS {{ number_format($loanableTakeHome, 2) }}</p>
    <p>Principal Loanable Amount: TSHS {{ number_format($loanableAmount, 2) }}</p>
    <p>Total Loan Amount with Interest: TSHS {{ number_format($totalLoanWithInterest, 2) }}</p>
    <p>Total Interest Paid:TSHS  {{ number_format($totalInterest, 2) }}</p>
    <p>Monthly Deduction:TSHS  {{ number_format($monthlyDeduction, 2) }}</p>
    <p>Processing Fee (0.25%):TSHS  {{ number_format($processingFee, 2) }}</p>
    <p>Insurance (1%):TSHS  {{ number_format($insurance, 2) }}</p>
    <p>Disbursement Amount:TSHS  {{ number_format($disbursementAmount, 2) }}</p>
    <div class="mt-4">
        <a href="{{ url('/mortgage-form') }}" class="btn btn-primary">Back to Calculator</a>
    </div>
</div> --}}
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
            }
            .container {
                width: 100%;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                padding: 8px;
                border: 1px solid #ddd;
            }
            th {
                background-color: #f2f2f2;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card"> 
            <h6 class="card-header text-primary"> Loan Details</h6>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="loan-table" class="table table-bordered table-hover border-primary">
                        <thead class="table-primary">
                            <tr>
                                <th>Detail</th>
                                <th class="text-end">Amount (TSHS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Loanable Take-Home Pay</td>
                                <td class="text-end">{{ number_format($loanableTakeHome, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Principal Loanable Amount</td>
                                <td class="text-end">{{ number_format($loanableAmount, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Total Loan Amount with Interest</td>
                                <td class="text-end">{{ number_format($totalLoanWithInterest, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Total Interest Paid</td>
                                <td class="text-end">{{ number_format($totalInterest, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Monthly Deduction</td>
                                <td class="text-end">{{ number_format($monthlyDeduction, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Processing Fee (0.25%)</td>
                                <td class="text-end">{{ number_format($processingFee, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Insurance (1%)</td>
                                <td class="text-end">{{ number_format($insurance, 2) }}</td>
                            </tr>
                            <tr>
                                <td>Disbursement Amount</td>
                                <td class="text-end">{{ number_format($disbursementAmount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <div class="mt-4 d-flex justify-content-end">
                    <a href="{{ url('/mortgage-form') }}" class="btn btn-outline-primary btn-sm">Back to Calculator</a>
                    <button class="btn btn-outline-secondary btn-sm ms-2" onclick="printTable()">Print Table</button>
                    <button class="btn btn-outline-secondary btn-sm ms-2" onclick="generatePDF()">Download PDF</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Print table content
        function printTable() {
            var printContents = document.getElementById('loan-table').outerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        // Generate PDF from table content
        function generatePDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Convert the HTML table to PDF and add it to the document
            doc.autoTable({ html: '#loan-table' });

            // Save the generated PDF
            doc.save('loan-details.pdf');
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.24/jspdf.plugin.autotable.min.js"></script>
    
</body>
</html>

@endsection

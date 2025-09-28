@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-import me-2"></i>
                        Import Existing Loans for Top-up
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-upload text-primary me-2"></i>
                                        Upload Excel File
                                    </h5>
                                    <p class="card-text">Upload your Excel file containing existing loan data.</p>
                                    
                                    <form action="{{ route('existing-loans.import') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="file" class="form-label">Select Excel File</label>
                                            <input type="file" 
                                                   class="form-control @error('file') is-invalid @enderror" 
                                                   id="file" 
                                                   name="file" 
                                                   accept=".xlsx,.xls,.csv"
                                                   required>
                                            @error('file')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                Supported formats: Excel (.xlsx, .xls) or CSV (.csv)
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-upload me-2"></i>Import Loans
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card bg-warning bg-opacity-10 mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-database text-warning me-2"></i>
                                        Use Default File
                                    </h5>
                                    <p class="card-text">Import from the default file: <br>
                                        <strong>DED_URA SACCOS LTD.xlsx</strong>
                                    </p>
                                    
                                    <form action="{{ route('existing-loans.import-default') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-file-excel me-2"></i>Import Default File
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <h6 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Expected Excel Format
                        </h6>
                        <p class="mb-2">Your Excel file should contain the following columns:</p>
                        <ul class="mb-0">
                            <li><strong>Check Number</strong> - Employee ID/Check Number</li>
                            <li><strong>Name/Employee Name</strong> - Full name of the employee</li>
                            <li><strong>Loan Number</strong> - Existing loan reference number</li>
                            <li><strong>Principal/Amount</strong> - Original loan amount</li>
                            <li><strong>Monthly Deduction</strong> - Monthly payment amount</li>
                            <li><strong>Balance/Outstanding</strong> - Current outstanding balance</li>
                            <li><strong>Account Number</strong> - Bank account (optional)</li>
                            <li><strong>Mobile/Phone</strong> - Contact number (optional)</li>
                            <li><strong>Email</strong> - Email address (optional)</li>
                            <li><strong>Salary</strong> - Basic/Net salary (optional)</li>
                        </ul>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('existing-loans.template') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-download me-2"></i>Download Sample Template
                        </a>
                        <a href="{{ route('loan-offers.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-list me-2"></i>View All Loans
                        </a>
                    </div>

                    <div class="card mt-4 bg-light">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="fas fa-question-circle text-info me-2"></i>
                                How It Works
                            </h6>
                            <ol class="mb-0">
                                <li>Prepare your Excel file with existing loan data</li>
                                <li>Upload the file using the form above</li>
                                <li>The system will import loans and mark them as "existing"</li>
                                <li>These loans will be available for top-up when employees apply</li>
                                <li>Duplicate loans (same loan number or check number + amount) will be skipped</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    if (fileName) {
        const fileSize = (e.target.files[0].size / 1024 / 1024).toFixed(2);
        const fileInfo = document.createElement('div');
        fileInfo.className = 'form-text text-success mt-2';
        fileInfo.innerHTML = `<i class="fas fa-check-circle me-1"></i>Selected: ${fileName} (${fileSize} MB)`;
        
        // Remove any existing file info
        const existingInfo = e.target.parentElement.querySelector('.text-success');
        if (existingInfo) {
            existingInfo.remove();
        }
        
        e.target.parentElement.appendChild(fileInfo);
    }
});
</script>
@endsection
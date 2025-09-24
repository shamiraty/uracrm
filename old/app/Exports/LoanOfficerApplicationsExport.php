<?php

namespace App\Exports;

use App\Models\LoanApplication;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoanOfficerApplicationsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Fetch all LoanApplication records
        $data = LoanApplication::all([
            'user_id', 'loan_amount', 'loan_type', 'loan_category',
            'loan_duration', 'interest_rate', 'monthly_deduction',
            'total_loan_with_interest', 'total_interest', 'processing_fee',
            'insurance', 'disbursement_amount', 'status', 'branch_id'
        ]);

        // Log the number of records fetched for debugging
        Log::info("Export Data Count:", ['count' => $data->count()]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'User ID', 'Loan Amount', 'Loan Type', 'Loan Category',
            'Loan Duration', 'Interest Rate', 'Monthly Deduction',
            'Total Loan with Interest', 'Total Interest', 'Processing Fee',
            'Insurance', 'Disbursement Amount', 'Status', 'Branch ID'
        ];
    }
}

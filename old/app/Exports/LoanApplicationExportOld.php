<?php

namespace App\Exports;

use App\Models\LoanApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LoanApplicationExport implements FromCollection, WithHeadings
{
    private $startDate;
    private $endDate;

    // Accept start and end dates via the constructor
    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
{
    $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
    $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

    return LoanApplication::with('enquiry.region', 'enquiry.district', 'enquiry.branch','enquiry.command')
        ->when($start && $end, function ($query) use ($start, $end) {
            $query->whereBetween('created_at', [$start, $end]);
        })
        ->get()
        ->map(function ($loanApplication) {
            $enquiry = $loanApplication->enquiry;
            if ($enquiry) {
                return [
                    'loan_amount' => $loanApplication->loan_amount,
                    'loan_type' => $loanApplication->loan_type,
                    'loan_category' => $loanApplication->loan_category,
                    'loan_duration' => $loanApplication->loan_duration,
                    'interest_rate' => $loanApplication->interest_rate,
                    'monthly_deduction' => $loanApplication->monthly_deduction,
                    'total_loan_with_interest' => $loanApplication->total_loan_with_interest,
                    'total_interest' => $loanApplication->total_interest,
                    'processing_fee' => $loanApplication->processing_fee,
                    'insurance' => $loanApplication->insurance,
                    'disbursement_amount' => $loanApplication->disbursement_amount,
                    'status' => $loanApplication->status,
                    'date_received' => optional($enquiry)->date_received,
                    'full_name' => optional($enquiry)->full_name,
                    'force_no' => optional($enquiry)->force_no,
                    'check_number' => optional($enquiry)->check_number,
                    'account_number' => optional($enquiry)->account_number,
                    'bank_name' => optional($enquiry)->bank_name,
                    'district' => optional($enquiry->district)->name,
                    'phone' => optional($enquiry)->phone,
                    'region' => optional($enquiry->region)->name,
                    'branch' => optional($enquiry->branch)->name,
                    'command' => optional($enquiry->command)->name,
                ];
            } else {
                return [
                    // Defaults for missing enquiry
                    'loan_amount' => $loanApplication->loan_amount,
                    'loan_type' => $loanApplication->loan_type,
                    // Other default values here...
                ];
            }
        });
}

    public function headings(): array
    {
        return [
            'Loan Amount',
            'Loan Type',
            'Loan Category',
            'Loan Duration',
            'Interest Rate',
            'Monthly Deduction',
            'Total Loan with Interest',
            'Total Interest',
            'Processing Fee',
            'Insurance',
            'Disbursement Amount',
            'Status',
            'Date Received',
            'Full Name',
            'Force No',
            'Check Number',
            'Account Number',
            'Bank Name',
            'District',
            'Phone',
            'Region',
            'Branch',
            'Command',
        ];
    }
}

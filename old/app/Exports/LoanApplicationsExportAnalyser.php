<?php

namespace App\Exports;

use App\Models\LoanApplication;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoanApplicationsExportAnalyser implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    protected $frequency;
    protected $status;
    protected $branch;
    protected $command;
    public function __construct($startDate, $endDate, $frequency, $status = null, $branch = null, $command = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->frequency = $frequency;
        $this->status = $status;
        $this->branch = $branch; // Add the branch parameter
    $this->command = $command; // Add the command parameter
    }

    public function collection()
    {
        $query = LoanApplication::with('enquiry');

        // Apply date filters
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [Carbon::parse($this->startDate), Carbon::parse($this->endDate)]);
        }

        // Apply frequency filters
        $now = Carbon::now();
        switch ($this->frequency) {
            case 'weekly':
                $query->whereHas('enquiry', function ($q) use ($now) {
                    $q->whereBetween('created_at', [
                        $now->startOfWeek(),
                        $now->endOfWeek()
                    ]);
                });
                break;
            case 'monthly':
                $query->whereHas('enquiry', function ($q) use ($now) {
                    $q->whereYear('created_at', $now->year)
                      ->whereMonth('created_at', $now->month);
                });
                break;
            case 'yearly':
                $query->whereHas('enquiry', function ($q) use ($now) {
                    $q->whereYear('created_at', $now->year);
                });
                break;
            case 'quarterly_q1':
                $query->whereHas('enquiry', function ($q) use ($now) {
                    $q->whereBetween('created_at', [
                        Carbon::create($now->year, 1, 1),
                        Carbon::create($now->year, 3, 31)
                    ]);
                });
                break;
            case 'quarterly_q2':
                $query->whereHas('enquiry', function ($q) use ($now) {
                    $q->whereBetween('created_at', [
                        Carbon::create($now->year, 4, 1),
                        Carbon::create($now->year, 6, 30)
                    ]);
                });
                break;
            case 'quarterly_q3':
                $query->whereHas('enquiry', function ($q) use ($now) {
                    $q->whereBetween('created_at', [
                        Carbon::create($now->year, 7, 1),
                        Carbon::create($now->year, 9, 30)
                    ]);
                });
                break;
            case 'quarterly_q4':
                $query->whereHas('enquiry', function ($q) use ($now) {
                    $q->whereBetween('created_at', [
                        Carbon::create($now->year, 10, 1),
                        Carbon::create($now->year, 12, 31)
                    ]);
                });
                break;
            case 'half_year_1_6':
                $query->whereHas('enquiry', function ($q) {
                    $q->whereBetween('created_at', [
                        Carbon::create(now()->year, 1, 1), 
                        Carbon::create(now()->year, 6, 30)
                    ]);
                });
                break;
            case 'half_year_6_12':
                $query->whereHas('enquiry', function ($q) {
                    $q->whereBetween('created_at', [
                        Carbon::create(now()->year, 7, 1), 
                        Carbon::create(now()->year, 12, 31)
                    ]);
                });
                break;
        }

        // Apply status filter if provided
        if ($this->status) {
            $query->where('status', $this->status);
        }

       // Apply branch filter if provided
    if ($this->branch) {
        $query->whereHas('enquiry', function ($q) {
            $q->where('branch_id', $this->branch); // Assuming 'branch_id' is the foreign key in 'enquiry' table
        });
    }

    // Apply command filter if provided
    if ($this->command) {
        $query->whereHas('enquiry', function ($q) {
            $q->where('command_id', $this->command); // Assuming 'command_id' is the foreign key in 'enquiry' table
        });
    }
        
        return $query->get()->map(function ($loanApplication) {
            $enquiry = $loanApplication->enquiry;
            return [
                'Enquiry Date Received' => optional($enquiry)->date_received ?? 'No Date Received',
                'Registered Date'=>optional($loanApplication->enquiry)->created_at ?? 'N/A',               
                'Loan ID' => $loanApplication->id,
                'Loan Amount' => $loanApplication->loan_amount,
                'Loan Category' => $loanApplication->loan_category,
                'Loan Duration' => $loanApplication->loan_duration,
                'Interest Rate' => $loanApplication->interest_rate,
                'Monthly Deduction' => $loanApplication->monthly_deduction,
                'Total Loan with Interest' => $loanApplication->total_loan_with_interest,
                'Total Interest' => $loanApplication->total_interest,
                'Processing Fee' => $loanApplication->processing_fee,
                'Insurance' => $loanApplication->insurance,
                'Disbursement Amount' => $loanApplication->disbursement_amount,
                'Enquiry Full Name' => optional($enquiry)->full_name ?? 'No Enquiry',
                'Enquiry Phone' => optional($enquiry)->phone ?? 'No Phone',
                'Enquiry Bank Name' => optional($enquiry)->bank_name ?? 'No Bank Name',
                'Enquiry Status' => optional($loanApplication)->status ?? 'No Status',
                'Enquiry Branch' => optional($enquiry)->branch ? $enquiry->branch->name : 'No Branch',
                'Enquiry District' => optional($enquiry)->district ? $enquiry->district->name : 'No District',
                'Enquiry Command' => optional($enquiry)->command ? $enquiry->command->name : 'No Command',
                // Add more fields as needed
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Received Date',
            'Registered Date',
            'Loan ID',
            'Loan Amount',
            'Loan Category',
            'Loan Duration',
            'Interest Rate',
            'Monthly Deduction',
            'Total Loan with Interest',
            'Total Interest',
            'Processing Fee',
            'Insurance',
            'Disbursement Amount',
            'Enquiry Full Name',
            'Enquiry Phone',
            'Enquiry Bank Name',
            'Enquiry Status',
            'Enquiry Branch',
            'Enquiry District',
            'Enquiry Command',
            // Add other headers as needed
        ];
    }
}

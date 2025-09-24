<?php

namespace App\Exports;

use App\Models\LoanApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoanApplicationExport implements WithMultipleSheets
{
    use Exportable;
    private $startDate;
    private $endDate;
    private $status;
    private $branchId;
    private $commandId;

    public function __construct($startDate, $endDate, $status = null, $branchId = null, $commandId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->branchId = $branchId;
        $this->commandId = $commandId;
    }

    public function sheets(): array
    {
        return [
            new LoanApplicationSheet($this->startDate, $this->endDate, $this->status, $this->branchId, $this->commandId),
            new LoanApplicationSummarySheet($this->startDate, $this->endDate, $this->status, $this->branchId, $this->commandId),
            
        ];
    }
}

class LoanApplicationSheet implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;
    private $startDate;
    private $endDate;
    private $status;
    private $branchId;
    private $commandId;

    public function __construct($startDate, $endDate, $status = null, $branchId = null, $commandId = null)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->status    = $status;
        $this->branchId  = $branchId;
        $this->commandId = $commandId;
    }
    public function collection()
    {
        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $end   = $this->endDate   ? Carbon::parse($this->endDate)->endOfDay() : null;
        $currentUser = auth()->user();

        $query = LoanApplication::with('enquiry.region', 'enquiry.district', 'enquiry.branch', 'enquiry.command')
            ->when($start && $end, fn($query) => $query->whereBetween('created_at', [$start, $end]))
            ->when($this->status, fn($query) => $query->where('status', $this->status))
            ->when($this->branchId, fn($query) => $query->whereHas('enquiry', fn($q) => $q->where('branch_id', $this->branchId)))
            ->when($this->commandId, fn($query) => $query->whereHas('enquiry', fn($q) => $q->where('command_id', $this->commandId)));

        // if the current user is registrar or registrar_hq export  data he register
        if ($currentUser && ($currentUser->hasRole('registrar') || $currentUser->hasRole('registrar_hq'))) {
            $query->whereHas('enquiry', fn($q) => $q->where('registered_by', $currentUser->id));
        }
         // If user is a loan officer or accountant, export only assigned data.
        if ($currentUser && ($currentUser->hasRole('loanofficer') || $currentUser->hasRole('accountant'))) {
        $query->whereHas('enquiry.assignedUsers', fn($q) => $q->where('users.id', $currentUser->id));
        }
       // Users with full access (general manager, assistant general manager, superadmin, system admin)
       if ($currentUser && ($currentUser->hasRole('general_manager') || 
                         $currentUser->hasRole('assistant_general_manager') || 
                         $currentUser->hasRole('superadmin') || 
                         $currentUser->hasRole('system_admin'))) {
        // No filter applied, export all data.
       }
        
        return $query->get()->map(fn($loan) => [
            'loan_amount'              => $loan->loan_amount,
            'loan_type'                => $loan->loan_type,
            'loan_category'            => $loan->loan_category,
            'loan_duration'            => $loan->loan_duration,
            'interest_rate'            => $loan->interest_rate,
            'monthly_deduction'        => $loan->monthly_deduction,
            'total_loan_with_interest' => $loan->total_loan_with_interest,
            'total_interest'           => $loan->total_interest,
            'processing_fee'           => $loan->processing_fee,
            'insurance'                => $loan->insurance,
            'disbursement_amount'      => $loan->disbursement_amount,
            'status'                   => $loan->status,
            'date_received'            => optional($loan->enquiry)->date_received,
            'full_name'                => optional($loan->enquiry)->full_name,
            'force_no'                 => optional($loan->enquiry)->force_no,
            'check_number'             => optional($loan->enquiry)->check_number,
            'account_number'           => optional($loan->enquiry)->account_number,
            'bank_name'                => optional($loan->enquiry)->bank_name,
            'district'                 => optional(optional($loan->enquiry)->district)->name,
            'phone'                    => optional($loan->enquiry)->phone,
            'region'                   => optional(optional($loan->enquiry)->region)->name,
            'branch'                   => optional(optional($loan->enquiry)->branch)->name,
            'command'                  => optional(optional($loan->enquiry)->command)->name,
        ]);
    }

    public function headings(): array
    {
        return [
            'Loan Amount', 'Loan Type', 'Loan Category', 'Loan Duration', 'Interest Rate',
            'Monthly Deduction', 'Total Loan with Interest', 'Total Interest', 'Processing Fee',
            'Insurance', 'Disbursement Amount', 'Status', 'Date Received', 'Full Name', 'Force No',
            'Check Number', 'Account Number', 'Bank Name', 'District', 'Phone', 'Region',
            'Branch', 'Command',
        ];
    }

    public function title(): string
    {
        return 'Loan Applications';
    }
}


class LoanApplicationSummarySheet implements FromCollection, WithHeadings, WithTitle
{
    use Exportable;
    private $startDate;
    private $endDate;
    private $status;
    private $branchId;
    private $commandId;

    public function __construct($startDate, $endDate, $status = null, $branchId = null, $commandId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->branchId = $branchId;
        $this->commandId = $commandId;
    }

    public function collection()
    {
        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        $data = LoanApplication::select(
            'loan_type',
            DB::raw('SUM(loan_amount) as total_loan_amount'),
            DB::raw('COUNT(*) as loan_count'),
            DB::raw('SUM(interest_rate) as total_interest_rate'),
            DB::raw('SUM(monthly_deduction) as total_monthly_deduction'),
            DB::raw('SUM(total_loan_with_interest) as total_loan_with_interest'),
            DB::raw('SUM(total_interest) as total_interest'),
            DB::raw('SUM(processing_fee) as total_processing_fee'),
            DB::raw('SUM(insurance) as total_insurance'),
            DB::raw('SUM(disbursement_amount) as total_disbursement_amount')
        )
            ->when($start && $end, fn($query) => $query->whereBetween('created_at', [$start, $end]))
            ->when($this->status, fn($query) => $query->where('status', $this->status))
            ->when($this->branchId, fn($query) => $query->whereHas('enquiry', fn($q) => $q->where('branch_id', $this->branchId))) // Add this line
            ->when($this->commandId, fn($query) => $query->whereHas('enquiry', fn($q) => $q->where('command_id', $this->commandId))) // Add this line
            ->groupBy('loan_type')
            ->get();

        $totals = [
            'Grand Total',
            $data->sum('total_loan_amount'),
            $data->sum('loan_count'),
            $data->sum('total_interest_rate'),
            $data->sum('total_monthly_deduction'),
            $data->sum('total_loan_with_interest'),
            $data->sum('total_interest'),
            $data->sum('total_processing_fee'),
            $data->sum('total_insurance'),
            $data->sum('total_disbursement_amount'),
        ];

        return $data->push(collect($totals));
    }

    public function headings(): array
    {
        return [
            'Loan Type',
            'Total Loan Amount',
            'Loan Count',
            'Total Interest Rate',
            'Total Monthly Deduction',
            'Total Loan with Interest',
            'Total Interest',
            'Total Processing Fee',
            'Total Insurance',
            'Total Disbursement Amount',
        ];
    }

    public function title(): string
    {
        return 'Summary';
    }
}
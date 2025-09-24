<?php

namespace App\Exports;

use App\Models\Enquiry;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AllEnquiryExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    private $status;
    private $branchId;
    private $commandId;

    // Constructor to accept parameters for filtering
    public function __construct($startDate, $endDate, $status = null, $branchId = null, $commandId = null)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $this->endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;
        $this->status = $status;
        $this->branchId = $branchId;
        $this->commandId = $commandId;
    }

    // Fetch the data collection for export
    public function collection()
    {
        return Enquiry::with('district', 'region', 'branch', 'command', 'users')  // Relationships to be loaded
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('date_received', [$this->startDate, $this->endDate]);
            })
            ->when($this->status, function ($query) {
                $query->where('type', $this->status);
            })
            ->when($this->branchId, function ($query) {
                $query->where('branch_id', $this->branchId);
            })
            ->when($this->commandId, function ($query) {
                $query->where('command_id', $this->commandId);
            })
            ->get()
            ->map(function ($enquiry) {
                return [
                    'date_received' => $enquiry->date_received,
                    'full_name' => $enquiry->full_name,
                    'force_no' => $enquiry->force_no,
                    'check_number' => $enquiry->check_number,
                    'account_number' => $enquiry->account_number,
                    'bank_name' => $enquiry->bank_name,
                    'district' => optional($enquiry->district)->name,  // Safe navigation operator in case relation is null
                    'phone' => $enquiry->phone,
                    'region' => optional($enquiry->region)->name,
                    'branch' => optional($enquiry->branch)->name,
                    'command' => optional($enquiry->command)->name,
                    'type' => $enquiry->type,
                    'status' => $enquiry->status,
                    'basic_salary' => $enquiry->basic_salary,
                    'allowances' => $enquiry->allowances,
                    'take_home' => $enquiry->take_home,
                    'registered_by' => optional($enquiry->registeredBy)->name, // Assuming the `User` relation
                ];
            });
    }

    // Define the headings for the Excel export
    public function headings(): array
    {
        return [
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
            'Type',
            'Status',
            'Basic Salary',
            'Allowances',
            'Take Home',
            'Registered By',
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\Deduction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeductionExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    private $branchId;
    private $commandId;

    public function __construct($startDate, $endDate, $branchId = null, $commandId = null)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $this->endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;
        $this->branchId = $branchId;
        $this->commandId = $commandId;
    }

    public function collection()
    {
        $currentUser = Auth::user();

        $query = Deduction::with([
                'enquiry:id,enquirable_id,enquirable_type,date_received,full_name,force_no,check_number,account_number,bank_name,district_id,phone,region_id,branch_id,command_id,registered_by',
                'enquiry.region:id,name',
                'enquiry.district:id,name',
                'enquiry.branch:id,name',
                'enquiry.command:id,name',
                'enquiry.assignedUsers:id'
            ])
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->when($this->branchId, function ($query) {
                $query->whereHas('enquiry', fn($q) => $q->where('branch_id', $this->branchId));
            })
            ->when($this->commandId, function ($query) {
                $query->whereHas('enquiry', fn($q) => $q->where('command_id', $this->commandId));
            });

        // **Registrar & Registrar HQ → Export only registered data**
        if ($currentUser->hasRole('registrar') || $currentUser->hasRole('registrar_hq')) {
            $query->whereHas('enquiry', fn($q) => $q->where('registered_by', $currentUser->id));
        }

        // **Loan Officer & Accountant → Export only assigned data**
        if ($currentUser->hasRole('loanofficer') || $currentUser->hasRole('accountant')) {
            $query->whereHas('enquiry.assignedUsers', fn($q) => $q->where('users.id', $currentUser->id));
        }

        // **Full Access Roles: GM, AGM, Superadmin, System Admin**
        if ($currentUser->hasAnyRole(['general_manager', 'assistant_general_manager', 'superadmin', 'system_admin'])) {
            // No additional filters, they can access all data.
        }

        return $query->get()->map(function ($deduction) {
            $enquiry = $deduction->enquiry;

            $status = $deduction->from_amount < $deduction->to_amount 
                      ? 'Deduction Increase' 
                      : 'Deduction Decrease';

            return [
                'from_amount' => $deduction->from_amount,
                'to_amount' => $deduction->to_amount,
                'date_received' => optional($enquiry)->date_received,
                'full_name' => optional($enquiry)->full_name,
                'force_no' => optional($enquiry)->force_no,
                'check_number' => optional($enquiry)->check_number,
                'account_number' => optional($enquiry)->account_number,
                'bank_name' => optional($enquiry)->bank_name,
                'region' => optional($enquiry->region)->name,
                'district' => optional($enquiry->district)->name,
                'phone' => optional($enquiry)->phone,
                'branch' => optional($enquiry->branch)->name,
                'command' => optional($enquiry->command)->name,
                'status' => $status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'From Amount',
            'To Amount',
            'Date Received',
            'Full Name',
            'Force Number',
            'Check Number',
            'Account Number',
            'Bank Name',
            'Region',
            'District',
            'Phone',
            'Branch Name',
            'Command Name',
            'Status',
        ];
    }
}

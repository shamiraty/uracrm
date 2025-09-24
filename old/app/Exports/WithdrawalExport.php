<?php

namespace App\Exports;

use App\Models\Withdrawal;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class WithdrawalExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $status;
    protected $branchId;
    protected $commandId;

    public function __construct($startDate, $endDate, $status, $branchId, $commandId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
        $this->branchId = $branchId;
        $this->commandId = $commandId;
    }

    public function collection()
    {
        $currentUser = Auth::user();
        $status = $this->status;
        $branchId = $this->branchId;
        $commandId = $this->commandId;

        $query = Withdrawal::with([
                'enquiry', 
                'enquiry.region', 
                'enquiry.district', 
                'enquiry.branch',
                'enquiry.command',
            ])
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('type', $status);
            })
            ->when($branchId, function ($query) use ($branchId) {
                $query->whereHas('enquiry', function ($query) use ($branchId) {
                    $query->where('branch_id', $branchId);
                });
            })
            ->when($commandId, function ($query) use ($commandId) {
                $query->whereHas('enquiry', function ($query) use ($commandId) {
                    $query->where('command_id', $commandId);
                });
            });

        // Apply role-based filtering:
        if ($currentUser->hasRole('registrar') || $currentUser->hasRole('registrar_hq')) {
            $query->whereHas('enquiry', function ($q) use ($currentUser) {
                $q->where('registered_by', $currentUser->id);
            });
        }

        if ($currentUser->hasRole('loanofficer') || $currentUser->hasRole('accountant')) {
            $query->whereHas('enquiry.assignedUsers', function ($q) use ($currentUser) {
                $q->where('users.id', $currentUser->id);
            });
        }

        if ($currentUser->hasAnyRole(['general_manager', 'assistant_general_manager', 'superadmin', 'system_admin'])) {
            // No additional filters for full access roles.
        }

        // Fetch data and apply mapping
        return $query->get()->map(function ($withdrawal) {
            $enquiry = $withdrawal->enquiry;
            
            // Calculate days between the date_received and current date
            // Parse the date using the correct format (assuming "d/m/Y")
            $dateReceived = Carbon::createFromFormat('d/m/Y', $enquiry->date_received);
            $currentDate = Carbon::now();
            $days = $dateReceived->diffInDays($currentDate);
            $eligibilityStatus = $days >= 90 ? "Eligible" : "Not Eligible";

            return [
                'amount'        => $withdrawal->amount,
                'type'          => $withdrawal->type,
                'date_received' => optional($enquiry)->date_received,
                'full_name'     => optional($enquiry)->full_name,
                'force_no'      => optional($enquiry)->force_no,
                'check_number'  => optional($enquiry)->check_number,
                'account_number'=> optional($enquiry)->account_number,
                'bank_name'     => optional($enquiry)->bank_name,
                'district'      => optional(optional($enquiry)->district)->name,
                'phone'         => optional($enquiry)->phone,
                'region'        => optional(optional($enquiry)->region)->name,
                'branch'        => optional(optional($enquiry)->branch)->name,
                'command'       => optional(optional($enquiry)->command)->name,
                'days'          => $days,             // New days column
                'status'        => $eligibilityStatus, // Eligibility status
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Amount',
            'Type',
            'Date Received',
            'Full Name',
            'Force Number',
            'Check Number',
            'Account Number',
            'Bank Name',
            'District',
            'Phone',
            'Region',
            'Branch',
            'Command',
            'Days',   // Added Days column heading
            'Status',
        ];
    }

    public function map($withdrawal): array
    {
        return $withdrawal; // The data is already formatted in collection, so just return it
    }
}

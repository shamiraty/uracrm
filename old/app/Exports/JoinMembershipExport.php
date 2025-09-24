<?php

namespace App\Exports;

use App\Models\Membership;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JoinMembershipExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    private $status;
    private $branchId;
    private $commandId;

    public function __construct($startDate, $endDate, $status = null, $branchId = null, $commandId = null)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $this->endDate = $endDate ? Carbon::parse($endDate)->endOfDay() : null;
        $this->status = $status;
        $this->branchId = $branchId;
        $this->commandId = $commandId;
    }

    public function collection()
    {
        $currentUser = Auth::user();

        $query = Membership::with([
                'enquiry.region', 
                'enquiry.district', 
                'enquiry.branch',
                'enquiry.command',
                'enquiry.assignedUsers'
            ])
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->when($this->status, function ($query) {
                $query->where('membership_status', $this->status);
            })
            ->when($this->branchId, function ($query) {
                $query->whereHas('enquiry', function ($q) {
                    $q->where('branch_id', $this->branchId);
                });
            })
            ->when($this->commandId, function ($query) {
                $query->whereHas('enquiry', function ($q) {
                    $q->where('command_id', $this->commandId);
                });
            });

        // Apply role based filters:
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

        // For roles such as general_manager, assistant_general_manager, superadmin, system_admin, 
        // no additional filtering is required.

        return $query->get()->map(function ($membership) {
            $enquiry = $membership->enquiry;

            return [
                'membership_status' => $membership->membership_status,
                'date_received'     => optional($enquiry)->date_received,
                'full_name'         => optional($enquiry)->full_name,
                'force_no'          => optional($enquiry)->force_no,
                'check_number'      => optional($enquiry)->check_number,
                'account_number'    => optional($enquiry)->account_number,
                'bank_name'         => optional($enquiry)->bank_name,
                'district'          => optional(optional($enquiry)->district)->name,
                'phone'             => optional($enquiry)->phone,
                'region'            => optional(optional($enquiry)->region)->name,
                'branch'            => optional(optional($enquiry)->branch)->name,
                'command'           => optional(optional($enquiry)->command)->name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'membership_status',
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

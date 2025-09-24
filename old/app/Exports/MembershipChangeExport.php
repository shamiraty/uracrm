<?php

namespace App\Exports;

use App\Models\MembershipChange;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MembershipChangeExport implements FromCollection, WithHeadings
{
    protected $startDate;
    protected $endDate;
    private $status;
    private $branchId;
    private $commandId;

    public function __construct($startDate, $endDate, $status = null, $branchId = null, $commandId = null)
    {
        $this->startDate = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $this->endDate   = $endDate   ? Carbon::parse($endDate)->endOfDay()   : null;
        $this->status    = $status;
        $this->branchId  = $branchId;
        $this->commandId = $commandId;
    }

    public function collection()
    {
        $currentUser = Auth::user();

        $query = MembershipChange::with([
                // Ensure you load any extra fields you need for role based filtering.
                'enquiry:enquirable_id,enquirable_type,id,full_name,check_number,account_number,region_id,district_id,bank_name,date_received,command_id,branch_id,registered_by',
                'enquiry.region:id,name',
                'enquiry.district:id,name',
                'enquiry.command:id,name',
                'enquiry.assignedUsers:id'
            ])
            ->select('id', 'category', 'created_at')
            ->where('action', 'unjoin')
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->when($this->status, function ($query) {
                $query->where('category', $this->status);
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

         // **Full Access Roles: GM, AGM, Superadmin, System Admin**
         if ($currentUser->hasAnyRole(['general_manager', 'assistant_general_manager', 'superadmin', 'system_admin'])) {
            // No additional filters, they can access all data.
        }

        return $query->get()->map(function ($membershipChange) {
            $enquiry = $membershipChange->enquiry;

            return [
                'firstname'      => optional($enquiry)->full_name,
                'check_number'   => optional($enquiry)->check_number,
                'account_number' => optional($enquiry)->account_number,
                'region'         => optional($enquiry->region)->name,
                'district'       => optional($enquiry->district)->name,
                'bank_name'      => optional($enquiry)->bank_name,
                'date_received'  => optional($enquiry)->date_received,
                'category'       => $membershipChange->category,
                'command'        => optional($enquiry->command)->name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'First Name',
            'Check Number',
            'Account Number',
            'Region',
            'District',
            'Bank Name',
            'Date Received',
            'Category',
            'Command',
        ];
    }
}

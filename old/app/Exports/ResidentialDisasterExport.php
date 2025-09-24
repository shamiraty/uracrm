<?php

namespace App\Exports;

use App\Models\ResidentialDisaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResidentialDisasterExport implements FromCollection, WithHeadings
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

        $query = ResidentialDisaster::with([
                'enquiry',
                'enquiry.region',
                'enquiry.district',
                'enquiry.branch',
                'enquiry.command',
                'enquiry.assignedUsers' // Needed for role-based filtering.
            ])
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            })
            ->when($this->status, function ($query) {
                $query->where('disaster_type', $this->status);
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

        // For registrar or registrar_hq roles, limit results to enquiries registered by the current user.
        if ($currentUser->hasRole('registrar') || $currentUser->hasRole('registrar_hq')) {
            $query->whereHas('enquiry', function ($q) use ($currentUser) {
                $q->where('registered_by', $currentUser->id);
            });
        }

        // For loan officer or accountant roles, limit results to enquiries where the current user is assigned.
        if ($currentUser->hasRole('loanofficer') || $currentUser->hasRole('accountant')) {
            $query->whereHas('enquiry.assignedUsers', function ($q) use ($currentUser) {
                $q->where('users.id', $currentUser->id);
            });
        }

        // For roles such as general_manager, assistant_general_manager, superadmin, system_admin,
        // no additional filtering is required.

        return $query->get()->map(function ($residentialDisaster) {
            $enquiry = $residentialDisaster->enquiry;

            // Debug log
            Log::info('Residential Disaster Export', [
                'ResidentialDisaster ID' => $residentialDisaster->id,
                'Enquiry Exists'         => $enquiry ? 'Yes' : 'No',
                'Enquiry Data'           => $enquiry ? $enquiry->toArray() : 'No Enquiry Data',
            ]);

            return [
                'disaster_type' => $residentialDisaster->disaster_type,
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
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Disaster Type',
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

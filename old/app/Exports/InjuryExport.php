<?php

namespace App\Exports;

use App\Models\Injury;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InjuryExport implements FromCollection, WithHeadings
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

        $query = Injury::with([
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

        if ($currentUser->hasRole('registrar') || $currentUser->hasRole('registrar_hq')) {
            $query->whereHas('enquiry', fn($q) => $q->where('registered_by', $currentUser->id));
        }

        if ($currentUser->hasRole('loanofficer') || $currentUser->hasRole('accountant')) {
            $query->whereHas('enquiry.assignedUsers', fn($q) => $q->where('users.id', $currentUser->id));
        }

        if ($currentUser->hasAnyRole(['general_manager', 'assistant_general_manager', 'superadmin', 'system_admin'])) {
            // No additional filters for full access roles.
        }

        return $query->get()->map(function ($injury) {
            $enquiry = $injury->enquiry;

            return [
                'start_date' => $injury->startdate,
                'end_date' => $injury->enddate,
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
                'description' => optional($injury)->description,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Start Date',
            'End Date',
            'Date Received',
            'Full Name',
            'Force No',
            'Check Number',
            'Account Number',
            'Bank Name',
            'Region',
            'District',
            'Phone',
            'Branch',
            'Command',
            'Description',
        ];
    }
}

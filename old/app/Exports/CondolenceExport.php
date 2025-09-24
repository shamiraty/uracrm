<?php

namespace App\Exports;

use App\Models\Condolence;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CondolenceExport implements FromCollection, WithHeadings
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
        $currentUser = auth()->user();
        
        $query = Condolence::with([
                'enquiry:id,enquirable_id,enquirable_type,full_name,force_no,check_number,account_number,bank_name,district_id,phone,region_id,branch_id,command_id,date_received',
                'enquiry.region:id,name',
                'enquiry.district:id,name',
                'enquiry.branch:id,name',
                'enquiry.command:id,name'
            ])
            ->when($this->startDate && $this->endDate, fn($query) => $query->whereBetween('created_at', [$this->startDate, $this->endDate]))
            ->when($this->status, fn($query) => $query->where('dependent_member_type', $this->status))
            ->when($this->branchId, fn($query) => $query->whereHas('enquiry', fn($q) => $q->where('branch_id', $this->branchId)))
            ->when($this->commandId, fn($query) => $query->whereHas('enquiry', fn($q) => $q->where('command_id', $this->commandId)));

        // Apply role-based filtering
        if ($currentUser) {
            if ($currentUser->hasRole('registrar') || $currentUser->hasRole('registrar_hq')) {
                $query->whereHas('enquiry', fn($q) => $q->where('registered_by', $currentUser->id));
            }
            if ($currentUser->hasRole('loanofficer') || $currentUser->hasRole('accountant')) {
                $query->whereHas('enquiry.assignedUsers', fn($q) => $q->where('users.id', $currentUser->id));
            }
            if ($currentUser->hasRole('general_manager') || 
                $currentUser->hasRole('assistant_general_manager') || 
                $currentUser->hasRole('superadmin') || 
                $currentUser->hasRole('system_admin')) {
                // No additional filters for these roles, export all data
            }
        }

        return $query->get()->map(function ($condolence) {
            $enquiry = $condolence->enquiry;
            return [
                'dependent_member_type' => $condolence->dependent_member_type,
                'gender' => $condolence->gender,
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
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Dependent Member Type',
            'Gender',
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
        ];
    }
}

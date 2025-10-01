<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BranchManagerEnquiriesExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $enquiries;
    protected $branch;
    protected $enquiryType;

    public function __construct($enquiries, $branch, $enquiryType = null)
    {
        $this->enquiries = $enquiries;
        $this->branch = $branch;
        $this->enquiryType = $enquiryType;
    }

    public function collection()
    {
        // Load appropriate relationships based on type
        if ($this->enquiryType) {
            return $this->enquiries->load($this->getRelationshipForType($this->enquiryType));
        }
        return $this->enquiries;
    }

    private function getRelationshipForType($type)
    {
        $relationships = [
            'loan_application' => 'loanApplication',
            'refund' => 'refund',
            'withdraw_savings' => 'withdrawal',
            'withdraw_deposit' => 'withdrawal',
            'deduction_add' => 'deduction',
            'condolences' => 'condolence',
            'injured_at_work' => 'injury',
            'sick_for_30_days' => 'sickLeave',
            'benefit_from_disasters' => 'benefit',
            'retirement' => 'retirement',
            'share_enquiry' => 'share',
            'unjoin_membership' => 'membershipChange',
            'join_membership' => 'membershipChange',
            'ura_mobile' => 'uraMobile',
        ];

        return $relationships[$type] ?? null;
    }

    public function headings(): array
    {
        $baseHeadings = [
            'Branch',
            'Date Received',
            'Check Number',
            'Member Name',
            'Force Number',
            'Phone',
            'Bank Name',
            'Account Number',
            'Enquiry Type',
            'Region',
            'District',
            'Status',
            'Assigned To',
            'Registered By',
            'Basic Salary',
            'Allowances',
            'Take Home',
        ];

        // Add type-specific headings
        if ($this->enquiryType) {
            $typeHeadings = $this->getTypeSpecificHeadings($this->enquiryType);
            $baseHeadings = array_merge($baseHeadings, $typeHeadings);
        }

        $baseHeadings[] = 'Created At';
        $baseHeadings[] = 'Updated At';

        return $baseHeadings;
    }

    private function getTypeSpecificHeadings($type)
    {
        $headings = [
            'loan_application' => ['Loan Type', 'Loan Amount', 'Loan Period', 'Purpose'],
            'refund' => ['Refund Amount', 'Refund Type', 'Reason'],
            'withdraw_savings' => ['Withdrawal Amount', 'Withdrawal Type', 'Reason'],
            'withdraw_deposit' => ['Withdrawal Amount', 'Withdrawal Type', 'Reason'],
            'deduction_add' => ['From Month', 'To Month', 'Deduction Type'],
            'condolences' => ['Relationship', 'Date of Death', 'Death Certificate'],
            'injured_at_work' => ['Injury Date', 'Injury Description', 'Hospital'],
            'sick_for_30_days' => ['Start Date', 'End Date', 'Medical Certificate'],
            'benefit_from_disasters' => ['Disaster Type', 'Disaster Date', 'Damage Description'],
            'retirement' => ['Retirement Date', 'Retirement Type', 'Years of Service'],
            'share_enquiry' => ['Share Amount', 'Share Type'],
            'unjoin_membership' => ['Change Type', 'Reason', 'Category'],
            'join_membership' => ['Change Type', 'Category'],
            'ura_mobile' => ['Mobile Number', 'Network', 'Status'],
        ];

        return $headings[$type] ?? [];
    }

    public function map($enquiry): array
    {
        $baseData = [
            $this->branch->name,
            $enquiry->date_received ?? $enquiry->created_at->format('d/m/Y'),
            $enquiry->check_number,
            $enquiry->full_name,
            $enquiry->force_no,
            $enquiry->phone,
            $enquiry->bank_name,
            $enquiry->account_number,
            ucfirst(str_replace('_', ' ', $enquiry->type)),
            $enquiry->region->name ?? 'N/A',
            $enquiry->district->name ?? 'N/A',
            ucfirst($enquiry->status),
            $enquiry->users->first()->name ?? 'Not Assigned',
            $enquiry->registeredBy->name ?? 'N/A',
            $enquiry->basic_salary ?? 'N/A',
            $enquiry->allowances ?? 'N/A',
            $enquiry->take_home ?? 'N/A',
        ];

        // Add type-specific data
        if ($this->enquiryType) {
            $typeData = $this->getTypeSpecificData($enquiry, $this->enquiryType);
            $baseData = array_merge($baseData, $typeData);
        }

        $baseData[] = $enquiry->created_at->format('d/m/Y H:i');
        $baseData[] = $enquiry->updated_at->format('d/m/Y H:i');

        return $baseData;
    }

    private function getTypeSpecificData($enquiry, $type)
    {
        switch($type) {
            case 'loan_application':
                $loan = $enquiry->loanApplication;
                return [
                    $loan->loan_type ?? 'N/A',
                    $loan->loan_amount ?? 'N/A',
                    $loan->loan_period ?? 'N/A',
                    $loan->purpose ?? 'N/A',
                ];

            case 'refund':
                $refund = $enquiry->refund;
                return [
                    $refund->refund_amount ?? 'N/A',
                    $refund->refund_type ?? 'N/A',
                    $refund->reason ?? 'N/A',
                ];

            case 'withdraw_savings':
            case 'withdraw_deposit':
                $withdrawal = $enquiry->withdrawal;
                return [
                    $withdrawal->amount ?? 'N/A',
                    $withdrawal->type ?? 'N/A',
                    $withdrawal->reason ?? 'N/A',
                ];

            case 'deduction_add':
                $deduction = $enquiry->deduction;
                return [
                    $deduction->from ?? 'N/A',
                    $deduction->to ?? 'N/A',
                    $deduction->deduction_type ?? 'N/A',
                ];

            case 'condolences':
                $condolence = $enquiry->condolence;
                return [
                    $condolence->relationship ?? 'N/A',
                    $condolence->date_of_death ?? 'N/A',
                    $condolence->death_certificate ?? 'N/A',
                ];

            case 'injured_at_work':
                $injury = $enquiry->injury;
                return [
                    $injury->injury_date ?? 'N/A',
                    $injury->injury_description ?? 'N/A',
                    $injury->hospital ?? 'N/A',
                ];

            case 'sick_for_30_days':
                $sickLeave = $enquiry->sickLeave;
                return [
                    $sickLeave->start_date ?? 'N/A',
                    $sickLeave->end_date ?? 'N/A',
                    $sickLeave->medical_certificate ?? 'N/A',
                ];

            case 'benefit_from_disasters':
                $benefit = $enquiry->benefit;
                return [
                    $benefit->disaster_type ?? 'N/A',
                    $benefit->disaster_date ?? 'N/A',
                    $benefit->damage_description ?? 'N/A',
                ];

            case 'retirement':
                $retirement = $enquiry->retirement;
                return [
                    $retirement->retirement_date ?? 'N/A',
                    $retirement->retirement_type ?? 'N/A',
                    $retirement->years_of_service ?? 'N/A',
                ];

            case 'share_enquiry':
                $share = $enquiry->share;
                return [
                    $share->share_amount ?? 'N/A',
                    $share->share_type ?? 'N/A',
                ];

            case 'unjoin_membership':
            case 'join_membership':
                $membership = $enquiry->membershipChange;
                return [
                    $membership->action ?? 'N/A',
                    $membership->reason ?? 'N/A',
                    $membership->category ?? 'N/A',
                ];

            case 'ura_mobile':
                $mobile = $enquiry->uraMobile;
                return [
                    $mobile->mobile_number ?? 'N/A',
                    $mobile->network ?? 'N/A',
                    $mobile->status ?? 'N/A',
                ];

            default:
                return [];
        }
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $sheet->getStyle('A1:S1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '17479E'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        // Auto-size columns
        foreach(range('A','S') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Set row height for header
        $sheet->getRowDimension(1)->setRowHeight(25);

        // Add borders to all data cells
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A2:S' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Alternate row colors
        for ($row = 2; $row <= $lastRow; $row++) {
            if ($row % 2 == 0) {
                $sheet->getStyle('A' . $row . ':S' . $row)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA'],
                    ],
                ]);
            }
        }

        return [];
    }

    public function title(): string
    {
        return 'Branch Enquiries - ' . substr($this->branch->name, 0, 20);
    }
}
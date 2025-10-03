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
            'unjoin_membership' => 'membershipChanges',
            'join_membership' => 'membershipChanges',
            'ura_mobile' => null,
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
        switch ($type) {
            case 'loan_application':
                return ['Loan Type', 'Loan Category', 'Interest Rate (%)', 'Monthly Deduction'];
            case 'refund':
                return ['Refund Amount', 'Reason'];
            case 'withdraw_savings':
            case 'withdraw_deposit':
                return ['Withdrawal Amount', 'Reason'];
            case 'deduction_add':
                return ['From Amount', 'To Amount', 'Changes', 'Status'];
            case 'retirement':
                return ['Retirement Date', 'Years of Service'];
            case 'share_enquiry':
                return ['Share Amount'];
            case 'condolences':
                return ['Deceased Name', 'Relationship', 'Date of Death'];
            case 'injured_at_work':
                return ['Injury Description', 'Injury Date'];
            case 'sick_for_30_days':
                return ['Start Date', 'End Date', 'Days'];
            case 'benefit_from_disasters':
                return ['Disaster Type', 'Description'];
            case 'unjoin_membership':
                return ['Category', 'Reason'];
            case 'join_membership':
                return ['Membership Status', 'Category'];
            default:
                return [];
        }
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
                    $loan->loan_category ?? 'N/A',
                    $loan->interest_rate ?? 'N/A',
                    $loan->monthly_deduction ? number_format($loan->monthly_deduction) : 'N/A',
                ];

            case 'refund':
                $refund = $enquiry->refund;
                return [
                    $refund ? number_format($refund->amount) : 'N/A',
                    $refund->reason ?? 'N/A',
                ];

            case 'withdraw_savings':
            case 'withdraw_deposit':
                $withdrawal = $enquiry->withdrawal;
                return [
                    $withdrawal ? number_format($withdrawal->amount) : 'N/A',
                    $withdrawal->reason ?? 'N/A',
                ];

            case 'deduction_add':
                $deduction = $enquiry->deduction;
                return [
                    $deduction ? number_format($deduction->from_amount) : 'N/A',
                    $deduction ? number_format($deduction->to_amount) : 'N/A',
                    $deduction ? number_format($deduction->changes) : 'N/A',
                    $deduction->status ?? 'N/A',
                ];

            case 'retirement':
                $retirement = $enquiry->retirement;
                return [
                    $retirement->retirement_date ?? 'N/A',
                    $retirement->years_of_service ?? 'N/A',
                ];

            case 'share_enquiry':
                $share = $enquiry->share;
                return [
                    $share ? number_format($share->amount) : 'N/A',
                ];

            case 'condolences':
                $condolence = $enquiry->condolence;
                return [
                    $condolence->deceased_name ?? 'N/A',
                    $condolence->relationship ?? 'N/A',
                    $condolence->date_of_death ?? 'N/A',
                ];

            case 'injured_at_work':
                $injury = $enquiry->injury;
                return [
                    $injury->description ?? 'N/A',
                    $injury->injury_date ?? 'N/A',
                ];

            case 'sick_for_30_days':
                $sickLeave = $enquiry->sickLeave;
                return [
                    $sickLeave->startdate ?? 'N/A',
                    $sickLeave->enddate ?? 'N/A',
                    $sickLeave->days ?? 'N/A',
                ];

            case 'benefit_from_disasters':
                $benefit = $enquiry->benefit;
                return [
                    $benefit->disaster_type ?? 'N/A',
                    $benefit->description ?? 'N/A',
                ];

            case 'unjoin_membership':
                $membership = $enquiry->membershipChanges;
                return [
                    $membership->category ?? 'N/A',
                    $membership->reason ?? 'N/A',
                ];

            case 'join_membership':
                $membership = $enquiry->membershipChanges;
                return [
                    $membership->membership_status ?? 'N/A',
                    $membership->category ?? 'N/A',
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
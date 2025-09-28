<?php

namespace App\Services;

use App\Models\Member;
use App\Repositories\LoanOfferRepository;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class EssIntegrationService
{
    protected $loanCalculationService;
    protected $xmlResponseService;
    protected $loanOfferRepository;
    
    public function __construct(
        LoanCalculationService $loanCalculationService,
        EssXmlResponseService $xmlResponseService,
        LoanOfferRepository $loanOfferRepository
    ) {
        $this->loanCalculationService = $loanCalculationService;
        $this->xmlResponseService = $xmlResponseService;
        $this->loanOfferRepository = $loanOfferRepository;
    }
    
    /**
     * Process loan charges request from ESS
     */
    public function processLoanChargesRequest(SimpleXMLElement $xml)
    {
        $messageDetails = $xml->Data->MessageDetails;
        
        // Extract request data
        $checkNumber = (string)$messageDetails->CheckNumber;
        
        // Validate member exists
        $member = Member::where('check_number', $checkNumber)->first();
        if (!$member) {
            return $this->xmlResponseService->generateResponse(
                '9000', 
                'No membership record found for the provided CheckNumber'
            );
        }
        
        // Prepare calculation parameters
        $params = [
            'basic_salary' => (float)$messageDetails->BasicSalary,
            'net_salary' => (float)$messageDetails->NetSalary,
            'one_third_amount' => (float)$messageDetails->OneThirdAmount,
            'deductible_amount' => (float)$messageDetails->DeductibleAmount,
            'requested_amount' => (float)($messageDetails->RequestedAmount ?? 0),
            'desired_deductible_amount' => (float)($messageDetails->DesiredDeductibleAmount ?? 0),
            'tenure' => (int)($messageDetails->Tenure ?? 0),
            'retirement_date' => (int)($messageDetails->RetirementDate ?? 0)
        ];
        
        try {
            // Calculate loan charges
            $charges = $this->loanCalculationService->calculateLoanCharges($params);
            
            // Update existing loan offer if exists
            $loanOffer = $this->loanOfferRepository->findByCheckNumber($checkNumber);
            if ($loanOffer) {
                $loanOffer->net_loan_amount = $charges['net_loan_amount'];
                $loanOffer->take_home_amount = $charges['take_home_amount'];
                $loanOffer->total_amount_to_pay = $charges['total_amount_to_pay'];
                $loanOffer->save();
                
                Log::info("Updated loan offer with calculated amounts", [
                    'loan_offer_id' => $loanOffer->id,
                    'check_number' => $checkNumber,
                    'net_loan_amount' => $charges['net_loan_amount'],
                    'take_home_amount' => $charges['take_home_amount']
                ]);
            }
            
            // Prepare response data
            $responseData = [
                'DesiredDeductibleAmount' => number_format($charges['monthly_payment'], 2, '.', ''),
                'TotalInsurance' => number_format($charges['insurance'], 2, '.', ''),
                'TotalProcessingFees' => number_format($charges['processing_fee'], 2, '.', ''),
                'OtherCharges' => number_format($charges['other_charges'], 2, '.', ''),
                'TotalChargesAmount' => number_format($charges['total_charges'], 2, '.', ''),
                'NetLoanAmount' => number_format($charges['net_loan_amount'], 2, '.', ''),
                'TotalAmountToPay' => number_format($charges['total_amount_to_pay'], 2, '.', ''),
                'Tenure' => $charges['tenure'],
                'EligibleAmount' => number_format($charges['eligible_amount'], 2, '.', ''),
                'MonthlyReturnAmount' => number_format($charges['monthly_payment'], 2, '.', ''),
                'TotalInterestRateAmount' => number_format($charges['total_interest'], 2, '.', '')
            ];
            
            return $this->xmlResponseService->generateLoanChargesResponse(
                '0000',
                'Loan Charges Request processed successfully',
                $responseData
            );
            
        } catch (\Exception $e) {
            Log::error('Error calculating loan charges', [
                'error' => $e->getMessage(),
                'check_number' => $checkNumber
            ]);
            
            return $this->xmlResponseService->generateResponse(
                '8006',
                'Error calculating loan charges: ' . $e->getMessage()
            );
        }
    }
    
    /**
     * Process loan offer request from ESS
     */
    public function processLoanOfferRequest(SimpleXMLElement $xml)
    {
        $messageDetails = $xml->Data->MessageDetails;
        
        // Prepare loan offer data
        $data = $this->extractLoanOfferData($messageDetails);
        
        // Calculate amounts if not already calculated
        if ($data['requested_amount'] > 0) {
            $charges = $this->loanCalculationService->calculateLoanCharges([
                'requested_amount' => $data['requested_amount'],
                'tenure' => $data['tenure'],
                'basic_salary' => $data['basic_salary'],
                'net_salary' => $data['net_salary'],
                'one_third_amount' => $data['one_third_amount'],
                'deductible_amount' => $data['desired_deductible_amount'] ?? 0,
                'retirement_date' => $data['retirement_date']
            ]);
            
            $data['net_loan_amount'] = $charges['net_loan_amount'];
            $data['take_home_amount'] = $charges['take_home_amount'];
            $data['total_amount_to_pay'] = $charges['total_amount_to_pay'];
        }
        
        // Set default values
        $data['approval'] = 'PENDING';
        $data['status'] = 'PENDING';
        $data['loan_type'] = 'new';
        
        try {
            // Create loan offer with proper relationships
            $loanOffer = $this->loanOfferRepository->createLoanOffer($data);
            
            Log::info('Loan offer created', [
                'id' => $loanOffer->id,
                'application_number' => $loanOffer->application_number
            ]);
            
            // Send initial approval notification
            return $this->xmlResponseService->generateLoanOfferApprovalNotification(
                $loanOffer->toArray(),
                'PENDING',
                'Loan offer received and under review'
            );
            
        } catch (\Exception $e) {
            Log::error('Error creating loan offer', [
                'error' => $e->getMessage(),
                'application_number' => $data['application_number']
            ]);
            
            return $this->xmlResponseService->generateResponse(
                '8007',
                'Error processing loan offer: ' . $e->getMessage()
            );
        }
    }
    
    /**
     * Process loan final approval notification
     */
    public function processLoanFinalApproval(SimpleXMLElement $xml)
    {
        $messageDetails = $xml->Data->MessageDetails;
        
        $applicationNumber = (string)$messageDetails->ApplicationNumber;
        $approval = (string)$messageDetails->Approval;
        $reason = (string)($messageDetails->Reason ?? 'No reason provided');
        $fspReferenceNumber = (string)($messageDetails->FSPReferenceNumber ?? null);
        $loanNumber = (string)($messageDetails->LoanNumber ?? null);
        
        $success = $this->loanOfferRepository->updateApproval(
            $applicationNumber,
            'final',
            strtolower($approval) === 'approved' ? 'approved' : 'rejected',
            [
                'reason' => $reason,
                'fsp_reference_number' => $fspReferenceNumber,
                'loan_number' => $loanNumber,
                'approved_by' => auth()->id(),
                'approved_at' => now()
            ]
        );
        
        if (!$success) {
            return $this->xmlResponseService->generateResponse(
                '8011',
                'Loan application not found'
            );
        }
        
        // If approved, create disbursement record
        if (strtolower($approval) === 'approved') {
            $loanOffer = $this->loanOfferRepository->findByApplicationNumber($applicationNumber);
            $this->loanOfferRepository->createDisbursement($loanOffer->id, []);
        }
        
        return $this->xmlResponseService->generateResponse(
            '8000',
            'Loan final approval processed successfully'
        );
    }
    
    /**
     * Process top-up balance request
     */
    public function processTopUpBalanceRequest(SimpleXMLElement $xml)
    {
        $messageDetails = $xml->Data->MessageDetails;
        
        $checkNumber = (string)$messageDetails->CheckNumber;
        $loanNumber = (string)$messageDetails->LoanNumber;
        
        // Find existing loan
        $existingLoan = $this->loanOfferRepository->findByCheckNumber($checkNumber);
        
        if (!$existingLoan || $existingLoan->loan_number !== $loanNumber) {
            return $this->xmlResponseService->generateResponse(
                '8011',
                'Loan not found'
            );
        }
        
        // Calculate balance (simplified - should integrate with actual payment records)
        $balanceData = [
            'loan_number' => $loanNumber,
            'fsp_reference_number' => $existingLoan->fsp_reference_number,
            'payment_reference_number' => 'PAY' . uniqid(),
            'total_payoff_amount' => $existingLoan->outstanding_balance ?? 0,
            'outstanding_balance' => $existingLoan->outstanding_balance ?? 0,
            'final_payment_date' => now()->addMonths(1)->format('Y-m-d\TH:i:s'),
            'last_deduction_date' => $existingLoan->last_deduction_date ?? now()->format('Y-m-d\TH:i:s'),
            'last_pay_date' => $existingLoan->last_pay_date ?? now()->format('Y-m-d\TH:i:s'),
            'end_date' => $existingLoan->end_date_str ?? now()->addMonths(12)->format('Y-m-d\TH:i:s')
        ];
        
        return $this->xmlResponseService->generateTopUpBalanceResponse(
            '0000',
            'Balance retrieved successfully',
            $balanceData
        );
    }
    
    /**
     * Process top-up offer request
     */
    public function processTopUpOfferRequest(SimpleXMLElement $xml)
    {
        $messageDetails = $xml->Data->MessageDetails;
        
        // Extract loan data
        $data = $this->extractLoanOfferData($messageDetails);
        $data['loan_type'] = 'topup';
        
        // Get original loan details
        $originalLoanNumber = (string)$messageDetails->LoanNumber;
        $settlementAmount = (float)$messageDetails->SettlementAmount;
        
        // Find original loan
        $originalLoan = LoanOffer::where('loan_number', $originalLoanNumber)->first();
        
        if (!$originalLoan) {
            return $this->xmlResponseService->generateResponse(
                '8011',
                'Original loan not found'
            );
        }
        
        // Calculate top-up loan
        $topupCalculation = $this->loanCalculationService->calculateTopupLoan(
            [
                'requested_amount' => $data['requested_amount'],
                'tenure' => $data['tenure'],
                'basic_salary' => $data['basic_salary'],
                'net_salary' => $data['net_salary'],
                'one_third_amount' => $data['one_third_amount'],
                'deductible_amount' => $data['desired_deductible_amount'] ?? 0,
                'retirement_date' => $data['retirement_date']
            ],
            [
                'settlement_amount' => $settlementAmount,
                'loan_number' => $originalLoanNumber,
                'outstanding_balance' => $originalLoan->outstanding_balance ?? 0
            ]
        );
        
        // Update data with calculated values
        $data['net_loan_amount'] = $topupCalculation['net_loan_amount'];
        $data['take_home_amount'] = $topupCalculation['net_disbursement'];
        $data['total_amount_to_pay'] = $topupCalculation['total_amount_to_pay'];
        $data['settlement_amount'] = $settlementAmount;
        
        try {
            // Create new loan offer
            $newLoanOffer = $this->loanOfferRepository->createLoanOffer($data);
            
            // Create top-up relationship
            $this->loanOfferRepository->createTopupLoan(
                $newLoanOffer->id,
                $originalLoan->id,
                [
                    'loan_number' => $originalLoanNumber,
                    'settlement_amount' => $settlementAmount,
                    'total_payoff_amount' => $settlementAmount,
                    'outstanding_balance' => $originalLoan->outstanding_balance ?? 0
                ]
            );
            
            Log::info('Top-up loan offer created', [
                'new_loan_id' => $newLoanOffer->id,
                'original_loan_id' => $originalLoan->id,
                'application_number' => $newLoanOffer->application_number
            ]);
            
            return $this->xmlResponseService->generateLoanOfferApprovalNotification(
                $newLoanOffer->toArray(),
                'PENDING',
                'Top-up loan offer received and under review'
            );
            
        } catch (\Exception $e) {
            Log::error('Error creating top-up loan offer', [
                'error' => $e->getMessage(),
                'application_number' => $data['application_number']
            ]);
            
            return $this->xmlResponseService->generateResponse(
                '8007',
                'Error processing top-up loan offer: ' . $e->getMessage()
            );
        }
    }
    
    /**
     * Extract loan offer data from XML message details
     */
    private function extractLoanOfferData(SimpleXMLElement $messageDetails): array
    {
        return [
            'check_number' => (string)$messageDetails->CheckNumber,
            'first_name' => (string)$messageDetails->FirstName,
            'middle_name' => (string)$messageDetails->MiddleName,
            'last_name' => (string)$messageDetails->LastName,
            'sex' => (string)$messageDetails->Sex,
            'employment_date' => (string)$messageDetails->EmploymentDate,
            'marital_status' => (string)$messageDetails->MaritalStatus,
            'confirmation_date' => empty((string)$messageDetails->ConfirmationDate) ? null : (string)$messageDetails->ConfirmationDate,
            'bank_account_number' => (string)$messageDetails->BankAccountNumber,
            'nearest_branch_name' => (string)$messageDetails->NearestBranchName,
            'nearest_branch_code' => (string)$messageDetails->NearestBranchCode,
            'vote_code' => (string)$messageDetails->VoteCode,
            'vote_name' => (string)$messageDetails->VoteName,
            'nin' => (string)$messageDetails->NIN,
            'designation_code' => (string)$messageDetails->DesignationCode,
            'designation_name' => (string)$messageDetails->DesignationName,
            'basic_salary' => (float)$messageDetails->BasicSalary,
            'net_salary' => (float)$messageDetails->NetSalary,
            'one_third_amount' => (float)$messageDetails->OneThirdAmount,
            'total_employee_deduction' => (float)$messageDetails->TotalEmployeeDeduction,
            'retirement_date' => (int)$messageDetails->RetirementDate,
            'terms_of_employment' => (string)$messageDetails->TermsOfEmployment,
            'requested_amount' => (float)$messageDetails->RequestedAmount,
            'desired_deductible_amount' => (float)$messageDetails->DesiredDeductibleAmount,
            'tenure' => (int)$messageDetails->Tenure,
            'fsp_code' => (string)$messageDetails->FSPCode,
            'product_code' => (string)$messageDetails->ProductCode,
            'interest_rate' => (float)$messageDetails->InterestRate,
            'processing_fee' => (float)$messageDetails->ProcessingFee,
            'insurance' => (float)$messageDetails->Insurance,
            'physical_address' => (string)$messageDetails->PhysicalAddress,
            'telephone_number' => (string)$messageDetails->TelephoneNumber,
            'email_address' => (string)$messageDetails->EmailAddress,
            'mobile_number' => (string)$messageDetails->MobileNumber,
            'application_number' => (string)$messageDetails->ApplicationNumber,
            'loan_purpose' => (string)$messageDetails->LoanPurpose,
            'contract_start_date' => empty((string)$messageDetails->ContractStartDate) ? null : (string)$messageDetails->ContractStartDate,
            'contract_end_date' => empty((string)$messageDetails->ContractEndDate) ? null : (string)$messageDetails->ContractEndDate,
            'swift_code' => (string)$messageDetails->SwiftCode,
            'funding' => (string)$messageDetails->Funding
        ];
    }
}
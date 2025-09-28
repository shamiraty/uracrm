<?php

/**
 * COMPLETE URA SACCOS LOAN TOP-UP HANDLER
 * Implements Message 11 (TOP_UP_PAY_0FF_BALANCE_REQUEST) and Message 12 (LOAN_TOP_UP_BALANCE_RESPONSE)
 * Using the validated formula: Total Payoff = P + (P × R/365 × D)
 */

class URASACCOSTopUpHandler
{
    private const DEFAULT_ANNUAL_RATE = 0.12; // 12% default annual rate
    private const DEFAULT_DAYS_SINCE_PAYMENT = 7; // Default 7 days for weekly processing
    private const FSP_CODE = 'FL7456'; // URA SACCOS FSP Code

    /**
     * Main handler for Message 11: TOP_UP_PAY_0FF_BALANCE_REQUEST
     * Returns Message 12: LOAN_TOP_UP_BALANCE_RESPONSE
     */
    public function handleTopUpPayoffBalance($xml)
    {
        Log::info('=== Processing Message 11: TOP_UP_PAY_0FF_BALANCE_REQUEST ===');

        try {
            // Validate and extract message details
            $msgDetails = $xml->Data->MessageDetails;
            $header = $xml->Data->Header;

            // Log complete request for audit trail
            $this->logIncomingRequest($header, $msgDetails);

            // Extract all fields as per API specification
            $requestData = $this->extractMessage11Fields($msgDetails);

            // Validate required fields
            $validation = $this->validateMessage11Fields($requestData);
            if (!$validation['valid']) {
                return $this->generateErrorResponse($validation['code'], $validation['message']);
            }

            // Find the loan in database
            $loanOffer = $this->findLoan($requestData['loan_number']);
            if (!$loanOffer) {
                return $this->generateErrorResponse('8004', 'No existing loan found for LoanNumber: ' . $requestData['loan_number']);
            }

            // Calculate the payoff amount using the correct formula
            $payoffCalculation = $this->calculatePayoffAmount($loanOffer, $requestData);

            // Update loan record with calculated values
            $this->updateLoanRecord($loanOffer, $payoffCalculation, $requestData);

            // Generate Message 12 response
            return $this->generateMessage12Response($loanOffer, $payoffCalculation);

        } catch (\Exception $e) {
            Log::error('Error processing TOP_UP_PAY_0FF_BALANCE_REQUEST', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->generateErrorResponse('9999', 'Internal processing error');
        }
    }

    /**
     * Extract all fields from Message 11 as per API specification
     */
    private function extractMessage11Fields($msgDetails): array
    {
        return [
            'check_number' => (string)($msgDetails->CheckNumber ?? ''),
            'loan_number' => (string)($msgDetails->LoanNumber ?? ''),
            'first_name' => (string)($msgDetails->FirstName ?? ''),
            'middle_name' => (string)($msgDetails->MiddleName ?? ''),
            'last_name' => (string)($msgDetails->LastName ?? ''),
            'vote_code' => (string)($msgDetails->VoteCode ?? ''),
            'vote_name' => (string)($msgDetails->VoteName ?? ''),
            'deduction_amount' => $this->parseAmount($msgDetails->DeductionAmount ?? 0),
            'deduction_code' => (string)($msgDetails->DeductionCode ?? ''),
            'deduction_name' => (string)($msgDetails->DeductionName ?? ''),
            'deduction_balance' => $this->parseAmount($msgDetails->DeductionBalance ?? 0),
            'payment_option' => (string)($msgDetails->PaymentOption ?? 'Full payment'),
        ];
    }

    /**
     * Validate required fields for Message 11
     */
    private function validateMessage11Fields(array $data): array
    {
        // Check required fields as per API specification
        $requiredFields = [
            'check_number' => 'CheckNumber',
            'loan_number' => 'LoanNumber',
            'first_name' => 'FirstName',
            'last_name' => 'LastName',
            'vote_code' => 'VoteCode',
            'vote_name' => 'VoteName',
            'deduction_amount' => 'DeductionAmount',
            'deduction_code' => 'DeductionCode',
            'deduction_name' => 'DeductionName',
            'deduction_balance' => 'DeductionBalance',
            'payment_option' => 'PaymentOption'
        ];

        foreach ($requiredFields as $field => $displayName) {
            if (empty($data[$field]) && $data[$field] !== 0) {
                return [
                    'valid' => false,
                    'code' => '8002',
                    'message' => "Missing required field: {$displayName}"
                ];
            }
        }

        // Validate check number format (9 digits)
        if (!preg_match('/^\d{9}$/', $data['check_number'])) {
            return [
                'valid' => false,
                'code' => '8003',
                'message' => 'Invalid CheckNumber format. Must be 9 digits.'
            ];
        }

        return ['valid' => true];
    }

    /**
     * Calculate payoff amount using the correct formula
     * Total Payoff = P + (P × R/365 × D)
     */
    private function calculatePayoffAmount(LoanOffer $loanOffer, array $requestData): array
    {
        Log::info('=== Calculating Payoff Amount ===', [
            'loan_number' => $loanOffer->loan_number,
            'deduction_amount' => $requestData['deduction_amount'],
            'deduction_balance' => $requestData['deduction_balance']
        ]);

        // Get loan parameters
        $initialBalance = $requestData['deduction_amount']; // Original loan amount
        $currentBalance = $requestData['deduction_balance']; // Current outstanding

        // Get from database if not provided
        if ($initialBalance <= 0) {
            $initialBalance = (float)$loanOffer->requested_amount;
        }
        if ($currentBalance <= 0) {
            $currentBalance = (float)$loanOffer->outstanding_balance;
        }

        // Get monthly payment
        $monthlyPayment = (float)($loanOffer->desired_deductible_amount ?? $loanOffer->monthly_payment ?? 0);

        // Calculate using the validated approach
        $annualRate = $this->getLoanInterestRate($loanOffer);
        $monthlyRate = $annualRate / 12;

        // Calculate original principal (loan amount without interest)
        $originalPrincipal = $this->calculateOriginalPrincipal(
            $initialBalance,
            $monthlyPayment,
            $annualRate,
            (int)$loanOffer->tenure
        );

        // Calculate installments paid
        $installmentsPaid = $this->calculateInstallmentsPaid(
            $initialBalance,
            $currentBalance,
            $monthlyPayment
        );

        // Calculate true outstanding principal
        $outstandingPrincipal = $this->calculateOutstandingPrincipal(
            $originalPrincipal,
            $monthlyPayment,
            $installmentsPaid,
            $monthlyRate
        );

        // Get days since last payment
        $daysSincePayment = $this->getDaysSinceLastPayment($loanOffer);

        // Apply the formula: Total Payoff = P + (P × R/365 × D)
        $proRatedInterest = $outstandingPrincipal * ($annualRate / 365) * $daysSincePayment;
        $totalPayoff = $outstandingPrincipal + $proRatedInterest;

        // Round to 2 decimal places
        $totalPayoff = round($totalPayoff, 2);

        Log::info('Payoff Calculation Complete', [
            'original_principal' => $originalPrincipal,
            'installments_paid' => $installmentsPaid,
            'outstanding_principal' => $outstandingPrincipal,
            'annual_rate' => $annualRate,
            'days_since_payment' => $daysSincePayment,
            'pro_rated_interest' => round($proRatedInterest, 2),
            'total_payoff' => $totalPayoff
        ]);

        return [
            'total_payoff_amount' => $totalPayoff,
            'outstanding_balance' => $outstandingPrincipal,
            'pro_rated_interest' => round($proRatedInterest, 2),
            'days_since_payment' => $daysSincePayment,
            'fsp_reference_number' => $this->generateFSPReference(),
            'payment_reference_number' => $this->generatePaymentReference(),
            'final_payment_date' => now()->addDays(7),
            'last_deduction_date' => $this->getLastDeductionDate($loanOffer),
            'last_pay_date' => $this->calculateLastPayDate($loanOffer),
            'end_date' => $this->calculateEndDate($loanOffer)
        ];
    }

    /**
     * Calculate original principal from initial balance
     */
    private function calculateOriginalPrincipal($initialBalance, $monthlyPayment, $annualRate, $tenure): float
    {
        if ($monthlyPayment <= 0 || $tenure <= 0) {
            return $initialBalance;
        }

        $monthlyRate = $annualRate / 12;

        if ($monthlyRate == 0) {
            return $initialBalance;
        }

        // PV = PMT × [(1 - (1 + r)^-n) / r]
        $presentValue = $monthlyPayment * ((1 - pow(1 + $monthlyRate, -$tenure)) / $monthlyRate);

        return round($presentValue, 2);
    }

    /**
     * Calculate installments paid
     */
    private function calculateInstallmentsPaid($initialBalance, $currentBalance, $monthlyPayment): int
    {
        if ($monthlyPayment <= 0) {
            return 0;
        }

        $reduction = $initialBalance - $currentBalance;
        $estimatedPayments = round($reduction / $monthlyPayment);

        return max(0, $estimatedPayments);
    }

    /**
     * Calculate outstanding principal after payments
     */
    private function calculateOutstandingPrincipal($originalPrincipal, $monthlyPayment, $installmentsPaid, $monthlyRate): float
    {
        if ($installmentsPaid <= 0) {
            return $originalPrincipal;
        }

        $balance = $originalPrincipal;

        for ($i = 1; $i <= $installmentsPaid; $i++) {
            $interestForMonth = $balance * $monthlyRate;
            $principalPayment = $monthlyPayment - $interestForMonth;

            if ($principalPayment > $balance) {
                $principalPayment = $balance;
            }

            $balance -= $principalPayment;

            if ($balance <= 0) {
                return 0;
            }
        }

        return round($balance, 2);
    }

    /**
     * Generate Message 12: LOAN_TOP_UP_BALANCE_RESPONSE
     */
    private function generateMessage12Response(LoanOffer $loanOffer, array $calculation)
    {
        $xml = new \SimpleXMLElement('<Document/>');
        $dataXml = $xml->addChild('Data');

        // Header as per API specification
        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', self::FSP_CODE);
        $header->addChild('MsgId', uniqid('URA'));
        $header->addChild('MessageType', 'LOAN_TOP_UP_BALANCE_RESPONSE');

        // MessageDetails as per API specification
        $msgDetails = $dataXml->addChild('MessageDetails');
        $msgDetails->addChild('LoanNumber', $loanOffer->loan_number);
        $msgDetails->addChild('FSPReferenceNumber', $calculation['fsp_reference_number']);
        $msgDetails->addChild('PaymentReferenceNumber', $calculation['payment_reference_number']);
        $msgDetails->addChild('TotalPayoffAmount', number_format($calculation['total_payoff_amount'], 2, '.', ''));
        $msgDetails->addChild('OutstandingBalance', number_format($calculation['outstanding_balance'], 2, '.', ''));
        $msgDetails->addChild('FinalPaymentDate', $calculation['final_payment_date']->format('Y-m-d\TH:i:s'));
        $msgDetails->addChild('LastDeductionDate', $calculation['last_deduction_date']->format('Y-m-d\TH:i:s'));
        $msgDetails->addChild('LastPayDate', $calculation['last_pay_date']->format('Y-m-d\TH:i:s'));
        $msgDetails->addChild('EndDate', $calculation['end_date']);

        $xml->addChild('Signature', 'URA_SACCOS_SIGNATURE');

        Log::info('=== Generated Message 12: LOAN_TOP_UP_BALANCE_RESPONSE ===', [
            'loan_number' => $loanOffer->loan_number,
            'total_payoff' => $calculation['total_payoff_amount'],
            'outstanding' => $calculation['outstanding_balance']
        ]);

        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Update loan record with calculated values
     */
    private function updateLoanRecord(LoanOffer $loanOffer, array $calculation, array $requestData): void
    {
        $loanOffer->settlement_amount = $calculation['total_payoff_amount'];
        $loanOffer->outstanding_balance = $calculation['outstanding_balance'];
        $loanOffer->fsp_reference_number = $calculation['fsp_reference_number'];
        $loanOffer->payment_reference_number = $calculation['payment_reference_number'];
        $loanOffer->final_payment_date = $calculation['final_payment_date'];
        $loanOffer->last_deduction_date = $calculation['last_deduction_date'];
        $loanOffer->last_pay_date = $calculation['last_pay_date'];
        $loanOffer->end_date_str = $calculation['end_date'];

        // Store employee details from request
        $loanOffer->employee_check_number = $requestData['check_number'];
        $loanOffer->employee_first_name = $requestData['first_name'];
        $loanOffer->employee_middle_name = $requestData['middle_name'];
        $loanOffer->employee_last_name = $requestData['last_name'];
        $loanOffer->vote_code = $requestData['vote_code'];
        $loanOffer->vote_name = $requestData['vote_name'];

        $loanOffer->save();

        Log::info('Loan record updated', ['loan_number' => $loanOffer->loan_number]);
    }

    /**
     * Helper methods
     */

    private function parseAmount($value): float
    {
        if (is_null($value) || $value === '') {
            return 0.0;
        }
        $cleanValue = preg_replace('/[^0-9.-]/', '', (string)$value);
        return (float)$cleanValue;
    }

    private function findLoan(string $loanNumber)
    {
        return LoanOffer::where('loan_number', $loanNumber)->first();
    }

    private function getLoanInterestRate(LoanOffer $loanOffer): float
    {
        if ($loanOffer->interest_rate) {
            return (float)$loanOffer->interest_rate / 100;
        }
        return self::DEFAULT_ANNUAL_RATE;
    }

    private function getDaysSinceLastPayment(LoanOffer $loanOffer): int
    {
        if ($loanOffer->last_payment_date) {
            return max(1, Carbon::parse($loanOffer->last_payment_date)->diffInDays(now()));
        }
        return self::DEFAULT_DAYS_SINCE_PAYMENT;
    }

    private function generateFSPReference(): string
    {
        return 'FSP' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
    }

    private function generatePaymentReference(): string
    {
        return 'PAY' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
    }

    private function getLastDeductionDate(LoanOffer $loanOffer): \Carbon\Carbon
    {
        if ($loanOffer->installments_paid > 0) {
            return now()->subMonth()->endOfMonth();
        }
        return now()->endOfMonth();
    }

    private function calculateLastPayDate(LoanOffer $loanOffer): \Carbon\Carbon
    {
        $remainingMonths = (int)$loanOffer->tenure - (int)$loanOffer->installments_paid;
        if ($remainingMonths <= 0) {
            return now();
        }
        return now()->addMonths($remainingMonths)->endOfMonth();
    }

    private function calculateEndDate(LoanOffer $loanOffer): string
    {
        $remainingMonths = (int)$loanOffer->tenure - (int)$loanOffer->installments_paid;
        if ($remainingMonths <= 0) {
            return now()->format('Ymd');
        }
        return now()->addMonths($remainingMonths)->format('Ymd');
    }

    private function generateErrorResponse(string $code, string $message)
    {
        $xml = new \SimpleXMLElement('<Document/>');
        $dataXml = $xml->addChild('Data');

        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', self::FSP_CODE);
        $header->addChild('MsgId', uniqid('ERR'));
        $header->addChild('MessageType', 'ERROR_RESPONSE');

        $error = $dataXml->addChild('Error');
        $error->addChild('Code', $code);
        $error->addChild('Message', $message);

        $xml->addChild('Signature', 'URA_SACCOS_SIGNATURE');

        return response($xml->asXML(), 400)
            ->header('Content-Type', 'application/xml');
    }

    private function logIncomingRequest($header, $msgDetails): void
    {
        Log::info('Incoming Message 11 Details', [
            'sender' => (string)($header->Sender ?? ''),
            'receiver' => (string)($header->Receiver ?? ''),
            'fsp_code' => (string)($header->FSPCode ?? ''),
            'msg_id' => (string)($header->MsgId ?? ''),
            'message_type' => (string)($header->MessageType ?? ''),
            'message_details' => json_decode(json_encode($msgDetails), true)
        ]);
    }
}

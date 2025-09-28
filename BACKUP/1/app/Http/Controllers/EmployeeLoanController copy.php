<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use App\Models\LoanOffer;

class EmployeeLoanController extends Controller
{
    public function handleRequest(Request $request)
    {
        $xmlContent = $request->getContent();
        Log::info('Received XML:', ['xml' => $xmlContent]);

        if (empty($xmlContent)) {
            return $this->generateXmlResponse('8001', 'Empty XML content');
        }

        if (!$this->isValidXml($xmlContent)) {
            return $this->generateXmlResponse('8001', 'Invalid XML format');
        }

        // Extract the signature
        $signature = $this->extractSignature($xmlContent);
        if (!$signature) {
            return $this->generateXmlResponse('8009', 'Invalid Signature');
        }

        // Verify digital signature
        if (!$this->verifySignature($xmlContent, $signature)) {
            return $this->generateXmlResponse('8009', 'Invalid Signature');
        }

        return $this->parseAndDispatch($xmlContent);
    }

    private function extractSignature($xmlContent)
    {
        try {
            $xml = new SimpleXMLElement($xmlContent);
            if (isset($xml->Signature)) {
                return (string)$xml->Signature;
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error extracting signature:', ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function isValidXml($xmlContent)
    {
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlContent);
        if ($xml === false) {
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                Log::error('XML Parsing Error:', ['error' => $error->message]);
            }
            libxml_clear_errors();
            return false;
        }
        return true;
    }

 


private function verifySignature($xmlContent, $signature)
{
    $publicKeyPath = '/home/crm/esstraining.crt';

    if (!file_exists($publicKeyPath)) {
        Log::error('Public key file not found at path: ' . $publicKeyPath);
        return false;
    }

    $publicKeyContent = file_get_contents($publicKeyPath);
    $publicKey = openssl_pkey_get_public($publicKeyContent);

    if (!$publicKey) {
        Log::error('Invalid public key content');
        return false;
    }

    $signatureDecoded = base64_decode($signature, true);
    if ($signatureDecoded === false) {
        Log::error('Failed to base64 decode the signature');
        return false;
    }

    // Log the original XML for comparison
    Log::info("Original XML: " . $xmlContent);

    // Remove the signature node from the XML content
    $xmlContent = preg_replace('/<Signature>.*?<\/Signature>/s', '', $xmlContent);

    // Log the XML after removing the signature
    Log::info("XML after Signature Removal: " . $xmlContent);

    // Extract the Data element as a string for verification
    $startPos = strpos($xmlContent, '<Data>');
    $endPos = strpos($xmlContent, '</Data>') + strlen('</Data>');
    $dataElementAsString = substr($xmlContent, $startPos, $endPos - $startPos);

    // Use SHA256withRSA as specified in the documentation
    $opensslAlgorithm = OPENSSL_ALGO_SHA256;
    Log::info("Using OpenSSL algorithm: " . $opensslAlgorithm);

    // Log the data to be verified
    Log::info("Data for Signature Verification: " . $dataElementAsString);
    Log::info("Data for Signature Verification (Hex): " . bin2hex($dataElementAsString));

    // Perform the verification
    $verification = openssl_verify($dataElementAsString, $signatureDecoded, $publicKey, $opensslAlgorithm);
    openssl_free_key($publicKey);

    if ($verification === 1) {
        Log::info("Signature verified successfully");
        return true;
    } elseif ($verification === 0) {
        Log::warning("Signature verification failed");
        return false;
    } else {
        Log::error("Error during signature verification: " . openssl_error_string());
        return false;
    }
}
    // private function parseAndDispatch($xmlContent)
    // {
    //     try {
    //         $xml = new SimpleXMLElement($xmlContent);
    //         $messageType = (string)$xml->Data->Header->MessageType ?? null;

    //         if (!$messageType) {
    //             return $this->generateXmlResponse('8002', 'MessageType not specified');
    //         }

    //         return $this->handleMessageType($messageType, $xml);
    //     } catch (\Exception $e) {
    //         Log::error('Error processing XML:', ['error' => $e->getMessage()]);
    //         return $this->generateXmlResponse('8001', 'Invalid XML format or processing error');
    //     }
    // }

    // private function handleMessageType($messageType, $xml)
    // {
    //     switch ($messageType) {
    //         case 'LOAN_CHARGES_REQUEST':
    //             return $this->handleLoanChargesRequest($xml);
    //         case 'LOAN_OFFER_REQUEST':
    //             return $this->handleLoanOfferRequest($xml);
    //         case 'LOAN_FINAL_APPROVAL_NOTIFICATION':
    //             return $this->handleLoanFinalApprovalNotification($xml);
    //         case 'LOAN_CANCELLATION_NOTIFICATION':
    //             return $this->handleLoanCancellationNotification($xml);
    //         default:
    //             return $this->generateXmlResponse('8003', 'Unsupported MessageType');
    //     }
    // }
    private function parseAndDispatch($xmlContent)
{
    try {
        $xml = new SimpleXMLElement($xmlContent);

        // Normalize the message type: trim whitespace and convert to uppercase
        $messageType = strtoupper(trim((string)($xml->Data->Header->MessageType ?? null)));

        Log::info('Parsed Message Type:', ['messageType' => $messageType]);

        if (!$messageType) {
            return $this->generateXmlResponse('8002', 'MessageType not specified');
        }

        // Handle the message based on its type
        return $this->handleMessageType($messageType, $xml);
    } catch (\Exception $e) {
        Log::error('Error processing XML:', ['error' => $e->getMessage()]);
        return $this->generateXmlResponse('8001', 'Invalid XML format or processing error');
    }
}

private function handleMessageType($messageType, $xml)
{
    Log::info('Handling Message Type:', ['messageType' => $messageType]);

    switch ($messageType) {
        case 'LOAN_CHARGES_REQUEST':
            return $this->handleLoanChargesRequest($xml);
        case 'LOAN_OFFER_REQUEST':
            return $this->handleLoanOfferRequest($xml);
        case 'LOAN_FINAL_APPROVAL_NOTIFICATION':
            return $this->handleLoanFinalApprovalNotification($xml);
        case 'LOAN_CANCELLATION_NOTIFICATION':
            return $this->handleLoanCancellationNotification($xml);
        default:
            Log::error('Unsupported Message Type:', ['messageType' => $messageType]);
            return $this->generateXmlResponse('8003', 'Unsupported MessageType');
    }
}
    private function generateXmlResponse($responseCode, $description, $httpStatusCode = 200)
    {
        $xml = new SimpleXMLElement('<Document/>');

        $data = $xml->addChild('Data');
        $header = $data->addChild('Header');
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', 'FL7456');
        $header->addChild('MsgId', uniqid());
        $header->addChild('MessageType', 'RESPONSE');

        $messageDetails = $data->addChild('MessageDetails');
        $messageDetails->addChild('ResponseCode', $responseCode);
        $messageDetails->addChild('Description', $description);

        $xml->addChild('Signature', 'Signature');

        $responseContent = $xml->asXML();

        return response($responseContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }

    private function handleLoanChargesRequest($xml)
    {
        Log::info('Handling LOAN_CHARGES_REQUEST', ['data' => $xml]);

        $messageDetails = $xml->Data->MessageDetails;

        $checkNumber = (string)$messageDetails->CheckNumber ?? null;
        $basicSalary = (float)$messageDetails->BasicSalary ?? null;
        $netSalary = (float)$messageDetails->NetSalary ?? null;
        $oneThirdAmount = (float)$messageDetails->OneThirdAmount ?? null;
        $deductibleAmount = (float)$messageDetails->DeductibleAmount ?? null;
        $requestedAmount = (float)($messageDetails->RequestedAmount ?? 0);
        $desiredDeductibleAmount = (float)($messageDetails->DesiredDeductibleAmount ?? 0);
        $tenure = (int)($messageDetails->Tenure ?? 0);
        $retirementDate = (int)($messageDetails->RetirementDate ?? 0);

        $allowances = [];
        if (isset($messageDetails->Allowances->Allowance)) {
            foreach ($messageDetails->Allowances->Allowance as $allowance) {
                $allowances[] = (float)$allowance;
            }
        }

        if (!$checkNumber || !$basicSalary || !$netSalary || !$oneThirdAmount || !$deductibleAmount) {
            return $this->generateXmlResponse('8002', 'Missing mandatory fields in LOAN_CHARGES_REQUEST');
        }

        $maxTenureAllowed = min(48, $retirementDate > 0 ? $retirementDate : 48);

        // Rates
        $insuranceRate = 0.01;      // 1%
        $processingFeeRate = 0.0025; // 0.25%
        $otherChargesRate = 0.00;   // Assume no other charges
        $totalChargesRate = $insuranceRate + $processingFeeRate + $otherChargesRate; // 0.0125

        $annualInterestRate = 12; 
        $r = $annualInterestRate / 100 / 12;

        // If requestedAmount > 0, requestedAmount is net after all charges.
        // P = requestedAmount / (1 - totalChargesRate)
        // This ensures that after charges are deducted, net is requestedAmount.
        if ($requestedAmount > 0) {
            $P = $requestedAmount / (1 - $totalChargesRate);
        } else {
            $P = 0;
        }

        $M = $desiredDeductibleAmount > 0 ? $desiredDeductibleAmount : $deductibleAmount;

        // Determine scenarios
        $scenarioPN = ($P > 0 && $tenure > 0);       // P & N => M
        $scenarioPM = ($P > 0 && $tenure == 0 && $M > 0); // P & M => N
        $scenarioMN = ($P == 0 && $M > 0 && $tenure > 0); // M & N => P (requested=0 in this scenario)

        if ($tenure > 0) {
            $tenure = min($tenure, $maxTenureAllowed);
        }

        if ($scenarioPN) {
            // Given P & N => Solve M
            $N = $tenure;
            $powResult = pow(1 + $r, $N);
            if ($powResult == 1 && $r == 0) {
                return $this->generateXmlResponse('8006', 'Invalid interest rate or tenure');
            }
            $numerator = $r * $powResult;
            $denominator = $powResult - 1;
            if ($denominator == 0) {
                return $this->generateXmlResponse('8006', 'Invalid interest rate or tenure');
            }
            $M = $P * ($numerator / $denominator);

        } elseif ($scenarioPM) {
            // Given P & M => Solve N
            if ($M <= $P * $r || $r <= 0) {
                return $this->generateXmlResponse('8007', 'Invalid values, cannot solve for tenure');
            }
            $ratio = $M / ($M - $P * $r);
            if ($ratio <= 0) {
                return $this->generateXmlResponse('8008', 'Cannot solve for tenure with given values');
            }
            $N = (int) round(log($ratio) / log(1 + $r));

            if ($N > $maxTenureAllowed) {
                $N = $maxTenureAllowed;
                // P & M given, do not recalc P or M since requestedAmount given means P fixed from start.
            }

        } elseif ($scenarioMN) {
            // M & N => Solve P (requestedAmount=0 here)
            $N = $tenure;
            $powResult = pow(1 + $r, $N);
            $num = $powResult - 1;
            $den = $r * $powResult;
            if ($den == 0) {
                return $this->generateXmlResponse('8006', 'Invalid interest rate or tenure');
            }
            $P = ($M * $num) / $den;

        } else {
            // No scenario fits
            // If requestedAmount > 0 but no P & N or P & M scenario, means not enough data.
            return $this->generateXmlResponse('8002', 'Not enough data to calculate loan details');
        }

        if (!isset($N)) {
            $N = $tenure > 0 ? $tenure : 0; 
        }

        if ($N <= 0) {
            return $this->generateXmlResponse('8006', 'Calculated or provided tenure is invalid');
        }

        // If after scenario we must clamp N and recalc (only if scenario and requested=0 allows):
        if ($N > $maxTenureAllowed) {
            $N = $maxTenureAllowed;
            if ($scenarioPN) {
                // Recalc M since N changed, P from requested not changed
                $powResult = pow(1 + $r, $N);
                $numerator = $r * $powResult;
                $denominator = $powResult - 1;
                $M = $P * ($numerator / $denominator);
            } elseif ($scenarioMN && $requestedAmount == 0) {
                // Recalc P since requested=0
                $powResult = pow(1 + $r, $N);
                $num = $powResult - 1;
                $den = $r * $powResult;
                $P = ($M * $num) / $den;
            }
        }

        // Now calculate charges from final P
        $Insurance = $P * $insuranceRate;
        $ProcessingFee = $P * $processingFeeRate;
        $OtherCharges = $P * $otherChargesRate; // currently 0
        $TotalCharges = $Insurance + $ProcessingFee + $OtherCharges;

        if ($requestedAmount > 0) {
            // requestedAmount is net, we already set P so that:
            // requestedAmount = P - TotalCharges
            // No need to recalc requestedAmount, just confirm:
            // NetLoanAmount = requestedAmount
            $NetLoanAmount = $requestedAmount;
        } else {
            // requestedAmount=0 means net is P - TotalCharges
            $NetLoanAmount = $P - $TotalCharges;
        }

        $totalLoanWithInterest = $M * $N;
        $totalInterest = $totalLoanWithInterest - $P;

        $responseData = [
            'DesiredDeductibleAmount' => number_format($M, 2, '.', ''),
            'TotalInsurance' => number_format($Insurance, 2, '.', ''),
            'TotalProcessingFees' => number_format($ProcessingFee, 2, '.', ''),
            'OtherCharges' => number_format($OtherCharges, 2, '.', ''),
            'TotalChargesAmount' => number_format($TotalCharges, 2, '.', ''),
            'NetLoanAmount' => number_format($NetLoanAmount, 2, '.', ''),
            'TotalAmountToPay' => number_format($totalLoanWithInterest, 2, '.', ''),
            'Tenure' => $N,
            'EligibleAmount' => number_format($P, 2, '.', ''),
            'MonthlyReturnAmount' => number_format($M, 2, '.', ''),
            'TotalInterestRateAmount' => number_format($totalInterest, 2, '.', '')
        ];

        return $this->generateLoanChargesResponse('0000', 'Loan Charges Request processed successfully', $responseData);
    }

    private function generateLoanChargesResponse($responseCode, $description, $data, $httpStatusCode = 200)
    {
        $xml = new SimpleXMLElement('<Document/>');

        $dataXml = $xml->addChild('Data');
        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', 'FL7456');
        $header->addChild('MsgId', uniqid());
        $header->addChild('MessageType', 'LOAN_CHARGES_RESPONSE');

        $messageDetails = $dataXml->addChild('MessageDetails');
        $messageDetails->addChild('ResponseCode', $responseCode);
        $messageDetails->addChild('Description', $description);

        foreach ($data as $key => $value) {
            $messageDetails->addChild($key, $value);
        }

        $xml->addChild('Signature', 'Signature');

        $responseContent = $xml->asXML();
        return response($responseContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }

 




private function handleLoanOfferRequest($xml)
{
    Log::info('Handling LOAN_OFFER_REQUEST', ['data' => $xml]);

    $messageDetails = $xml->Data->MessageDetails;

        // Convert empty date strings to NULL
        $contractStartDate = (string)$messageDetails->ContractStartDate === '' 
        ? null 
        : (string)$messageDetails->ContractStartDate;
    $contractEndDate = (string)$messageDetails->ContractEndDate === '' 
        ? null 
        : (string)$messageDetails->ContractEndDate;

    // If you have numeric fields that can be empty, convert them to float or NULL in-line as well:
    // For example, requestedAmount can be 0.0 if it's empty or numeric
    $requestedAmount = (string)$messageDetails->RequestedAmount === '' 
        ? null 
        : (float)$messageDetails->RequestedAmount;
    $desiredDeductibleAmount = (string)$messageDetails->DesiredDeductibleAmount === '' 
        ? null 
        : (float)$messageDetails->DesiredDeductibleAmount;
    $data = [
        'check_number' => (string)$messageDetails->CheckNumber,
        'first_name' => (string)$messageDetails->FirstName,
        'middle_name' => (string)$messageDetails->MiddleName,
        'last_name' => (string)$messageDetails->LastName,
        'sex' => (string)$messageDetails->Sex,
        'employment_date' => (string)$messageDetails->EmploymentDate,
        'marital_status' => (string)$messageDetails->MaritalStatus,
        'confirmation_date' => (string)$messageDetails->ConfirmationDate,
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
        // 'contract_start_date' => (string)$messageDetails->ContractStartDate,
        // 'contract_end_date' => (string)$messageDetails->ContractEndDate,
        'contract_start_date' => $contractStartDate,
        'contract_end_date' => $contractEndDate,
        'swift_code' => (string)$messageDetails->SwiftCode,
        'funding' => (string)$messageDetails->Funding,
    ];

    // Save to the database
    $loanOffer = LoanOffer::create($data);

    return $this->generateLoanOfferResponse(
        '8000',
        'Loan Offer Request processed successfully',
        $data
    );
}


/**
 * Generate an XML response for LOAN_OFFER_REQUEST similar to LOAN_CHARGES_REQUEST.
 *
 * @param string $responseCode
 * @param string $description
 * @param array  $data         // Key-value pairs for message details
 * @param int    $httpStatusCode
 * @return \Illuminate\Http\Response
 */
private function generateLoanOfferResponse($responseCode, $description, $data, $httpStatusCode = 200)
{
    // Create the base Document element
    $xml = new SimpleXMLElement('<Document/>');

    // Add the Data element
    $dataXml = $xml->addChild('Data');

    // Build the Header element
    $header = $dataXml->addChild('Header');
    $header->addChild('Sender', 'URA SACCOS LTD LOAN');
    $header->addChild('Receiver', 'ESS_UTUMISHI');
    $header->addChild('FSPCode', 'FL7456');
    $header->addChild('MsgId', uniqid());
    $header->addChild('MessageType', 'RESPONSE');

    // Build the MessageDetails element
    $messageDetails = $dataXml->addChild('MessageDetails');
    // Basic response info
    $messageDetails->addChild('ResponseCode', $responseCode);
    $messageDetails->addChild('Description', $description);

    // Populate additional data
    foreach ($data as $key => $value) {
        $messageDetails->addChild($key, $value);
    }

    // Add a Signature element (placeholder)
    $xml->addChild('Signature', 'Signature');

    // Convert to string
    $responseContent = $xml->asXML();

    // Return as raw XML with the correct Content-Type
    return response($responseContent, $httpStatusCode)
        ->header('Content-Type', 'application/xml');
}

    private function generateLoanInitialApprovalNotification($data, $httpStatusCode = 200)
    {
        $xml = new SimpleXMLElement('<Document/>');

        $dataXml = $xml->addChild('Data');
        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', 'FL7456');
        $header->addChild('MsgId', uniqid());
        $header->addChild('MessageType', 'LOAN_INITIAL_APPROVAL_NOTIFICATION');

        $messageDetails = $dataXml->addChild('MessageDetails');
        foreach ($data as $key => $value) {
            $messageDetails->addChild($key, $value);
        }

        $xml->addChild('Signature', 'Signature');
        $responseContent = $xml->asXML();
        return response($responseContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }

    private function handleLoanFinalApprovalNotification($xml)
    {
        Log::info('Handling LOAN_FINAL_APPROVAL_NOTIFICATION', ['data' => $xml]);

        $messageDetails = $xml->Data->MessageDetails;
        $applicationNumber = (string)$messageDetails->ApplicationNumber ?? null;
        $approval = (string)$messageDetails->Approval ?? null;

        if (!$applicationNumber || !$approval) {
            return $this->generateXmlResponse('8002', 'Missing mandatory fields in LOAN_FINAL_APPROVAL_NOTIFICATION');
        }

        if (strtoupper($approval) == 'APPROVED') {
            $responseData = [
                'ApplicationNumber' => $applicationNumber,
                'FSPReferenceNumber' => (string)$messageDetails->FSPReferenceNumber ?? '',
                'LoanNumber' => (string)$messageDetails->LoanNumber ?? '',
                'TotalAmountToPay' => '2500000',
                'DisbursementDate' => date('Y-m-d\TH:i:s'),
            ];

            return $this->generateLoanDisbursementNotification($responseData);
        } else {
            $responseData = [
                'ApplicationNumber' => $applicationNumber,
                'Reason' => (string)$messageDetails->Reason ?? 'Loan not approved',
            ];

            return $this->generateLoanDisbursementFailureNotification($responseData);
        }
    }

    private function generateLoanDisbursementNotification($data, $httpStatusCode = 200)
    {
        $xml = new SimpleXMLElement('<Document/>');

        $dataXml = $xml->addChild('Data');
        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', 'FL7456');
        $header->addChild('MsgId', uniqid());
        $header->addChild('MessageType', 'LOAN_DISBURSEMENT_NOTIFICATION');

        $messageDetails = $dataXml->addChild('MessageDetails');
        foreach ($data as $key => $value) {
            $messageDetails->addChild($key, $value);
        }

        $xml->addChild('Signature', 'Signature');
        $responseContent = $xml->asXML();
        return response($responseContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }

    private function generateLoanDisbursementFailureNotification($data, $httpStatusCode = 200)
    {
        $xml = new SimpleXMLElement('<Document/>');

        $dataXml = $xml->addChild('Data');
        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', 'FL7456');
        $header->addChild('MsgId', uniqid());
        $header->addChild('MessageType', 'LOAN_DISBURSEMENT_FAILURE_NOTIFICATION');

        $messageDetails = $dataXml->addChild('MessageDetails');
        foreach ($data as $key => $value) {
            $messageDetails->addChild($key, $value);
        }

        $xml->addChild('Signature', 'Signature');
        $responseContent = $xml->asXML();
        return response($responseContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }



private function handleLoanCancellationNotification($xml)
{
    Log::info('Handling LOAN_CANCELLATION_NOTIFICATION', ['data' => $xml]);

    $messageDetails = $xml->Data->MessageDetails;
    $applicationNumber = (string)$messageDetails->ApplicationNumber ?? null;
    $reason = (string)$messageDetails->Reason ?? null;
    $fspReferenceNumber = (string)$messageDetails->FSPReferenceNumber ?? null;
    $loanNumber = (string)$messageDetails->LoanNumber ?? null; // Optional

    if (!$applicationNumber || !$reason || !$fspReferenceNumber) {
        return $this->generateXmlResponse('8002', 'Missing mandatory fields in LOAN_CANCELLATION_NOTIFICATION');
    }

    try {
        // Find the loan application (replace LoanApplication with your actual model)
        $loanApplication = LoanApplication::where('application_number', $applicationNumber)->first();

        if (!$loanApplication) {
            return $this->generateXmlResponse('8011', 'Loan application not found');
        }

        // Check if the loan is in a cancellable state
        if ($loanApplication->status === 'DISBURSED' || $loanApplication->status === 'CANCELLED') {
            return $this->generateXmlResponse('8012', 'Loan cannot be cancelled in its current state');
        }

        // Update the loan application status and other details
        $loanApplication->status = 'CANCELLED';
        $loanApplication->cancellation_reason = $reason;
        $loanApplication->fsp_reference_number = $fspReferenceNumber;
        if ($loanNumber) {
            $loanApplication->loan_number = $loanNumber;
        }
        $loanApplication->save();

        Log::info('Loan Cancellation Processed:', [
            'ApplicationNumber' => $applicationNumber,
            'Reason' => $reason,
            'FSPReferenceNumber' => $fspReferenceNumber,
            'LoanNumber' => $loanNumber,
            'Status' => 'CANCELLED'
        ]);

        // Generate a success response
        return $this->generateXmlResponse('0000', 'Loan cancellation notification received and processed');

    } catch (\Exception $e) {
        Log::error('Error during loan cancellation:', ['error' => $e->getMessage()]);
        return $this->generateXmlResponse('9000', 'An error occurred during loan cancellation');
    }
}
}

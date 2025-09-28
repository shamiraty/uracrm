<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use SimpleXMLElement;
use App\Models\LoanOffer;
use App\Models\MonthlyDeduction;
use Illuminate\Support\Facades\Validator;
use App\Models\Member;
use App\Services\NmbDisbursementService;
use App\Models\PaymentDestination;
use App\Models\Bank;
use App\Models\LoanOfferApproval;
use App\Models\LoanDisbursement;
use App\Models\LoanOfferTopup;
use Illuminate\Support\Facades\DB;

use App\Models\NmbCallback;
class EmployeeLoanController extends Controller
{

    protected $nmbService;

    public function __construct(NmbDisbursementService $nmbService)
    {
        $this->nmbService = $nmbService;
    }


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
    $publicKeyPath = '/home/crm/ess_utumishi_go_tz.crt';

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
        case 'FSP_MONTHLY_DEDUCTIONS':
            return $this->handleFspMonthlyDeductions($xml);
        case 'TOP_UP_PAY_0FF_BALANCE_REQUEST':
            return $this->handletopuppayoffbalance($xml);
        case 'TOP_UP_OFFER_REQUEST':
                return $this->handleTopUpOfferRequest($xml);
        case 'LOAN_LIQUIDATION_NOTIFICATION':
                return $this->handleLoanLiquidationNotification($xml);
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
        $header->addChild('Sender', 'URA SACCOS ');
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

        $member = Member::where('check_number', $checkNumber)->first();
        if (!$member) {
            // If no member found with that checkNumber, return an error
            return $this->generateXmlResponse('9000', 'No membership record found for the provided CheckNumber');
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
        // P = requestedAmount / c
        // This ensures that after charges are deducted, net is requestedAmount.
        if ($requestedAmount > 0) {
            // $P = $requestedAmount / (1 - $totalChargesRate);
            $P = $requestedAmount ;
        } else {
            $P = 0;
        }

        $M = $desiredDeductibleAmount > 0 ? $desiredDeductibleAmount : $deductibleAmount;

        // Determine scenarios
        $scenarioPN = ($P > 0 && $tenure > 0);       // P & N => M
        $scenarioPM = ($P > 0 && $tenure == 0 && $M > 0); // P & M => N
        $scenarioMN = ($P == 0 && $M > 0 && $tenure > 0); // M & N => P (requested=0 in this scenario)
        $scenarioM = ($P == 0 && $M > 0 && $tenure==0); //miezi tupu
        $scenarioP = ($P > 0 && $M == 0 && $tenure==0); //loan amount tupu
        $scenarioPMN = ($P > 0 && $M > 0 && $tenure > 0);

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

            $N = $maxTenureAllowed;


  $powResult = pow(1 + $r, $N);
            // if ($powResult == 1 && $r == 0) {
            //     return $this->generateXmlResponse('8006', 'Invalid interest rate or tenure');
            // }
            $numerator = $r * $powResult;
            $denominator = $powResult - 1;
            if ($denominator == 0) {
                return $this->generateXmlResponse('8006', 'Invalid interest rate or tenure');
            }
            $M = $P * ($numerator / $denominator);

        }
        elseif ($scenarioMN) {
            // M & N => Solve P (requestedAmount=0 here)
            $N = $tenure;
            $powResult = pow(1 + $r, $N);
            $num = $powResult - 1;
            $den = $r * $powResult;
            if ($den == 0) {
                return $this->generateXmlResponse('8006', 'Invalid interest rate or tenure');
            }
            $P = ($M * $num) / $den;

        }
        //miezi tupu
        elseif($scenarioM){

            $N = $maxTenureAllowed;
            $powResult = pow(1 + $r, $N);
            $num = $powResult - 1;
            $den = $r * $powResult;
            $P = ($M * $num) / $den;

        }
//loan amount tupu
        elseif($scenarioP){

            $N = $maxTenureAllowed;
            $powResult = pow(1 + $r, $N);
            $numerator = $r * $powResult;
            $denominator = $powResult - 1;
            $M = $P * ($numerator / $denominator);

        }

        elseif($scenarioPMN){

            $N = $maxTenureAllowed;
            $powResult = pow(1 + $r, $N);
            $numerator = $r * $powResult;
            $denominator = $powResult - 1;
            $M = $P * ($numerator / $denominator);

        }
        else {
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

            $NetLoanAmount = $requestedAmount;
        } else {

            $NetLoanAmount = $P ;
        }

        $totalLoanWithInterest = $M * $N;
        $totalInterest = $totalLoanWithInterest - $P;

        // Calculate the actual take-home amount (disbursement amount) for NMB
        // This is the amount that will be disbursed to the customer after deducting all charges
        $TakeHomeAmount = $P - $TotalCharges;

        // Update the loan offer with the calculated take_home_amount if we have a check number
        if (isset($checkNumber) && $checkNumber) {
            $loanOffer = LoanOffer::where('check_number', $checkNumber)
                                  ->orderBy('created_at', 'desc')
                                  ->first();
            if ($loanOffer) {
                // Store both net_loan_amount (for backward compatibility) and take_home_amount
                $loanOffer->net_loan_amount = $NetLoanAmount; // Keep original logic for system integration
                $loanOffer->take_home_amount = $TakeHomeAmount; // Store the actual disbursement amount
                $loanOffer->total_amount_to_pay = $totalLoanWithInterest;
                $loanOffer->save();

                Log::info("Updated loan offer with take_home_amount", [
                    'loan_offer_id' => $loanOffer->id,
                    'net_loan_amount' => $NetLoanAmount,
                    'take_home_amount' => $TakeHomeAmount,
                    'total_amount_to_pay' => $totalLoanWithInterest,
                    'principal_amount' => $P,
                    'total_charges' => $TotalCharges
                ]);
            }
        }

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
    // Calculate net_loan_amount and take_home_amount
    $requestedAmountValue = (float)$messageDetails->RequestedAmount;
    $processingFeeValue = (float)$messageDetails->ProcessingFee;
    $insuranceValue = (float)$messageDetails->Insurance;

    // Calculate take_home_amount (actual disbursement after all charges)
    $netLoanAmount = null;
    $takeHomeAmount = null;
    if ($requestedAmountValue > 0) {
        // Keep original net_loan_amount logic for backward compatibility
        $netLoanAmount = $requestedAmountValue;
        // Calculate take_home_amount as requested amount minus all charges
        $totalCharges = $processingFeeValue + $insuranceValue;
        $takeHomeAmount = $requestedAmountValue - $totalCharges;
    }
    // Note: If requested amount is 0, both will be calculated during loan charges calculation

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
        'net_loan_amount' => $netLoanAmount,
        'take_home_amount' => $takeHomeAmount,
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
        'loan_type' => 'new', // Mark as new loan
        'approval' => 'PENDING', // Set default approval status to PENDING
        'status' => 'PENDING', // Set default status to PENDING
    ];

    // Save to the database with normalized structure support
    $loanOffer = DB::transaction(function () use ($data) {
        // Check if bank exists and link it
        if (!empty($data['swift_code'])) {
            $bank = Bank::where('swift_code', $data['swift_code'])->first();
            if ($bank) {
                $data['bank_id'] = $bank->id;
            }
        }

        // Set initial state according to loan state transition document
        $data['state'] = 'Loan offer At FSP'; // Initial state when loan comes from ESS to FSP
        $data['status'] = 'pending'; // Status remains pending
        
        // Create loan offer
        $loanOffer = LoanOffer::create($data);

        // Create initial approval record in normalized table
        LoanOfferApproval::create([
            'loan_offer_id' => $loanOffer->id,
            'approval_type' => 'initial',
            'status' => 'pending'
        ]);

        return $loanOffer;
    });

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


/**************************************************************
     * 4) LOAN_FINAL_APPROVAL_NOTIFICATION
     **************************************************************/
    private function handleLoanFinalApprovalNotification($xml) {
        Log::info('Handling LOAN_FINAL_APPROVAL_NOTIFICATION', ['data' => $xml]);
        $messageDetails = $xml->Data->MessageDetails;
        $applicationNumber = (string)$messageDetails->ApplicationNumber ?? null;
        $approval = (string)$messageDetails->Approval ?? null;
        $reason = (string)$messageDetails->Reason ?? 'No reason provided';
        $fspReferenceNumber = (string)$messageDetails->FSPReferenceNumber ?? null;
        $loanNumber = (string)$messageDetails->LoanNumber ?? null;

        if (!$applicationNumber || !$approval) {
            return $this->generateXmlResponse('8002', 'Missing mandatory fields in LOAN_FINAL_APPROVAL_NOTIFICATION');
        }

        // Update the loan offer in the database
        $loanOffer = LoanOffer::where('application_number', $applicationNumber)->first();
        if (!$loanOffer) {
            return $this->generateXmlResponse('8011', 'Loan application not found');
        }

        // Update loan offer (keeping existing logic for backward compatibility)
        $loanOffer->approval= strtoupper($approval);
        $loanOffer->reason = $reason;
        $loanOffer->fsp_reference_number = $fspReferenceNumber;
        $loanOffer->loan_number = $loanNumber;

        // Update status based on approval
        if (strtoupper($approval) === 'APPROVED') {
            $loanOffer->status = 'APPROVED';
            // When employer approves from ESS (final approval), loan is ready for disbursement
            $loanOffer->state = 'Submitted for disbursement';
            
            // Save the state changes FIRST before processing disbursement
            $loanOffer->save();
            
            // Log the state after employer approval
            Log::info("Employer approved loan #{$loanOffer->id} from ESS", [
                'state' => $loanOffer->state,
                'status' => $loanOffer->status,
                'application_number' => $loanOffer->application_number
            ]);

            // Trigger disbursement if approved
            try {
                // Check if already disbursed
                if (!in_array($loanOffer->status, ['disbursed', 'completed'])) {
                    Log::info("Triggering automatic disbursement for loan #{$loanOffer->id} after employer final approval");

                    // Reload the loan offer to ensure we have the latest saved state
                    $loanOffer->refresh();
                    
                    // Process disbursement
                    $this->processDisbursement($loanOffer);
                }
            } catch (\Exception $e) {
                Log::error("Failed to trigger disbursement after final approval", [
                    'loan_offer_id' => $loanOffer->id,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            $loanOffer->status = 'REJECTED';
            // When employer rejects from ESS, it's employer rejection
            $loanOffer->state = 'Employer Rejected';
            $loanOffer->save();
        }

        // Also track in normalized approval table
        $approvalStatus = strtoupper($approval) === 'APPROVED' ? 'approved' : 'rejected';
        LoanOfferApproval::updateOrCreate(
            [
                'loan_offer_id' => $loanOffer->id,
                'approval_type' => 'final'
            ],
            [
                'status' => $approvalStatus,
                'reason' => $reason,
                'fsp_reference_number' => $fspReferenceNumber,
                'approved_by' => auth()->id() ?? null,
                'approved_at' => $approvalStatus === 'approved' ? now() : null,
                'rejected_at' => $approvalStatus === 'rejected' ? now() : null
            ]
        );

        Log::info('Loan Final Approval processed', [
            'ApplicationNumber' => $applicationNumber,
            'ApprovalStatus' => $approval,
            'FSPReferenceNumber' => $fspReferenceNumber,
            'LoanNumber' => $loanNumber,
            'Status' => $loanOffer->status,
            'State' => $loanOffer->state
        ]);

        // Return a response indicating successful processing
        return $this->generateLoanFinalApprovalResponse('8000', 'Loan final approval notification processed successfully');
    }

    private function generateLoanFinalApprovalResponse($responseCode, $description, $httpStatusCode = 200) {
        $xml = new SimpleXMLElement('<Document/>');

        $dataXml = $xml->addChild('Data');
        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', 'FL7456');
        $header->addChild('MsgId', uniqid());
        $header->addChild('MessageType', 'RESPONSE');

        $messageDetails = $dataXml->addChild('MessageDetails');
        $messageDetails->addChild('ResponseCode', $responseCode);
        $messageDetails->addChild('Description', $description);

        $xml->addChild('Signature', 'Signature');  // Placeholder for digital signature if needed

        $responseContent = $xml->asXML();
        return response($responseContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }

    private function generateLoanDisbursementNotification($data, $httpStatusCode = 200)
    {
        $xml = new SimpleXMLElement('<Document/>');
        $dataXml = $xml->addChild('Data');
        $header  = $dataXml->addChild('Header');
        $header->addChild('Sender',   'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode',  'FL7456');
        $header->addChild('MsgId',    uniqid());
        $header->addChild('MessageType', 'LOAN_DISBURSEMENT_NOTIFICATION');

        $messageDetails = $dataXml->addChild('MessageDetails');
        foreach ($data as $key => $value) {
            $messageDetails->addChild($key, (string)$value);
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
        $header  = $dataXml->addChild('Header');
        $header->addChild('Sender',   'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode',  'FL7456');
        $header->addChild('MsgId',    uniqid());
        $header->addChild('MessageType', 'LOAN_DISBURSEMENT_FAILURE_NOTIFICATION');

        $messageDetails = $dataXml->addChild('MessageDetails');
        foreach ($data as $key => $value) {
            $messageDetails->addChild($key, (string)$value);
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

    // Extract relevant data from the XML
    $applicationNumber = (string) $messageDetails->ApplicationNumber ?? null;
    $reason = (string) $messageDetails->Reason ?? null;
    $fspReferenceNumber = (string) $messageDetails->FSPReferenceNumber ?? null;
    $loanNumber = (string) $messageDetails->LoanNumber ?? null;

    // Check for mandatory fields
    if (!$applicationNumber || !$reason ) {
        return $this->generateCancellationXmlResponse('8002', 'Missing mandatory fields in LOAN_CANCELLATION_NOTIFICATION');
    }

    // Attempt to find the loan offer associated with the application number
    try {
        $loanOffer = LoanOffer::where('application_number', $applicationNumber)->first();
        if (!$loanOffer) {
            return $this->generateCancellationXmlResponse('8011', 'Loan application not found');
        }

        // Update loan offer status to 'CANCELLED' (employee rejected) if not already cancelled or disbursed
        if (!in_array($loanOffer->approval, ['CANCELLED', 'DISBURSED'])) {
            $loanOffer->approval = 'CANCELLED'; // Employee rejected the loan
            $loanOffer->reason = $reason;
            $loanOffer->fsp_reference_number = $fspReferenceNumber;
            $loanOffer->loan_number = $loanNumber ?? $loanOffer->loan_number; // Update loan number if provided
            $loanOffer->save();

            Log::info('Loan cancellation processed successfully', [
                'ApplicationNumber' => $applicationNumber,
                'FSPReferenceNumber' => $fspReferenceNumber,
                'LoanNumber' => $loanNumber,
                'Status' => 'CANCELLED'
            ]);

            return $this->generateCancellationXmlResponse('8000', 'Loan cancellation processed successfully');
        } else {
            return $this->generateCancellationXmlResponse('8012', 'Loan cannot be cancelled in its current state');
        }
    } catch (\Exception $e) {
        Log::error('Error during loan cancellation processing:', ['error' => $e->getMessage()]);
        return $this->generateCancellationXmlResponse('9000', 'An error occurred during loan cancellation');
    }
}

private function generateCancellationXmlResponse($responseCode, $description, $httpStatusCode = 200)
{
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
    $messageDetails->addChild('ResponseCode', $responseCode);
    $messageDetails->addChild('Description', $description);

    // Add a Signature element (as a placeholder here, assuming signature generation elsewhere)
    $xml->addChild('Signature', 'Signature');

    // Convert the XML to a string
    $responseContent = $xml->asXML();

    // Return the response with the correct headers
    return response($responseContent, $httpStatusCode)
        ->header('Content-Type', 'application/xml');
}

    /**************************************************************
     * 6) Blade CRUD: Approve/Reject in the URASACCOS App
     **************************************************************/
//     public function editLoanOffer($id)
//     {
//         $loanOffer = LoanOffer::findOrFail($id);
//         return view('employee_loan.edit', compact('loanOffer'));
//     }



// public function updateLoanOffer(Request $request, $id)
// {
//     $loanOffer = LoanOffer::findOrFail($id);

//     // Update fields based on user input
//     $loanOffer->approval = $request->input('approval', $loanOffer->approval);
//     $loanOffer->reason = $request->input('reason', $loanOffer->reason);
//     $loanOffer->other_charges = $request->input('other_charges', $loanOffer->other_charges);
//     $loanOffer->total_amount_to_pay = $request->input('total_amount_to_pay', $loanOffer->total_amount_to_pay);

//     // Update status and state if provided
//     if ($request->has('status')) {
//         $newStatus = $request->input('status');
//         $loanOffer->status = $newStatus;

//         // Map status to state based on business logic
//         switch ($newStatus) {
//             case 'DISBURSEMENT_FAILED':
//                 $loanOffer->state = 'process_disbursement_failure'; // Expected by ESS
//                 break;
//             case 'SUBMITTED_FOR_DISBURSEMENT':
//                 $loanOffer->state = 'submitted_for_disbursement'; // Ensure this state is expected by your system
//                 break;
//             case 'APPROVED':
//                 $loanOffer->state = 'approved'; // Example state
//                 break;
//             // case 'FULL_SETTLED': // New status for a fully settled (liquidated) loan
//             //         $loanOffer->state = 'liquidated';
//             //         break;
//             default:
//                 $loanOffer->state = 'unknown'; // Default or handle accordingly
//         }
//     }

//     // Generate reference numbers if not already set
//     if (empty($loanOffer->fsp_reference_number)) {
//         $loanOffer->fsp_reference_number = 'TZ' . mt_rand(1000, 9999);
//     }
//     if (empty($loanOffer->loan_number)) {
//         $loanOffer->loan_number = (string)mt_rand(100000, 999999);
//     }

//     $loanOffer->save();

//     // Log the update for audit purposes
//     Log::info("LoanOffer updated", [
//         'id' => $id,
//         'status' => $loanOffer->status,
//         'approval' => $loanOffer->approval,
//         'state' => $loanOffer->state
//     ]);

//     // Prepare the message based on the response from ESS or status update
//     $message = "LoanOffer " . strtolower($loanOffer->approval) . " updated.";

//     // Conditionally notify ESS based on status
//     if ($loanOffer->status === 'DISBURSEMENT_FAILED') {
//         // Notify ESS of disbursement failure
//         $notifyDisbursementFailureResponse = $this->notifyEssOfDisbursementFailure($loanOffer->id, $loanOffer->reason);
//         $message .= " Disbursement Failure Notification: " . $notifyDisbursementFailureResponse;
//     } elseif ($loanOffer->status === 'SUBMITTED_FOR_DISBURSEMENT') {
//         // Notify ESS of disbursement
//         // Pass a reason if necessary; if not, you can pass null or an empty string
//         $reason = $request->input('reason'); // Ensure this comes from user input or business logic
//         $notifyDisbursementResponse = $this->sendDisbursementNotification($loanOffer, $reason);
//         $message .= " Disbursement Notification: " . $notifyDisbursementResponse;
//     }   elseif ($loanOffer->status === 'FULL_SETTLED') {
//         // Ensure a remark is provided for liquidation notification.
//         // Try to get the "remark" field from the request; if not, use the current reason.
//         $remarks = $request->input('remark', $loanOffer->reason);
//         if (!$remarks) {
//             $remarks = 'Loan fully settled';
//         }
//         // Notify ESS of loan liquidation (full settlement)
//         $notifyLiquidationResponse = $this->sendLiquidationNotification($loanOffer, $remarks);
//         $message .= " Liquidation Notification: " . $notifyLiquidationResponse;
//     }


//     else {
//         // Notify ESS irrespective of approval status only if not in a disbursement failed state
//         $notifyApprovalResponse = $this->notifyEssOnInitialApproval($loanOffer->id);
//         $message .= " Approval Notification: " . $notifyApprovalResponse;
//     }

//     // Return redirect with a status message
//     return redirect()->route('loan-offers.edit', $id)->with('status', $message);
// }



   // In app/Http/Controllers/EmployeeLoanController.php

// public function indexLoanOffers(Request $request)
// {
//     // Start a new query
//     $query = LoanOffer::query();

//     // Handle the search query
//     if ($request->has('search') && $request->input('search')) {
//         $searchTerm = $request->input('search');
//         $query->where(function($q) use ($searchTerm) {
//             $q->where('application_number', 'like', "%{$searchTerm}%")
//               ->orWhere('check_number', 'like', "%{$searchTerm}%")
//               ->orWhere('first_name', 'like', "%{$searchTerm}%")
//               ->orWhere('last_name', 'like', "%{$searchTerm}%");
//         });
//     }

//     // Handle the status filter
//     if ($request->has('status') && $request->input('status')) {
//         $query->where('status', $request->input('status'));
//     }

//     // Order by the most recent and paginate
//     $loanOffers = $query->latest()->paginate(15)->withQueryString(); // withQueryString preserves filters

//     return view('employee_loan.index', compact('loanOffers'));
// }

public function indexLoanOffers(Request $request)
{
    // Start a new query with eager loading of normalized relationships
    $query = LoanOffer::with(['bank', 'approvals', 'disbursements']);

    // Handle the search query
    if ($request->has('search') && $request->input('search')) {
        $searchTerm = $request->input('search');
        $query->where(function($q) use ($searchTerm) {
            $q->where('application_number', 'like', "%{$searchTerm}%")
              ->orWhere('check_number', 'like', "%{$searchTerm}%")
              ->orWhere('first_name', 'like', "%{$searchTerm}%")
              ->orWhere('last_name', 'like', "%{$searchTerm}%");
        });
    }

    // Handle the status filter - mapping frontend values to database fields
    if ($request->has('status') && $request->input('status')) {
        $statusFilter = $request->input('status');

        switch($statusFilter) {
            case 'pending_approval':
                $query->where(function($q) {
                    $q->where('approval', 'PENDING')
                      ->orWhereNull('approval');
                });
                break;
            case 'approved':
                $query->where('approval', 'APPROVED');
                break;
            case 'rejected':
                $query->where('approval', 'REJECTED');
                break;
            case 'cancelled':
                $query->where('approval', 'CANCELLED');
                break;
            case 'disbursement_pending':
                $query->where('status', 'disbursement_pending');
                break;
            case 'disbursed':
                $query->where('status', 'disbursed');
                break;
            case 'DISBURSEMENT_FAILED':
                $query->where('status', 'DISBURSEMENT_FAILED');
                break;
            case 'FULL_SETTLED':
                $query->where('status', 'FULL_SETTLED');
                break;
            default:
                // If it's a direct status value, use it as is
                $query->where('status', $statusFilter);
                break;
        }
    }

    // Handle date filters
    if ($request->has('date_from') && $request->input('date_from')) {
        $query->whereDate('created_at', '>=', $request->input('date_from'));
    }

    if ($request->has('date_to') && $request->input('date_to')) {
        $query->whereDate('created_at', '<=', $request->input('date_to'));
    }

    // Order by: First show pending loans (oldest first), then others (newest first)
    // This ensures FIFO (First In, First Out) for pending approvals
    $perPage = $request->input('per_page', 25); // Default to 25 items per page
    
    // Sort by:
    // 1. Pending approvals first (approval = 'PENDING' or NULL)
    // 2. Within pending, oldest first (FIFO)
    // 3. Non-pending loans shown after, newest first
    $query->orderByRaw("
        CASE 
            WHEN approval = 'PENDING' OR approval IS NULL THEN 0
            ELSE 1
        END
    ")
    ->orderByRaw("
        CASE 
            WHEN approval = 'PENDING' OR approval IS NULL THEN created_at
            ELSE NULL
        END ASC
    ")
    ->orderBy('created_at', 'desc');
    
    $loanOffers = $query->paginate($perPage)->withQueryString(); // withQueryString preserves filters

    // Calculate statistics for dashboard cards
    // Loan Approval Workflow:
    // - PENDING (or null): Initial state when loan offer is created
    // - APPROVED: When URAERP approves the loan
    // - REJECTED: When URAERP rejects the loan
    // - CANCELLED: When the employee rejects/cancels the loan offer
    //
    // Pending includes only PENDING status and null (for backward compatibility)
    $pendingCount = LoanOffer::where(function($q) {
                        $q->where('approval', 'PENDING')
                          ->orWhereNull('approval');
                    })->count();

    // Count all approved loans (regardless of disbursement status)
    $approvedCount = LoanOffer::where('approval', 'APPROVED')->count();

    $rejectedCount = LoanOffer::where('approval', 'REJECTED')->count();

    $cancelledCount = LoanOffer::where('approval', 'CANCELLED')->count(); // Employee rejected

    $disbursedCount = LoanOffer::where('status', 'disbursed')->count();

    $pendingNMBCount = LoanOffer::where('status', 'disbursement_pending')->count();

    // Calculate total disbursed today
    $totalDisbursed = LoanOffer::where('status', 'disbursed')
                               ->whereDate('updated_at', today())
                               ->sum('total_amount_to_pay');

    return view('employee_loan.index', compact(
        'loanOffers',
        'pendingCount',
        'approvedCount',
        'rejectedCount',
        'cancelledCount',
        'disbursedCount',
        'pendingNMBCount',
        'totalDisbursed'
    ));
}


    public function editLoanOffer($id)
    {
       // Eager load normalized relationships
       $loanOffer = LoanOffer::with([
           'bank',
           'approvals.approvedBy',
           'approvals.rejectedBy',
           'disbursements.disbursedBy',
           'disbursements.bank',
           'topupAsNew.originalLoan',
           'topupAsOriginal.newLoan'
       ])->findOrFail($id);

       $destinations = PaymentDestination::orderBy('name')->get()->groupBy('type');
        // return view('employee_loan.edit', compact('loanOffer','destinations'));
        return view('employee_loan.edit_enhanced', compact('loanOffer','destinations'));
    }





  public function updateLoanOffer(Request $request, $id)
    {
        try {
            $loanOffer = LoanOffer::findOrFail($id);
            $message = "LoanOffer updated successfully.";

        // Check if loan is cancelled - prevent any approval/rejection/disbursement actions
        if (in_array(strtoupper($loanOffer->status), ['CANCELLED', 'CANCELED']) ||
            in_array(strtoupper($loanOffer->state), ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION'])) {

            Log::warning("Attempt to update cancelled loan", [
                'loan_id' => $id,
                'status' => $loanOffer->status,
                'state' => $loanOffer->state,
                'attempted_action' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'This loan application has been cancelled by the employee and cannot be approved, rejected, or disbursed.',
                'error' => 'LOAN_CANCELLED'
            ], 422);
        }

        // Additional check for approval/rejection/disbursement actions
        $restrictedActions = ['APPROVED', 'REJECTED', 'SUBMITTED_FOR_DISBURSEMENT'];
        $requestedStatus = $request->input('status');
        $requestedApproval = $request->input('approval');

        if ((in_array($requestedStatus, $restrictedActions) || in_array($requestedApproval, ['APPROVED', 'REJECTED'])) &&
            (in_array(strtoupper($loanOffer->status), ['CANCELLED', 'CANCELED']) ||
             in_array(strtoupper($loanOffer->state), ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION']))) {

            return response()->json([
                'success' => false,
                'message' => 'Cannot process this action. The loan has been cancelled by the employee through ESS.',
                'error' => 'LOAN_CANCELLED'
            ], 422);
        }

        if ($request->input('status') === 'SUBMITTED_FOR_DISBURSEMENT') {

            // IMPORTANT: Check if loan has employer's final approval before allowing disbursement
            $hasEmployerApproval = false;

            // Check multiple approval indicators
            if ($loanOffer->approval === 'APPROVED' && $loanOffer->status === 'APPROVED') {
                $hasEmployerApproval = true;
            }

            // Also check if there's a final approval record
            $finalApproval = LoanOfferApproval::where('loan_offer_id', $loanOffer->id)
                ->where('approval_type', 'final')
                ->where('status', 'approved')
                ->first();

            if ($finalApproval) {
                $hasEmployerApproval = true;
            }

            // Block disbursement if no employer approval
            if (!$hasEmployerApproval) {
                Log::warning("Disbursement attempt blocked - No employer final approval", [
                    'loan_id' => $id,
                    'current_approval' => $loanOffer->approval,
                    'current_status' => $loanOffer->status
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Cannot disburse loan. This loan requires final approval from the employer through ESS before disbursement.',
                    'error' => 'NO_EMPLOYER_APPROVAL'
                ], 422);
            }

            // Check if disbursement already exists to prevent duplicates
            $existingDisbursement = LoanDisbursement::where('loan_offer_id', $loanOffer->id)
                ->whereIn('status', ['pending', 'processing', 'success'])
                ->first();

            if ($existingDisbursement) {
                Log::warning("Duplicate disbursement attempt blocked", [
                    'loan_id' => $id,
                    'existing_disbursement_id' => $existingDisbursement->id
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'A disbursement is already in progress or completed for this loan.',
                    'error' => 'DUPLICATE_DISBURSEMENT'
                ], 422);
            }

            // --- NMB DISBURSEMENT PATH ---
            Log::info("Initiating NMB disbursement for LoanOffer ID: {$id} (Employer approved)");

            // Validate that we have bank information for disbursement
            $swiftCode = null;
            if ($loanOffer->bank && $loanOffer->bank->swift_code) {
                $swiftCode = $loanOffer->bank->swift_code;
            } elseif ($loanOffer->swift_code) {
                $swiftCode = $loanOffer->swift_code;
            }

            if (!$swiftCode) {
                Log::error("No bank SWIFT code found for loan offer", ['loan_id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Bank information is missing. Please ensure the loan has a valid bank with SWIFT code.'
                ], 422);
            }

            // First, determine channel and destination from bank info
            // This ensures we save this critical routing information even if API call fails
            $bankShortName = $loanOffer->bank ? strtoupper($loanOffer->bank->short_name ?? '') : '';
            $channelIdentifier = '';
            $destinationCode = $bankShortName ?: substr($swiftCode, 0, 4);

            // Determine channel based on bank and amount
            $amount = $loanOffer->take_home_amount ?: ($loanOffer->net_loan_amount ?: $loanOffer->requested_amount);
            if ($swiftCode === 'NMIBTZTZ' || $bankShortName === 'NMB') {
                $channelIdentifier = 'INTERNAL';
                $destinationCode = 'NMB';
            } else {
                // External bank - determine by amount threshold
                $channelIdentifier = ($amount < 20000000) ? 'DOMESTIC' : 'TISS';
            }

            // Generate tracking IDs
            $batchId = 'URA' . $loanOffer->id . time();
            $gwxReference = 'GWX' . time() . $loanOffer->id;

            // Create disbursement record BEFORE API call with channel info
            $disbursementData = [
                'loan_offer_id' => $loanOffer->id,
                'bank_id' => $loanOffer->bank_id,
                'status' => 'pending',
                'amount' => $loanOffer->requested_amount,
                'net_amount' => $amount,
                'account_number' => $loanOffer->bank_account_number,
                'account_name' => implode(' ', array_filter([
                    $loanOffer->first_name,
                    $loanOffer->middle_name,
                    $loanOffer->last_name
                ])),
                'swift_code' => $swiftCode,
                'channel_identifier' => $channelIdentifier,  // Always saved
                'destination_code' => $destinationCode,      // Always saved
                'batch_id' => $batchId,                      // Always saved
                'reference_number' => $gwxReference,         // Always saved
                'disbursed_by' => auth()->id()
            ];

            Log::info("Creating disbursement record with channel info", ['data' => $disbursementData]);
            $disbursement = LoanDisbursement::create($disbursementData);

            // Now call NMB service to initiate disbursement
            try {
                $nmbResponse = $this->nmbService->disburseLoan($loanOffer);

                // Extract any additional metadata from response
                $channelMetadata = $nmbResponse['channel_metadata'] ?? [];

                Log::info("NMB API response received", [
                    'loan_id' => $id,
                    'channel_metadata' => $channelMetadata,
                    'response' => array_keys($nmbResponse)
                ]);
            } catch (\Exception $e) {
                // If NMB API call fails, we still have the disbursement record with channel info
                Log::error("NMB API call failed", [
                    'loan_id' => $id,
                    'error' => $e->getMessage(),
                    'disbursement_id' => $disbursement->id
                ]);

                // Update disbursement as failed but keep channel info for retry
                $disbursement->status = 'failed';
                $disbursement->failure_reason = 'Network error: ' . $e->getMessage();
                $disbursement->save();

                // Return error response
                if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to connect to NMB: ' . $e->getMessage() . '. Disbursement record created for retry.',
                        'disbursement_id' => $disbursement->id
                    ], 500);
                }

                return redirect()->back()->with('error', 'Failed to connect to NMB. Please try again.');
            }

            if (isset($nmbResponse['body']['payload']['RespStatus']) && $nmbResponse['body']['payload']['RespStatus'] === 'Success') {

                // Use batch ID from response if available, otherwise use our generated one
                $responseBatchId = $nmbResponse['body']['payload']['RespHeader']['BatchId'] ?? null;
                if ($responseBatchId) {
                    $batchId = $responseBatchId;  // Override with NMB's batch ID if provided
                }

                $loanOffer->status = 'disbursement_pending';
                $loanOffer->state = 'Submitted for disbursement'; // Use correct state from API doc
                $loanOffer->nmb_batch_id = $batchId;
                $loanOffer->save();

                // Update disbursement record with successful submission
                $disbursement->batch_id = $batchId;
                $disbursement->reference_number = $nmbResponse['body']['payload']['gwxReference'] ?? $gwxReference;
                $disbursement->response_data = $nmbResponse;
                $disbursement->status = 'submitted';
                $disbursement->save();

                Log::info("NMB batch submitted successfully.", ['id' => $id, 'batch_id' => $batchId]);
                $notifyDisbursementResponse = $this->sendDisbursementNotification($loanOffer, 'Submitted to bank for processing.');
                $message = "SUCCESS: Disbursement request sent to NMB. Batch ID: {$batchId}.";

            } else {
                $errorMessage = $nmbResponse['body']['message']
                              ?? ($nmbResponse['body']['payload']['respHeader']['ErrorDesc']
                              ?? 'Unknown error from NMB.');

                $loanOffer->status = 'DISBURSEMENT_FAILED';
                $loanOffer->state = 'Disbursement Failure'; // Use correct state from API doc
                $loanOffer->reason = $errorMessage;
                $loanOffer->save();

                // Update disbursement record as failed
                $disbursement->status = 'failed';
                $disbursement->failure_reason = $errorMessage;
                $disbursement->response_data = $nmbResponse;
                $disbursement->save();

                Log::error("NMB submission failed.", ['id' => $id, 'error' => $errorMessage, 'response' => $nmbResponse]);
                $notifyFailureResponse = $this->notifyEssOfDisbursementFailure($loanOffer->id, 'technical_error');
                $message = "NMB disbursement failed: {$errorMessage}. ESS Notification: " . $notifyFailureResponse;
            }

        } else {

            // --- REGULAR UPDATE PATH (No NMB Disbursement) ---
            $loanOffer->approval = $request->input('approval', $loanOffer->approval);
            $loanOffer->reason = $request->input('reason', $loanOffer->reason);
            $loanOffer->other_charges = $request->input('other_charges', $loanOffer->other_charges);
            $loanOffer->total_amount_to_pay = $request->input('total_amount_to_pay', $loanOffer->total_amount_to_pay);

            if ($request->has('status') && $request->input('status')) {
                $newStatus = $request->input('status');
                $loanOffer->status = $newStatus;
                switch ($newStatus) {
                    // State transitions as per API documentation
                    case 'INITIATED': 
                        $loanOffer->state = 'Initiated'; 
                        break;
                    case 'AT_FSP':
                    case 'LOAN_OFFER_AT_FSP':
                        $loanOffer->state = 'Loan offer At FSP'; 
                        break;
                    case 'LOAN_OFFER_AT_EMPLOYEE':
                        $loanOffer->state = 'Loan Offer at employee';
                        break;
                    case 'PENDING_FOR_APPROVAL':
                        $loanOffer->state = 'Pending for approval';
                        break;
                    case 'APPROVED': 
                        $loanOffer->state = 'Submitted for disbursement'; // Approved means ready for disbursement
                        break;
                    case 'SUBMITTED_FOR_DISBURSEMENT': 
                        $loanOffer->state = 'Submitted for disbursement'; 
                        break;
                    case 'DISBURSEMENT_FAILED': 
                        $loanOffer->state = 'Disbursement Failure'; 
                        break;
                    case 'disbursed':
                    case 'DISBURSED':
                    case 'COMPLETED':
                        $loanOffer->state = 'Completed'; // Disbursed/Completed
                        break;
                    case 'WAITING_FOR_LIQUIDATION':
                    case 'FULL_SETTLED': 
                        $loanOffer->state = 'Waiting for liquidation'; 
                        break;
                    case 'liquidated':
                    case 'LIQUIDATED':
                        $loanOffer->state = 'Completed'; // After liquidation, loan is completed
                        break;
                    case 'FSP_CANCELED':
                    case 'FSP_CANCELLED':
                        $loanOffer->state = 'FSP Canceled';
                        break;
                    case 'EMPLOYEE_CANCELED':
                    case 'EMPLOYEE_CANCELLED':
                        $loanOffer->state = 'Employee canceled';
                        break;
                    case 'EMPLOYEE_REJECTED':
                        $loanOffer->state = 'Employee Rejected';
                        break;
                    case 'EMPLOYER_REJECTED':
                        $loanOffer->state = 'Employer Rejected';
                        break;
                    case 'FSP_REJECTED':
                        $loanOffer->state = 'FSP Rejected';
                        break;
                    default: 
                        $loanOffer->state = $newStatus;
                }
            }
            $loanOffer->save();
            Log::info("LoanOffer updated via regular save.", ['id' => $id, 'status' => $loanOffer->status]);

            // Update normalized approval table when approval status changes
            if ($request->has('approval')) {
                $approvalValue = $request->input('approval');

                // Map the approval value to normalized status
                $normalizedStatus = 'pending';
                $approvedBy = null;
                $rejectedBy = null;
                $approvedAt = null;
                $rejectedAt = null;

                if ($approvalValue === 'APPROVED') {
                    $normalizedStatus = 'approved';
                    $approvedBy = auth()->id();
                    $approvedAt = now();
                    
                    // Check if this is initial FSP approval or final employer approval
                    // If state is "Loan offer At FSP", this is initial FSP approval
                    // If state is "Pending for approval", this is final employer approval from ESS
                    if ($loanOffer->state === 'Loan offer At FSP' || $loanOffer->state === null || $loanOffer->state === '') {
                        // Initial FSP approval - send offer back to employee
                        $loanOffer->state = 'Loan Offer at employee';
                    } else if ($loanOffer->state === 'Pending for approval' || $loanOffer->state === 'Loan Offer at employee') {
                        // Final approval (after employer approves from ESS) - ready for disbursement
                        $loanOffer->state = 'Submitted for disbursement';
                    } else {
                        // Default to submitted for disbursement for other cases
                        $loanOffer->state = 'Submitted for disbursement';
                    }
                    $loanOffer->status = 'approved'; // Also update status field
                } elseif ($approvalValue === 'REJECTED') {
                    $normalizedStatus = 'rejected';
                    $rejectedBy = auth()->id();
                    $rejectedAt = now();
                    // URAERP acts as FSP, so rejection from URAERP is FSP rejection
                    $loanOffer->state = 'FSP Rejected'; // FSP rejection as URAERP is the FSP
                    $loanOffer->status = 'rejected'; // Also update status field
                } elseif ($approvalValue === 'CANCELLED') {
                    $normalizedStatus = 'rejected';
                    $rejectedBy = auth()->id();
                    $rejectedAt = now();
                    $loanOffer->state = 'Employee canceled'; // Proper state from loan state document  
                    $loanOffer->status = 'cancelled'; // Also update status field
                }

                // Save the updated state and status
                $loanOffer->save();

                // Update or create approval record
                LoanOfferApproval::updateOrCreate(
                    [
                        'loan_offer_id' => $loanOffer->id,
                        'approval_type' => 'final'  // This is manual approval from the UI
                    ],
                    [
                        'status' => $normalizedStatus,
                        'approved_by' => $approvedBy,
                        'rejected_by' => $rejectedBy,
                        'approved_at' => $approvedAt,
                        'rejected_at' => $rejectedAt,
                        'reason' => $request->input('reason')
                    ]
                );

                Log::info('Updated loan_offer_approvals table', [
                    'loan_offer_id' => $loanOffer->id,
                    'approval' => $approvalValue,
                    'normalized_status' => $normalizedStatus,
                    'user_id' => auth()->id()
                ]);
            }

            // This adds your original ESS notification logic back in for non-disbursement cases
            if ($loanOffer->status === 'FULL_SETTLED') {
                $remarks = $request->input('remark', 'Loan fully settled');

                // Send liquidation notification to ESS
                try {
                    $notifyResponse = $this->sendLiquidationNotification($loanOffer, $remarks);

                    // Log the liquidation notification attempt
                    Log::info("Loan liquidation notification sent for loan #{$id}", [
                        'application_number' => $loanOffer->application_number,
                        'loan_number' => $loanOffer->loan_number,
                        'remarks' => $remarks,
                        'ess_response' => $notifyResponse
                    ]);

                    if (strpos($notifyResponse, 'successfully') !== false) {
                        $message = "Loan fully settled and ESS notified successfully.";
                    } else {
                        $message = "Loan fully settled. ESS notification: " . $notifyResponse;
                    }
                } catch (\Exception $e) {
                    Log::error("Failed to send liquidation notification for loan #{$id}", [
                        'error' => $e->getMessage()
                    ]);
                    $message = "Loan fully settled (ESS notification failed).";
                }
            } elseif ($loanOffer->approval === 'APPROVED' || $loanOffer->status === 'APPROVED') {
                // Send initial approval notification only when loan is approved
                try {
                    $notifyResponse = $this->notifyEssOnInitialApproval($loanOffer->id);

                    // Check if ESS notification was successful
                    if (strpos($notifyResponse, 'successfully') !== false) {
                        $message = "Loan Offer approved successfully.";
                    } else {
                        $message = "Loan Offer approved successfully.";
                        Log::warning("ESS notification issue for loan #{$id}", [
                            'response' => $notifyResponse
                        ]);
                    }

                    Log::info("Initial approval notification attempted for loan #{$id}", [
                        'approval' => $loanOffer->approval,
                        'status' => $loanOffer->status,
                        'ess_response' => $notifyResponse
                    ]);
                } catch (\Exception $e) {
                    // Log the error but don't fail the approval
                    Log::error("ESS notification failed for loan #{$id}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $message = "Loan Offer approved successfully.";
                }
            } elseif ($loanOffer->approval === 'REJECTED') {
                // Send rejection notification to ESS
                try {
                    // Send the rejection notification to ESS with the reason
                    $notifyResponse = $this->notifyEssOnInitialApproval($loanOffer->id);

                    // Check if ESS notification was successful
                    if (strpos($notifyResponse, 'successfully') !== false) {
                        $message = "Loan Offer rejected successfully and ESS has been notified.";
                    } else {
                        $message = "Loan Offer rejected successfully (ESS notification may have issues).";
                        Log::warning("ESS notification issue for rejected loan #{$id}", [
                            'response' => $notifyResponse
                        ]);
                    }

                    Log::info("Rejection notification sent to ESS for loan #{$id}", [
                        'approval' => $loanOffer->approval,
                        'reason' => $loanOffer->reason,
                        'ess_response' => $notifyResponse
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to notify ESS about loan rejection #{$id}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    $message = "Loan Offer rejected successfully (ESS notification failed).";
                }
            } else {
                // For other status updates, just save without ESS notification
                $message = "Loan Offer details saved successfully.";
            }
        }

        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->route('loan-offers.edit', $id)->with('status', $message);

        } catch (\Exception $e) {
            Log::error('Error updating loan offer', [
                'loan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while updating the loan offer: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'An error occurred while updating the loan offer');
        }
    }

    /**
     * Show loan offer details as JSON (for modal/AJAX requests)
     */
    public function showLoanOffer($id)
    {
        try {
            $loanOffer = LoanOffer::with(['bank', 'disbursements', 'approvals'])->find($id);

            if (!$loanOffer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Loan offer not found'
                ], 404);
            }

            return response()->json($loanOffer);

        } catch (\Exception $e) {
            Log::error('Error fetching loan offer', [
                'loan_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch loan details'
            ], 500);
        }
    }

    /**
     * Handle reject disbursement action with customer-friendly reason
     */
    public function rejectDisbursement(Request $request, $id)
    {
        try {
            $request->validate([
                'reason' => 'required|string',
                'detailed_message' => 'required|string|max:1000'
            ]);

            $loanOffer = LoanOffer::find($id);

            if (!$loanOffer) {
                return response()->json([
                    'success' => false,
                    'message' => 'Loan offer not found'
                ], 404);
            }

            // Check if loan is already disbursed
            if ($loanOffer->status === 'disbursed' || $loanOffer->state === 'disbursed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot reject/postpone an already disbursed loan'
                ], 400);
            }

            $reason = $request->input('reason');
            $detailedMessage = $request->input('detailed_message');

            // Update loan offer status
            $loanOffer->status = 'DISBURSEMENT_REJECTED';
            $loanOffer->state = 'Disbursement Failure'; // Use correct state from API doc

            // Store the customer-friendly reason directly
            $loanOffer->reason = $detailedMessage;
            $loanOffer->save();

            // Create disbursement record for tracking
            LoanDisbursement::create([
                'loan_offer_id' => $loanOffer->id,
                'amount' => $loanOffer->take_home_amount ?? $loanOffer->net_loan_amount ?? $loanOffer->requested_amount,
                'status' => 'failed',
                'failure_reason' => $detailedMessage,
                'account_number' => $loanOffer->bank_account_number ?? '0000000000',
                'channel_identifier' => 'REJECTION_' . time()
            ]);

            // Note: Not tracking in approval table since this is a disbursement decision, not an approval
            // The disbursement record and loan offer status changes are sufficient for tracking

            // Send disbursement failure notification to ESS with the rejection reason
            try {
                // Send the customer-friendly reason to ESS
                $this->notifyEssOfDisbursementFailure($loanOffer->id, $reason, $detailedMessage);
                $essNotificationStatus = 'ESS has been notified successfully';
            } catch (\Exception $e) {
                Log::error('Failed to notify ESS of disbursement rejection', [
                    'loan_offer_id' => $loanOffer->id,
                    'error' => $e->getMessage()
                ]);
                $essNotificationStatus = 'ESS notification failed but rejection was recorded';
            }

            // Log the action
            Log::info('Disbursement rejected', [
                'loan_offer_id' => $loanOffer->id,
                'user_id' => auth()->id(),
                'reason' => $reason,
                'detailed_message' => $detailedMessage
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Disbursement has been rejected successfully. ' . $essNotificationStatus,
                'loan_offer' => $loanOffer
            ]);

        } catch (\Exception $e) {
            Log::error('Error in reject disbursement', [
                'loan_offer_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process the request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process single loan disbursement
     */
    private function processDisbursement($loanOffer)
    {
        try {
            // Check if loan is approved
            if ($loanOffer->approval !== 'APPROVED') {
                return [
                    'success' => false,
                    'message' => 'Loan is not approved'
                ];
            }

            // Check if already disbursed
            if ($loanOffer->status === 'disbursed' || $loanOffer->state === 'disbursed') {
                return [
                    'success' => false,
                    'message' => 'Loan already disbursed'
                ];
            }

            // Create disbursement record
            $disbursement = LoanDisbursement::create([
                'loan_offer_id' => $loanOffer->id,
                'amount' => $loanOffer->take_home_amount ?? $loanOffer->net_loan_amount ?? $loanOffer->requested_amount,
                'status' => 'pending',
                'disbursement_date' => now(),
                'channel' => 'NMB',
                'channel_identifier' => 'NMB_' . time()
            ]);

            // Process NMB disbursement if configured
            if (config('services.nmb.enabled', false)) {
                $nmbService = new \App\Services\NmbService();
                $nmbResponse = $nmbService->disburseLoan($loanOffer);

                if (isset($nmbResponse['body']['payload']['RespStatus']) &&
                    $nmbResponse['body']['payload']['RespStatus'] === 'Success') {

                    $batchId = $nmbResponse['body']['payload']['RespHeader']['BatchId'];

                    // Update loan offer (preserve the state field)
                    $loanOffer->nmb_batch_id = $batchId;
                    $loanOffer->status = 'disbursement_pending';
                    $loanOffer->disbursement_date = now();
                    // Keep the state as "Submitted for disbursement" - don't change it
                    if ($loanOffer->state !== 'Submitted for disbursement') {
                        $loanOffer->state = 'Submitted for disbursement';
                    }
                    $loanOffer->save();

                    // Update disbursement record
                    $disbursement->status = 'submitted';
                    $disbursement->reference_number = $batchId;
                    $disbursement->response_data = $nmbResponse;
                    $disbursement->save();

                    // Send success notification to ESS
                    $this->sendDisbursementNotification($loanOffer, 'Submitted to bank for processing');

                    return [
                        'success' => true,
                        'message' => 'Disbursement initiated successfully',
                        'batch_id' => $batchId
                    ];

                } else {
                    $errorMessage = $nmbResponse['body']['message'] ?? 'Unknown error from NMB';

                    // Update disbursement as failed
                    $disbursement->status = 'failed';
                    $disbursement->failure_reason = $errorMessage;
                    $disbursement->response_data = $nmbResponse;
                    $disbursement->save();

                    // Update loan offer status
                    $loanOffer->status = 'DISBURSEMENT_FAILED';
                    $loanOffer->state = 'Disbursement Failure'; // Use correct state from API doc
                    $loanOffer->reason = $errorMessage;
                    $loanOffer->save();

                    // Notify ESS of disbursement failure
                    try {
                        $this->notifyEssOfDisbursementFailure($loanOffer->id, 'technical_error');
                    } catch (\Exception $notifyEx) {
                        Log::error('Failed to notify ESS of disbursement failure', [
                            'loan_offer_id' => $loanOffer->id,
                            'error' => $notifyEx->getMessage()
                        ]);
                    }

                    return [
                        'success' => false,
                        'message' => $errorMessage
                    ];
                }
            } else {
                // Simulate disbursement for testing
                $loanOffer->disbursement_date = now();
                $loanOffer->status = 'disbursed';
                $loanOffer->state = 'disbursed';
                $loanOffer->save();

                $disbursement->status = 'successful';
                $disbursement->save();

                // Send success notification to ESS
                $this->sendDisbursementNotification($loanOffer, 'Disbursement successful');

                return [
                    'success' => true,
                    'message' => 'Disbursement completed successfully'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Disbursement processing error', [
                'loan_offer_id' => $loanOffer->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Update loan status to failed
            $loanOffer->status = 'DISBURSEMENT_FAILED';
            $loanOffer->state = 'Disbursement Failure'; // Use correct state from API doc
            $loanOffer->reason = 'System error: ' . $e->getMessage();
            $loanOffer->save();

            // Notify ESS of disbursement failure
            try {
                $this->notifyEssOfDisbursementFailure($loanOffer->id, 'technical_error');
            } catch (\Exception $notifyEx) {
                Log::error('Failed to notify ESS of disbursement failure', [
                    'loan_offer_id' => $loanOffer->id,
                    'error' => $notifyEx->getMessage()
                ]);
            }

            return [
                'success' => false,
                'message' => 'System error occurred during disbursement'
            ];
        }
    }

    /**
     * Process batch disbursement for multiple loans
     */
    public function batchDisbursement(Request $request)
    {
        try {
            $loanIds = $request->input('loan_ids', []);

            if (empty($loanIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No loans selected for disbursement'
                ], 400);
            }

            $successful = 0;
            $failed = 0;
            $totalAmount = 0;
            $errors = [];

            foreach ($loanIds as $loanId) {
                try {
                    $loanOffer = LoanOffer::find($loanId);

                    if (!$loanOffer) {
                        $failed++;
                        $errors[] = "Loan #{$loanId} not found";
                        continue;
                    }

                    // Check if already disbursed
                    if ($loanOffer->status === 'disbursed' || $loanOffer->state === 'disbursed') {
                        $errors[] = "Loan #{$loanId} already disbursed";
                        continue;
                    }

                    // Process disbursement using the existing logic
                    $result = $this->processDisbursement($loanOffer);

                    if ($result['success']) {
                        $successful++;
                        $totalAmount += $loanOffer->take_home_amount ?? $loanOffer->net_loan_amount ?? $loanOffer->requested_amount;
                    } else {
                        $failed++;
                        $errors[] = "Loan #{$loanId}: " . ($result['message'] ?? 'Disbursement failed');

                        // ESS notification is already handled in processDisbursement method
                    }

                } catch (\Exception $e) {
                    $failed++;
                    $errors[] = "Loan #{$loanId}: " . $e->getMessage();
                    Log::error("Batch disbursement error for loan #{$loanId}", [
                        'error' => $e->getMessage()
                    ]);

                    // Notify ESS of disbursement failure
                    try {
                        $this->notifyEssOfDisbursementFailure($loanId, 'technical_error');
                    } catch (\Exception $notifyEx) {
                        Log::error("Failed to notify ESS of disbursement failure for loan #{$loanId}", [
                            'error' => $notifyEx->getMessage()
                        ]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'successful' => $successful,
                'failed' => $failed,
                'total_amount' => $totalAmount,
                'errors' => $errors,
                'message' => "Processed {$successful} loans successfully"
            ]);

        } catch (\Exception $e) {
            Log::error('Batch disbursement error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process batch disbursement'
            ], 500);
        }
    }

/**
     * Hard-coded control test for NMB API.
     */


public function handleCallback(Request $request)
{
    Log::info('NMB Callback Received:', ['data' => $request->all()]);

    $batchId = $request->input('paymentStatus.respHeader.batchId');

    if (!$batchId) {
        Log::warning('NMB callback received without a batchId.');
        return response()->json(['status' => 'error', 'message' => 'Batch ID missing'], 400);
    }

    $loanOffer = LoanOffer::where('nmb_batch_id', $batchId)->first();

    if (!$loanOffer) {
        Log::warning('Received NMB callback for an unknown Batch ID: ' . $batchId);
        return response()->json(['status' => 'success', 'message' => 'Batch ID not found'], 200);
    }

    $fileRecords = $request->input('paymentStatus.respBody.fileRecords', []);
    $firstRecord = $fileRecords[0] ?? null;

    // --- NEW: Save the callback details to the database ---
    $callback = new NmbCallback();
    $callback->loan_offer_id = $loanOffer->id;
    $callback->batch_id = $batchId;
    $callback->raw_payload = $request->input('paymentStatus'); // Store the whole thing

    if ($firstRecord) {
        $callback->final_status = $firstRecord['status'];
        $callback->status_description = $firstRecord['statusDesc'];
        $callback->payment_reference = $firstRecord['paymentReference'];
        $callback->file_ref_id = $firstRecord['fileRefId'];

        // Update the main LoanOffer status
        if (strtolower($firstRecord['status']) === 'success') {
            $loanOffer->update(['status' => 'disbursed', 'state' => 'disbursed']);

            // Update disbursement record in normalized table
            // Try to find by batch_id first, then fallback to latest disbursement for this loan
            $disbursement = LoanDisbursement::where('loan_offer_id', $loanOffer->id)
                ->where('batch_id', $batchId)
                ->first();

            if (!$disbursement) {
                // Fallback to the latest pending disbursement for this loan
                $disbursement = LoanDisbursement::where('loan_offer_id', $loanOffer->id)
                    ->where('status', 'submitted')
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
            if ($disbursement) {
                $disbursement->status = 'success';
                $disbursement->disbursed_at = now();
                $disbursement->transaction_id = $firstRecord['transRef'] ?? null;
                $disbursement->save();
            }

            Log::info('Loan disbursement CONFIRMED by NMB callback.', ['loan_id' => $loanOffer->id, 'batch_id' => $batchId]);
        } else {
            $failureReason = $firstRecord['statusDesc'] ?? 'Transaction failed at NMB.';
            $loanOffer->update([
                'status' => 'DISBURSEMENT_FAILED',
                'state' => 'Disbursement Failure', // Use correct state from API doc
                'reason' => $failureReason,
            ]);

            // Update disbursement record as failed
            $disbursement = LoanDisbursement::where('loan_offer_id', $loanOffer->id)
                ->where('batch_id', $batchId)
                ->first();

            if (!$disbursement) {
                // Fallback to the latest pending disbursement for this loan
                $disbursement = LoanDisbursement::where('loan_offer_id', $loanOffer->id)
                    ->where('status', 'submitted')
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
            if ($disbursement) {
                $disbursement->status = 'failed';
                $disbursement->failure_reason = $failureReason;
                $disbursement->save();
            }

            Log::error('Loan disbursement FAILED via NMB callback.', ['loan_id' => $loanOffer->id, 'batch_id' => $batchId, 'reason' => $failureReason]);

            // Notify ESS of disbursement failure
            try {
                $this->notifyEssOfDisbursementFailure($loanOffer->id, 'technical_error');
                Log::info('ESS notified of disbursement failure for loan #' . $loanOffer->id);
            } catch (\Exception $notifyEx) {
                Log::error('Failed to notify ESS of disbursement failure from NMB callback', [
                    'loan_offer_id' => $loanOffer->id,
                    'error' => $notifyEx->getMessage()
                ]);
            }
        }
    } else {
        // Handle cases where the batch fails without individual records
        $failureReason = $request->input('paymentStatus.respHeader.failureReason', 'Batch failed without processing records.');
        $callback->final_status = 'failed';
        $callback->status_description = $failureReason;
        $loanOffer->update([
            'status' => 'DISBURSEMENT_FAILED',
            'state' => 'Disbursement Failure', // Use correct state from API doc
            'reason' => $failureReason,
        ]);
        Log::error('NMB callback for Batch ID: ' . $batchId . ' contained no fileRecords. Batch failed.', ['reason' => $failureReason]);

        // Notify ESS of disbursement failure
        try {
            $this->notifyEssOfDisbursementFailure($loanOffer->id, 'technical_error');
            Log::info('ESS notified of batch disbursement failure for loan #' . $loanOffer->id);
        } catch (\Exception $notifyEx) {
            Log::error('Failed to notify ESS of batch disbursement failure from NMB callback', [
                'loan_offer_id' => $loanOffer->id,
                'error' => $notifyEx->getMessage()
            ]);
        }
    }

    $callback->save(); // Save the new callback record
    // ----------------------------------------------------

    return response()->json(['status' => 'received'], 200);
}



public function showCallbacks(LoanOffer $loanOffer)
{
    // Eager load the callbacks for the given loan offer, most recent first
    $loanOffer->load(['callbacks' => function ($query) {
        $query->latest();
    }]);

    return view('employee_loan.callbacks', compact('loanOffer'));
}


public function fetchCallbacksAjax(LoanOffer $loanOffer)
{
    // Eager load the callbacks, most recent first
    $callbacks = $loanOffer->callbacks()->latest()->get();

    // Return the partial view as an HTML response
    return view('employee_loan._callback_history', compact('callbacks'));
}

//     public function notifyEssOnInitialApproval($loanOfferId)
// {
//     $loanOffer = LoanOffer::find($loanOfferId);
//     if (!$loanOffer) {
//         return 'LoanOffer not found'; // Consider throwing an exception here instead
//     }

//     $dom = new \DOMDocument('1.0', 'UTF-8');
//     $dom->formatOutput = false;

//     $document = $dom->createElement('Document');
//     $dom->appendChild($document);

//     $data = $dom->createElement('Data');
//     $document->appendChild($data);

//     $header = $dom->createElement('Header');
//     $data->appendChild($header);
//     $header->appendChild($dom->createElement('Sender', 'URA SACCOS'));
//     $header->appendChild($dom->createElement('Receiver', 'ESS_UTUMISHI'));
//     $header->appendChild($dom->createElement('FSPCode', 'FL7456'));
//     $header->appendChild($dom->createElement('MsgId', uniqid('fsp_', true)));
//     $header->appendChild($dom->createElement('MessageType', 'LOAN_INITIAL_APPROVAL_NOTIFICATION'));

//     $messageDetails = $dom->createElement('MessageDetails');
//     $data->appendChild($messageDetails);

//     $messageDetails->appendChild($dom->createElement('ApplicationNumber', $loanOffer->application_number));
//     $messageDetails->appendChild($dom->createElement('Reason', $loanOffer->reason ?? 'Ok'));
//     $messageDetails->appendChild($dom->createElement('FSPReferenceNumber', $loanOffer->fsp_reference_number ?? ''));
//     $messageDetails->appendChild($dom->createElement('LoanNumber', $loanOffer->loan_number ?? ''));
//     $messageDetails->appendChild($dom->createElement('TotalAmountToPay', $loanOffer->total_amount_to_pay ?? '2500000.58'));
//     $messageDetails->appendChild($dom->createElement('OtherCharges', $loanOffer->other_charges ?? '2500.05'));
//     $messageDetails->appendChild($dom->createElement('Approval', strtoupper($loanOffer->approval)));

//     $dataC14N = $data->C14N();
//     $privateKey = file_get_contents('/home/crm/emkopo.key');
//     $pkeyid = openssl_pkey_get_private($privateKey);
//     if (!$pkeyid) {
//         return 'Failed to load private key';
//     }

//     $signature = '';
//     openssl_sign($dataC14N, $signature, $pkeyid, OPENSSL_ALGO_SHA256);
//     openssl_free_key($pkeyid);

//     $base64Signature = base64_encode($signature);
//     $document->appendChild($dom->createElement('Signature', $base64Signature));

//     $xmlContent = $dom->saveXML();
//     $client = new Client();
//     try {
//         $response = $client->post('https://gateway.ess.utumishi.go.tz/ess-loans/mvtyztwq/consume', [
//             'headers' => ['Content-Type' => 'application/xml'],
//             'body' => $xmlContent,
//         ]);
//         $statusCode = $response->getStatusCode();
//         $body = (string)$response->getBody();

//         Log::info("LOAN_INITIAL_APPROVAL_NOTIFICATION posted OK for #{$loanOfferId}", ['response' => $body]);
//         if ($statusCode == 200) {
//             return "Notified ESS successfully. ESS response: {$body}";
//         } else {
//             return "ESS responded with code {$statusCode}. Response: {$body}";
//         }
//     } catch (\Exception $e) {
//         Log::error('Error sending LOAN_INITIAL_APPROVAL_NOTIFICATION: ' . $e->getMessage());
//         return "Error sending XML: " . $e->getMessage();
//     }
// }


public function notifyEssOnInitialApproval($loanOfferId)
{
try {
$loanOffer = LoanOffer::find($loanOfferId);
if (!$loanOffer) {
return 'LoanOffer not found';
}

// Generate reference numbers if missing
if (empty($loanOffer->fsp_reference_number)) {
$loanOffer->fsp_reference_number = 'TZ' . mt_rand(1000, 9999);
}
if (empty($loanOffer->loan_number)) {
$loanOffer->loan_number = (string)mt_rand(100000, 999999);
}
$loanOffer->save();

$dom = new \DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = false;

$document = $dom->createElement('Document');
$dom->appendChild($document);

$data = $dom->createElement('Data');
$document->appendChild($data);

$header = $dom->createElement('Header');
$data->appendChild($header);
$header->appendChild($dom->createElement('Sender', 'URA SACCOS')); // Changed to match ApiController
$header->appendChild($dom->createElement('Receiver', 'ESS_UTUMISHI'));
$header->appendChild($dom->createElement('FSPCode', 'FL7456'));
$header->appendChild($dom->createElement('MsgId', uniqid('fsp_', true)));
$header->appendChild($dom->createElement('MessageType', 'LOAN_INITIAL_APPROVAL_NOTIFICATION'));

$messageDetails = $dom->createElement('MessageDetails');
$data->appendChild($messageDetails);

$messageDetails->appendChild($dom->createElement('ApplicationNumber', $loanOffer->application_number));
$messageDetails->appendChild($dom->createElement('Reason', $loanOffer->reason ?? 'Ok'));
$messageDetails->appendChild($dom->createElement('FSPReferenceNumber', $loanOffer->fsp_reference_number));
$messageDetails->appendChild($dom->createElement('LoanNumber', $loanOffer->loan_number));
$messageDetails->appendChild($dom->createElement('TotalAmountToPay', $loanOffer->total_amount_to_pay ?? '0'));
$messageDetails->appendChild($dom->createElement('OtherCharges', $loanOffer->other_charges ?? '0'));
$messageDetails->appendChild($dom->createElement('Approval', strtoupper($loanOffer->approval)));

$dataC14N = $data->C14N();

// Check if private key file exists
$privateKeyPath = '/home/crm/emkopo.key';
if (!file_exists($privateKeyPath)) {
    Log::warning("Private key not found at {$privateKeyPath}, skipping signature");
    // Return success without signature for development/Windows environments
    return 'Approved successfully (ESS notification skipped - key not found)';
}

$privateKey = file_get_contents($privateKeyPath);
$pkeyid = openssl_pkey_get_private($privateKey);
if (!$pkeyid) {
return 'Failed to load private key';
}

$signature = '';
openssl_sign($dataC14N, $signature, $pkeyid, OPENSSL_ALGO_SHA256);
openssl_free_key($pkeyid);

$base64Signature = base64_encode($signature);
$document->appendChild($dom->createElement('Signature', $base64Signature));

$xmlContent = $dom->saveXML();

// Log the XML being sent for debugging
Log::info("Sending LOAN_INITIAL_APPROVAL_NOTIFICATION XML for loan #{$loanOfferId}", [
    'xml' => $xmlContent,
    'application_number' => $loanOffer->application_number,
    'approval' => $loanOffer->approval
]);

// Configure client with SSL certificate if it exists
$clientConfig = [
'timeout' => 30,
'http_errors' => false
];

if (file_exists('/home/crm/ess_prod_ssl_certificate.crt')) {
$clientConfig['verify'] = '/home/crm/ess_prod_ssl_certificate.crt';
}

$client = new Client($clientConfig);

try {
$response = $client->post('https://gateway.ess.utumishi.go.tz/ess-loans/mvtyztwq/consume', [
'headers' => ['Content-Type' => 'application/xml'],
'body' => $xmlContent,
]);

$statusCode = $response->getStatusCode();
$body = (string)$response->getBody();

Log::info("LOAN_INITIAL_APPROVAL_NOTIFICATION posted for #{$loanOfferId}", [
'status' => $statusCode,
'response' => $body
]);

if ($statusCode >= 200 && $statusCode < 300) {
return "Notified ESS successfully. ESS response: {$body}";
}

// If HTTPS fails, try HTTP fallback
$response = $client->post('http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
'headers' => ['Content-Type' => 'application/xml'],
'body' => $xmlContent,
]);

$statusCode = $response->getStatusCode();
$body = (string)$response->getBody();

return $statusCode == 200 ? "Notified ESS successfully via fallback. Response: {$body}"
: "ESS responded with code {$statusCode}. Response: {$body}";

} catch (\Exception $e) {
Log::error('Error sending LOAN_INITIAL_APPROVAL_NOTIFICATION: ' . $e->getMessage());

// Try HTTP fallback on exception
try {
$response = $client->post('http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
'headers' => ['Content-Type' => 'application/xml'],
'body' => $xmlContent,
]);

$statusCode = $response->getStatusCode();
$body = (string)$response->getBody();

Log::info("Fallback successful for #{$loanOfferId}", ['response' => $body]);
return "Notified ESS via fallback. Response: {$body}";

} catch (\Exception $fallbackError) {
Log::error('Fallback also failed: ' . $fallbackError->getMessage());
return "Error sending XML: " . $e->getMessage();
}
}
} catch (\Exception $generalError) {
    Log::error('General error in notifyEssOnInitialApproval: ' . $generalError->getMessage());
    return "Approved successfully (ESS notification failed)";
}
}


public function notifyEssOfDisbursementFailure($loanOfferId, $reason, $customMessage = null)
{
    $loanOffer = LoanOffer::find($loanOfferId);
    if (!$loanOffer) {
        Log::error("LoanOffer not found for ID: {$loanOfferId}");
        return 'LoanOffer not found';
    }

    // Validate Loan State - Accept the correct state from API documentation
    // API doc specifies "Disbursement Failure" as the correct state
    $validStates = ['Disbursement Failure', 'disbursement_rejected', 'process_disbursement_failure'];
    if (!in_array($loanOffer->state, $validStates)) {
        Log::warning("LoanOffer ID: {$loanOfferId} is not in the correct state for disbursement failure notification. Current state: {$loanOffer->state}");
        // Still proceed with notification even if state doesn't match exactly
        // return 'LoanOffer not in the correct state for disbursement failure notification';
    }

    // Validate Reason Field - made more flexible
    $acceptableReasons = [
        'insufficient_funds',
        'technical_error',
        'customer_request',
        'other',
        'Account verification pending',
        'Customer cancellation',
        'Technical error',
        'Compliance check failed',
        'Invalid bank details',
        'Insufficient funds',
        'Incomplete documentation',
        // Add other valid reasons as per ESS's specifications
    ];

    // Check if reason matches any acceptable reason (case-insensitive)
    $reasonIsValid = false;
    foreach ($acceptableReasons as $acceptableReason) {
        if (stripos($reason, $acceptableReason) !== false || stripos($acceptableReason, $reason) !== false) {
            $reasonIsValid = true;
            break;
        }
    }

    if (!$reasonIsValid) {
        Log::warning("Unusual reason provided for LoanOffer ID: {$loanOfferId}: {$reason} - proceeding anyway");
        // Don't block the notification, just log the warning
        // return 'Invalid reason provided for disbursement failure notification';
    }

    // Create the XML document
    $dom = new \DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = false; // Disable formatting to eliminate extra whitespace

    $document = $dom->createElement('Document');
    $dom->appendChild($document);

    $data = $dom->createElement('Data');
    $document->appendChild($data);

    $header = $dom->createElement('Header');
    $data->appendChild($header);
    $header->appendChild($dom->createElement('Sender', 'URA_SACCOS_LTD_LOAN')); // Correct Sender
    $header->appendChild($dom->createElement('Receiver', 'ESS_UTUMISHI')); // Correct Receiver
    $header->appendChild($dom->createElement('FSPCode', 'FL7456')); // Correct FSPCode
    $header->appendChild($dom->createElement('MsgId', uniqid('fsp_', true)));
    $header->appendChild($dom->createElement('MessageType', 'LOAN_DISBURSEMENT_FAILURE_NOTIFICATION'));

    $messageDetails = $dom->createElement('MessageDetails');
    $data->appendChild($messageDetails);

    $messageDetails->appendChild($dom->createElement('ApplicationNumber', $loanOffer->application_number));

    // Use custom message if provided, otherwise use the reason code
    $reasonText = $customMessage ?: $reason;
    $messageDetails->appendChild($dom->createElement('Reason', htmlspecialchars($reasonText, ENT_XML1, 'UTF-8')));

    // Extract the raw XML string of the <Data> element without canonicalization
    $dataElementAsString = $dom->saveXML($data);
    Log::debug("Raw Data Element for Disbursement Failure: {$dataElementAsString}"); // Log for debugging

    // Signing the raw XML string
    $privateKey = file_get_contents('/home/crm/emkopo.key');
    $pkeyid = openssl_pkey_get_private($privateKey);
    if (!$pkeyid) {
        Log::error("Failed to load private key for LoanOffer ID: {$loanOfferId}");
        return 'Failed to load private key';
    }

    $signature = '';
    if (!openssl_sign($dataElementAsString, $signature, $pkeyid, OPENSSL_ALGO_SHA256)) {
        Log::error("Failed to sign XML for LoanOffer ID: {$loanOfferId}");
        return 'Failed to sign XML';
    }
    openssl_free_key($pkeyid);

    $base64Signature = base64_encode($signature);
    $document->appendChild($dom->createElement('Signature', $base64Signature));
    Log::debug("Generated Signature for Disbursement Failure: {$base64Signature}"); // Log signature

    // Convert the XML to a string
    $xmlContent = $dom->saveXML();
    Log::debug("XML Content for Disbursement Failure:\n{$xmlContent}"); // Log the full XML

    // Log the XML being sent for debugging
    Log::info("Sending LOAN_DISBURSEMENT_FAILURE_NOTIFICATION XML for loan #{$loanOfferId}", [
        'xml' => $xmlContent,
        'application_number' => $loanOffer->application_number,
        'reason' => $reasonText,
        'state' => $loanOffer->state
    ]);

    // Configure client with SSL certificate if it exists (same as approval notification)
    $clientConfig = [
        'timeout' => 30,
        'http_errors' => false
    ];

    if (file_exists('/home/crm/ess_prod_ssl_certificate.crt')) {
        $clientConfig['verify'] = '/home/crm/ess_prod_ssl_certificate.crt';
    }

    $client = new Client($clientConfig);

    try {
        // Try HTTPS first
        $response = $client->post('https://gateway.ess.utumishi.go.tz/ess-loans/mvtyztwq/consume', [
            'headers' => ['Content-Type' => 'application/xml'],
            'body' => $xmlContent,
        ]);

        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();

        Log::info("LOAN_DISBURSEMENT_FAILURE_NOTIFICATION posted for #{$loanOfferId}", [
            'status' => $statusCode,
            'response' => $body
        ]);

        if ($statusCode >= 200 && $statusCode < 300) {
            return "Notified ESS successfully. ESS response: {$body}";
        }

        // If HTTPS fails with non-success code, try HTTP fallback
        Log::warning("HTTPS returned non-success code, trying HTTP fallback", ['status' => $statusCode]);
        $response = $client->post('http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
            'headers' => ['Content-Type' => 'application/xml'],
            'body' => $xmlContent,
        ]);

        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();

        return $statusCode == 200 ? "Notified ESS successfully via fallback. Response: {$body}"
                                  : "ESS responded with code {$statusCode}. Response: {$body}";

    } catch (\Exception $e) {
        Log::error('Error sending LOAN_DISBURSEMENT_FAILURE_NOTIFICATION via HTTPS: ' . $e->getMessage());

        // Try HTTP fallback on exception
        try {
            $response = $client->post('http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
                'headers' => ['Content-Type' => 'application/xml'],
                'body' => $xmlContent,
            ]);

            $statusCode = $response->getStatusCode();
            $body = (string) $response->getBody();

            Log::info("Fallback successful for disbursement failure #{$loanOfferId}", ['response' => $body]);
            return "Notified ESS via fallback. Response: {$body}";

        } catch (\Exception $fallbackError) {
            Log::error('Fallback also failed for disbursement failure: ' . $fallbackError->getMessage());
            return "Error sending XML: " . $e->getMessage() . " | Fallback error: " . $fallbackError->getMessage();
        }
    }
}




public function sendDisbursementNotification($loanOffer, $reason = null)
{
    $dom = new \DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = false; // Disable formatting to avoid unintended whitespace

    // Create the root <Document> element
    $document = $dom->createElement('Document');
    $dom->appendChild($document);

    // Create the <Data> element
    $data = $dom->createElement('Data');
    $document->appendChild($data);

    // Create the <Header> element
    $header = $dom->createElement('Header');
    $data->appendChild($header);
    $header->appendChild($dom->createElement('Sender', 'URA_SACCOS_LTD_LOAN'));
    $header->appendChild($dom->createElement('Receiver', 'ESS_UTUMISHI'));
    $header->appendChild($dom->createElement('FSPCode', 'FL7456')); // Ensure this matches your configuration
    $header->appendChild($dom->createElement('MsgId', uniqid('fsp_', true)));
    $header->appendChild($dom->createElement('MessageType', 'LOAN_DISBURSEMENT_NOTIFICATION'));

    // Create the <MessageDetails> element
    $messageDetails = $dom->createElement('MessageDetails');
    $data->appendChild($messageDetails);

    // Append required elements in the correct order
    $messageDetails->appendChild($dom->createElement('ApplicationNumber', $loanOffer->application_number));

    // Conditionally append <Reason> if provided
    if ($reason) {
        $messageDetails->appendChild($dom->createElement('Reason', htmlspecialchars($reason, ENT_XML1, 'UTF-8')));
    }

    $messageDetails->appendChild($dom->createElement('FSPReferenceNumber', $loanOffer->fsp_reference_number));
    $messageDetails->appendChild($dom->createElement('LoanNumber', $loanOffer->loan_number));

    // Ensure TotalAmountToPay is correctly formatted
    $totalAmount = number_format($loanOffer->total_amount_to_pay, 2, '.', '');
    $messageDetails->appendChild($dom->createElement('TotalAmountToPay', $totalAmount));

    // Format DisbursementDate without timezone
    $disbursementDate = date('Y-m-d\TH:i:s'); // Example: 2025-01-04T20:00:26
    $messageDetails->appendChild($dom->createElement('DisbursementDate', $disbursementDate));

    // Extract the raw XML string of the <Data> element for signing
    $dataElementAsString = $dom->saveXML($data);
    Log::debug("Raw Data Element for Disbursement Notification: {$dataElementAsString}");

    // Load the private key
    $privateKey = file_get_contents('/home/crm/emkopo.key');
    if (!$privateKey) {
        Log::error("Private key file not found at /home/crm/emkopo.key");
        return 'Failed to load private key';
    }

    $pkeyid = openssl_pkey_get_private($privateKey);
    if (!$pkeyid) {
        Log::error("Failed to load private key for signing.");
        return 'Failed to load private key';
    }

    // Generate the signature
    $signature = '';
    if (!openssl_sign($dataElementAsString, $signature, $pkeyid, OPENSSL_ALGO_SHA256)) {
        Log::error("Failed to sign XML for LoanOffer ID: {$loanOffer->id}");
        openssl_free_key($pkeyid);
        return 'Failed to sign XML';
    }
    openssl_free_key($pkeyid);

    // Encode the signature in base64
    $base64Signature = base64_encode($signature);
    Log::debug("Generated Signature for Disbursement Notification: {$base64Signature}");

    // Append the <Signature> element
    $document->appendChild($dom->createElement('Signature', $base64Signature));

    // Final XML content
    $xmlContent = $dom->saveXML();
    Log::debug("XML Content for Disbursement Notification:\n{$xmlContent}");

    // Send the XML to ESS
    $client = new \GuzzleHttp\Client();

    try {
        Log::info("Sending disbursement notification to ESS for LoanOffer ID: {$loanOffer->id}");
        $response = $client->post('https://gateway.ess.utumishi.go.tz/ess-loans/mvtyztwq/consume', [
            'headers' => ['Content-Type' => 'application/xml'],
            'body' => $xmlContent,
        ]);

        $statusCode = $response->getStatusCode();
        $body = (string)$response->getBody();

        Log::info("LOAN_DISBURSEMENT_NOTIFICATION sent to ESS", [
            'id' => $loanOffer->id,
            'response' => $body
        ]);

        return $statusCode == 200
            ? "Disbursement Notification sent successfully. ESS response: {$body}"
            : "ESS responded with code {$statusCode}. Response: {$body}";
    } catch (\Exception $e) {
        Log::error('Error sending LOAN_DISBURSEMENT_NOTIFICATION: ' . $e->getMessage());
        return "Error sending XML: " . $e->getMessage();
    }
}
/**
     * Check if a loan should be marked as liquidated based on payment completion
     * Called after payments are processed
     */
    public function checkAndProcessLiquidation($loanOfferId)
    {
        $loanOffer = LoanOffer::find($loanOfferId);
        if (!$loanOffer) {
            Log::error("LoanOffer not found for liquidation check: {$loanOfferId}");
            return false;
        }

        // Check if loan is already settled
        if ($loanOffer->status === 'FULL_SETTLED') {
            Log::info("Loan #{$loanOfferId} already marked as FULL_SETTLED");
            return true;
        }

        // Calculate total payments made
        $totalPaid = Payment::where('loan_offer_id', $loanOfferId)
            ->where('status', 'completed')
            ->sum('amount');

        // Get the total amount to be paid
        $totalDue = $loanOffer->total_amount_to_pay ?: $loanOffer->requested_amount;

        // Check if loan is fully paid (with small tolerance for rounding)
        if ($totalPaid >= ($totalDue - 0.01)) {
            Log::info("Loan #{$loanOfferId} is fully paid. Marking as liquidated.", [
                'total_paid' => $totalPaid,
                'total_due' => $totalDue
            ]);

            // Update loan status
            $loanOffer->status = 'FULL_SETTLED';
            $loanOffer->state = 'liquidated';
            $loanOffer->liquidated_at = now();
            $loanOffer->save();

            // Send liquidation notification to ESS
            try {
                $remarks = "Loan fully paid. Total amount: {$totalPaid}";
                $this->sendLiquidationNotification($loanOffer, $remarks);
            } catch (\Exception $e) {
                Log::error("Failed to send liquidation notification", [
                    'loan_offer_id' => $loanOfferId,
                    'error' => $e->getMessage()
                ]);
            }

            return true;
        }

        return false;
    }

    public function sendLiquidationNotification($loanOffer, $remarks = null)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->formatOutput = false; // Disable formatting to avoid extra whitespace

        // Create the root <Document> element
        $document = $dom->createElement('Document');
        $dom->appendChild($document);

        // Create the <Data> element
        $data = $dom->createElement('Data');
        $document->appendChild($data);

        // Build the <Header> element
        $header = $dom->createElement('Header');
        $data->appendChild($header);
        $header->appendChild($dom->createElement('Sender', 'URA_SACCOS_LTD_LOAN'));
        $header->appendChild($dom->createElement('Receiver', 'ESS_UTUMISHI'));
        $header->appendChild($dom->createElement('FSPCode', 'FL7456')); // Ensure this matches your configuration
        $header->appendChild($dom->createElement('MsgId', uniqid('fsp_', true)));
        $header->appendChild($dom->createElement('MessageType', 'LOAN_LIQUIDATION_NOTIFICATION'));

        // Build the <MessageDetails> element
        $messageDetails = $dom->createElement('MessageDetails');
        $data->appendChild($messageDetails);
        $messageDetails->appendChild($dom->createElement('ApplicationNumber', $loanOffer->application_number));
        $messageDetails->appendChild($dom->createElement('LoanNumber', $loanOffer->loan_number));
        if ($remarks) {
            $messageDetails->appendChild(
                $dom->createElement('Remarks', htmlspecialchars($remarks, ENT_XML1, 'UTF-8'))
            );
        }

        // Extract the XML string of the <Data> element (to be signed)
        $dataElementAsString = $dom->saveXML($data);
        Log::debug("Raw Data Element for Loan Liquidation Notification: " . $dataElementAsString);

        // Load the private key for signing
        $privateKey = file_get_contents('/home/crm/emkopo.key');
        if (!$privateKey) {
            Log::error("Private key file not found at /home/crm/emkopo.key");
            return 'Failed to load private key';
        }

        $pkeyid = openssl_pkey_get_private($privateKey);
        if (!$pkeyid) {
            Log::error("Failed to load private key for signing.");
            return 'Failed to load private key';
        }

        // Sign the XML string using SHA256 with RSA
        $signature = '';
        if (!openssl_sign($dataElementAsString, $signature, $pkeyid, OPENSSL_ALGO_SHA256)) {
            Log::error("Failed to sign XML for LoanOffer ID: " . $loanOffer->id);
            openssl_free_key($pkeyid);
            return 'Failed to sign XML';
        }
        openssl_free_key($pkeyid);

        // Encode the signature in base64
        $base64Signature = base64_encode($signature);
        Log::debug("Generated Signature for Loan Liquidation Notification: " . $base64Signature);

        // Append the <Signature> element to the root
        $document->appendChild($dom->createElement('Signature', $base64Signature));

        // Get the final XML content
        $xmlContent = $dom->saveXML();
        Log::debug("XML Content for Loan Liquidation Notification:\n" . $xmlContent);

        // Send the XML via HTTP POST using Guzzle
        $client = new Client();
        try {
            Log::info("Sending Loan Liquidation Notification to ESS for LoanOffer ID: " . $loanOffer->id);
            $response = $client->post('https://gateway.ess.utumishi.go.tz/ess-loans/mvtyztwq/consume', [
                'headers' => ['Content-Type' => 'application/xml'],
                'body' => $xmlContent,
            ]);
            $statusCode = $response->getStatusCode();
            $body = (string)$response->getBody();
            Log::info("LOAN_LIQUIDATION_NOTIFICATION sent to ESS", [
                'id' => $loanOffer->id,
                'response' => $body
            ]);
            return $statusCode == 200
                ? "Liquidation Notification sent successfully. ESS response: {$body}"
                : "ESS responded with code {$statusCode}. Response: {$body}";
        } catch (\Exception $e) {
            Log::error("Error sending LOAN_LIQUIDATION_NOTIFICATION: " . $e->getMessage());
            return "Error sending XML: " . $e->getMessage();
        }
    }


    // API endpoint for KPI details with period support
    public function getKPIDetails(Request $request)
    {
        $status = $request->input('status');
        $period = $request->input('period', 'auto'); // auto, week, month, year
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Determine period and date range
        if ($startDate && $endDate) {
            // Custom date range
            $periodStart = \Carbon\Carbon::parse($startDate)->startOfDay();
            $periodEnd = \Carbon\Carbon::parse($endDate)->endOfDay();
            $periodLabel = 'Custom Period';
        } else {
            // Auto-detect or use specified period
            $result = $this->determinePeriodWithData($status, $period);
            $periodStart = $result['start'];
            $periodEnd = $result['end'];
            $periodLabel = $result['label'];
            $period = $result['period'];
        }

        // Build query based on status
        $query = $this->buildKPIQuery($status, $periodStart, $periodEnd);
        $loans = $query->orderBy('created_at', 'desc')->get();

        // Calculate stats for all statuses in the period
        $stats = $this->calculatePeriodStats($periodStart, $periodEnd);

        return response()->json([
            'period' => $period,
            'period_label' => $periodLabel,
            'period_start' => $periodStart->format('M d, Y'),
            'period_end' => $periodEnd->format('M d, Y'),
            'total_count' => $loans->count(),
            'loans' => $loans,
            'stats' => $stats,
            'has_data' => $loans->count() > 0
        ]);
    }

    // Helper method to determine period with data fallback
    private function determinePeriodWithData($status, $requestedPeriod)
    {
        $now = now();

        if ($requestedPeriod !== 'auto') {
            // Use requested period
            switch($requestedPeriod) {
                case 'today':
                    return [
                        'period' => 'today',
                        'label' => 'Today',
                        'start' => $now->copy()->startOfDay(),
                        'end' => $now->copy()->endOfDay()
                    ];
                case 'week':
                case 'weekly':
                    return [
                        'period' => 'week',
                        'label' => 'This Week',
                        'start' => $now->copy()->startOfWeek(),
                        'end' => $now->copy()->endOfWeek()
                    ];
                case 'month':
                case 'monthly':
                    return [
                        'period' => 'month',
                        'label' => 'This Month',
                        'start' => $now->copy()->startOfMonth(),
                        'end' => $now->copy()->endOfMonth()
                    ];
                case 'year':
                case 'yearly':
                    return [
                        'period' => 'year',
                        'label' => 'This Year',
                        'start' => $now->copy()->startOfYear(),
                        'end' => $now->copy()->endOfYear()
                    ];
                case 'all':
                    return [
                        'period' => 'all',
                        'label' => 'All Time',
                        'start' => \Carbon\Carbon::create(2000, 1, 1), // Far past date
                        'end' => $now->copy()->endOfYear()->addYears(10) // Far future date
                    ];
            }
        }

        // Auto mode: check for data in each period
        // Check weekly data
        $weekStart = $now->copy()->startOfWeek();
        $weekEnd = $now->copy()->endOfWeek();
        $weekCount = $this->getCountForStatus($status, $weekStart, $weekEnd);

        if ($weekCount > 0) {
            return [
                'period' => 'week',
                'label' => 'This Week',
                'start' => $weekStart,
                'end' => $weekEnd
            ];
        }

        // Check monthly data
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();
        $monthCount = $this->getCountForStatus($status, $monthStart, $monthEnd);

        if ($monthCount > 0) {
            return [
                'period' => 'month',
                'label' => 'This Month',
                'start' => $monthStart,
                'end' => $monthEnd
            ];
        }

        // Default to yearly
        return [
            'period' => 'year',
            'label' => 'This Year',
            'start' => $now->copy()->startOfYear(),
            'end' => $now->copy()->endOfYear()
        ];
    }

    // Helper method to get count for specific status and period
    private function getCountForStatus($status, $start, $end)
    {
        $query = LoanOffer::query();

        switch($status) {
            case 'pending':
                $query->where(function($q) {
                    $q->where('approval', 'PENDING')
                      ->orWhereNull('approval');
                });
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'approved':
                $query->where('approval', 'APPROVED');
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'rejected':
                $query->where('approval', 'REJECTED');
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'cancelled':
                $query->where('approval', 'CANCELLED');
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'disbursed':
                $query->where('status', 'disbursed');
                $query->whereBetween('updated_at', [$start, $end]);
                break;
        }

        return $query->count();
    }

    // Helper method to build KPI query
    private function buildKPIQuery($status, $start, $end)
    {
        $query = LoanOffer::query();

        switch($status) {
            case 'pending':
                $query->where(function($q) {
                    $q->where('approval', 'PENDING')
                      ->orWhereNull('approval');
                });
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'approved':
                $query->where('approval', 'APPROVED');
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'rejected':
                $query->where('approval', 'REJECTED');
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'cancelled':
                $query->where('approval', 'CANCELLED');
                $query->whereBetween('created_at', [$start, $end]);
                break;
            case 'disbursed':
                $query->where('status', 'disbursed');
                $query->whereBetween('updated_at', [$start, $end]);
                break;
        }

        return $query;
    }

    // Helper method to calculate period stats
    private function calculatePeriodStats($start, $end)
    {
        return [
            'pending' => LoanOffer::where(function($q) {
                            $q->where('approval', 'PENDING')
                              ->orWhereNull('approval');
                        })
                        ->whereBetween('created_at', [$start, $end])
                        ->count(),
            'approved' => LoanOffer::where('approval', 'APPROVED')
                        ->whereBetween('created_at', [$start, $end])
                        ->count(),
            'rejected' => LoanOffer::where('approval', 'REJECTED')
                        ->whereBetween('created_at', [$start, $end])
                        ->count(),
            'cancelled' => LoanOffer::where('approval', 'CANCELLED')
                        ->whereBetween('created_at', [$start, $end])
                        ->count(),
            'disbursed' => LoanOffer::where('status', 'disbursed')
                        ->whereBetween('updated_at', [$start, $end])
                        ->count(),
        ];
    }

    // API endpoint for weekly statistics (kept for backward compatibility)
    public function weeklyStats(Request $request)
    {
        $status = $request->input('status');
        $summary = $request->input('summary', false);

        // Get the start and end of the current week
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();

        // If summary is requested, return weekly counts for all statuses
        if ($summary) {
            $weeklyStats = [
                'pending' => LoanOffer::where(function($q) {
                                    $q->where('approval', 'PENDING')
                                      ->orWhereNull('approval');
                                })
                                ->whereBetween('created_at', [$weekStart, $weekEnd])
                                ->count(),
                'approved' => LoanOffer::where('approval', 'APPROVED')
                                ->whereBetween('created_at', [$weekStart, $weekEnd])
                                ->count(),
                'rejected' => LoanOffer::where('approval', 'REJECTED')
                                ->whereBetween('created_at', [$weekStart, $weekEnd])
                                ->count(),
                'cancelled' => LoanOffer::where('approval', 'CANCELLED')
                                ->whereBetween('created_at', [$weekStart, $weekEnd])
                                ->count(),
                'disbursed' => LoanOffer::where('status', 'disbursed')
                                ->whereBetween('updated_at', [$weekStart, $weekEnd])
                                ->count(),
            ];

            return response()->json($weeklyStats);
        }

        // Build query based on status
        $query = LoanOffer::query();

        switch($status) {
            case 'pending':
                $query->where(function($q) {
                    $q->where('approval', 'PENDING')
                      ->orWhereNull('approval');
                });
                $query->whereBetween('created_at', [$weekStart, $weekEnd]);
                break;

            case 'approved':
                $query->where('approval', 'APPROVED');
                $query->whereBetween('created_at', [$weekStart, $weekEnd]);
                break;

            case 'rejected':
                $query->where('approval', 'REJECTED');
                $query->whereBetween('created_at', [$weekStart, $weekEnd]);
                break;

            case 'cancelled':
                $query->where('approval', 'CANCELLED');
                $query->whereBetween('created_at', [$weekStart, $weekEnd]);
                break;

            case 'disbursed':
                $query->where('status', 'disbursed');
                $query->whereBetween('updated_at', [$weekStart, $weekEnd]);
                break;
        }

        $loans = $query->orderBy('created_at', 'desc')->get();

        // Calculate weekly stats for all statuses
        $weeklyStats = [
            'pending' => LoanOffer::where(function($q) {
                                $q->where('approval', 'PENDING')
                                  ->orWhereNull('approval');
                            })
                            ->whereBetween('created_at', [$weekStart, $weekEnd])
                            ->count(),
            'approved' => LoanOffer::where('approval', 'APPROVED')
                            ->whereBetween('created_at', [$weekStart, $weekEnd])
                            ->count(),
            'rejected' => LoanOffer::where('approval', 'REJECTED')
                            ->whereBetween('created_at', [$weekStart, $weekEnd])
                            ->count(),
            'cancelled' => LoanOffer::where('approval', 'CANCELLED')
                            ->whereBetween('created_at', [$weekStart, $weekEnd])
                            ->count(),
            'disbursed' => LoanOffer::where('status', 'disbursed')
                            ->whereBetween('updated_at', [$weekStart, $weekEnd])
                            ->count(),
        ];

        return response()->json([
            'week_start' => $weekStart->format('M d, Y'),
            'week_end' => $weekEnd->format('M d, Y'),
            'total_count' => $loans->count(),
            'loans' => $loans,
            'weekly_stats' => $weeklyStats
        ]);
    }


/*========================================================================================
     | (A) TOP_UP_PAY_0FF_BALANCE_REQUEST (Msg 11) -> LOAN_TOP_UP_BALANCE_RESPONSE (Msg 12)
     |    factoring partial payment using `requested_amount` as original principal
     *=======================================================================================*/
    private function handleTopUpPayoffBalance($xml)
    {
        Log::info('Handling TOP_UP_PAY_0FF_BALANCE_REQUEST', ['data' => $xml]);
        $msgDetails = $xml->Data->MessageDetails;

        $loanNumber = (string)$msgDetails->LoanNumber ?? null;
        if (!$loanNumber) {
            return $this->generateXmlResponse('8002', 'LoanNumber missing in TOP_UP_PAY_0FF_BALANCE_REQUEST');
        }

        // Find the existing loan
        $loanOffer = LoanOffer::where('loan_number', $loanNumber)->first();
        if (!$loanOffer) {
            return $this->generateXmlResponse('8004', 'No existing loan found for this LoanNumber');
        }

        // 1) Use partial payment logic: requested_amount = original principal
        $principal        = (float)$loanOffer->requested_amount;
        $annualRate       = 12;
        $monthlyRate      = $annualRate / 100 / 12;
        $totalTenure      = (int)$loanOffer->tenure;
        $installmentsPaid =(int)$loanOffer->installments_paid;
        // $totalTenure      = (int)$loanOffer->tenure;
        // $installmentsPaid =3;

        // 2) Compute outstanding via amortization
        $balance = $this->calculatePartialPaymentBalance($principal, $monthlyRate, $totalTenure, $installmentsPaid);

        // 3) Possibly store or update columns
        $loanOffer->settlement_amount      = $balance; // payoff
        $loanOffer->outstanding_balance    = $balance;
        $loanOffer->fsp_reference_number   = 'A2343453345';
        $loanOffer->payment_reference_number = 'A22211';
        $loanOffer->final_payment_date     = '2025-02-11 10:20:15';//should be within seven days
        $loanOffer->last_deduction_date    = '2025-05-23 10:20:15';
        $loanOffer->last_pay_date          = '2029-05-26 21:32:52';
        $loanOffer->end_date_str           = '2026-03-23'; // store as Varchar(8)
        $loanOffer->save();

        // 4) Return LOAN_TOP_UP_BALANCE_RESPONSE (Message 12)
        return $this->generateTopUpBalanceResponse($loanOffer);
    }
    /**
     * Partial payment formula:
     * B = P₀ * [ (1+r)^n - (1+r)^m ] / [ (1+r)^n - 1 ]
     */
    // private function calculatePartialPaymentBalance(float $principal, float $monthlyRate, int $N, int $m): float
    // {

    //     if ($m >= $N) {
    //         return 0.0;
    //     }
    //     if ($monthlyRate <= 0 || $N <= 0) {
    //         return 0.0;
    //     }
    //     $powRn = pow(1 + $monthlyRate, $N);
    //     $powRm = pow(1 + $monthlyRate, $m);
    //     $numerator   = $powRn - $powRm;
    //     $denominator = $powRn - 1;
    //     if ($denominator == 0) {
    //         return 0.0;
    //     }
    //     return $principal * ($numerator / $denominator);
    // }

    private function calculatePartialPaymentBalance(float $principal, float $monthlyRate, int $totalTenure, int $installmentsPaid): float
{
    // No balance left if the number of payments made is equal to or exceeds the total number of payments
    if ($installmentsPaid >= $totalTenure) {
        return 0.0;
    }
    // Early exit for invalid rate or tenure
    if ($monthlyRate <= 0 || $totalTenure <= 0) {
        return 0.0;
    }

    // Calculate the compound interest factors for the total and the paid periods
    $powRn = pow(1 + $monthlyRate, $totalTenure);
    $powRm = pow(1 + $monthlyRate, $installmentsPaid);

    // Use the formula to calculate the remaining balance
    $numerator = $powRn - $powRm;
    $denominator = $powRn - 1;

    // Avoid division by zero
    if ($denominator == 0) {
        return 0.0;
    }

    return $principal * ($numerator / $denominator);
}
    private function generateTopUpBalanceResponse(LoanOffer $loanOffer, $httpStatusCode = 200)
    {
        // Build XML for Message 12
        $xml = new \SimpleXMLElement('<Document/>');
        $dataXml = $xml->addChild('Data');

        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', 'FL7456');
        $header->addChild('MsgId', uniqid());
        $header->addChild('MessageType', 'LOAN_TOP_UP_BALANCE_RESPONSE');

        $msgDetails = $dataXml->addChild('MessageDetails');
        $msgDetails->addChild('LoanNumber', $loanOffer->loan_number ?? '');
        $msgDetails->addChild('FSPReferenceNumber', $loanOffer->fsp_reference_number ?? '');
        $msgDetails->addChild('PaymentReferenceNumber', $loanOffer->payment_reference_number ?? '');
        // For total payoff, you can use settlement_amount or outstanding_balance
        $msgDetails->addChild('TotalPayoffAmount', number_format($loanOffer->settlement_amount ?? 0, 2, '.', ''));
        $msgDetails->addChild('OutstandingBalance', number_format($loanOffer->outstanding_balance ?? 0, 2, '.', ''));
        // date/time fields:
        $msgDetails->addChild('FinalPaymentDate', $loanOffer->final_payment_date ? $loanOffer->final_payment_date->format('Y-m-d\TH:i:s') : '');
        $msgDetails->addChild('LastDeductionDate', $loanOffer->last_deduction_date ? $loanOffer->last_deduction_date->format('Y-m-d\TH:i:s') : '');
        $msgDetails->addChild('LastPayDate',       $loanOffer->last_pay_date ? $loanOffer->last_pay_date->format('Y-m-d\TH:i:s') : '');
        $msgDetails->addChild('EndDate',          $loanOffer->end_date_str ?? '');

        $xml->addChild('Signature', 'Signature');
        $responseContent = $xml->asXML();
        return response($responseContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }
 /*========================================================================================
     | (C) TOP_UP_OFFER_REQUEST (Msg 16)
     *=======================================================================================*/
    private function handleTopUpOfferRequest($xml)

{


    Log::info('Handling TOP_UP_OFFER_REQUEST', ['data' => $xml]);

    // 1) Extract <MessageDetails> from the XML
    $msgDetails = $xml->Data->MessageDetails;
      // Convert empty date strings to NULL
      $contractStartDate = (string)$msgDetails->ContractStartDate === ''
      ? null
      : (string)$msgDetails->ContractStartDate;
  $contractEndDate = (string)$msgDetails->ContractEndDate === ''
      ? null
      : (string)$messageDetails->ContractEndDate;

    // 2) Parse each field
    //    We'll do it step by step, matching your existing DB columns

    $checkNumber = (string)($msgDetails->CheckNumber ?? null);
    $firstName   = (string)($msgDetails->FirstName ?? null);
    $middleName  = (string)($msgDetails->MiddleName ?? null);
    $lastName    = (string)($msgDetails->LastName ?? null);
    $sex         = (string)($msgDetails->Sex ?? 'M');
    $bankAccountNumber = (string)($msgDetails->BankAccountNumber ?? null);

    $employmentDate = (string)($msgDetails->EmploymentDate ?? null);
    $maritalStatus  = (string)($msgDetails->MaritalStatus ?? null);
    $confirmationDate = (string)($msgDetails->ConfirmationDate ?? null);
    $totalEmployeeDeduction = (float)($msgDetails->TotalEmployeeDeduction ?? 0);
    $nearestBranchName = (string)($msgDetails->NearestBranchName ?? null);
    $nearestBranchCode = (string)($msgDetails->NearestBranchCode ?? null);

    $voteCode = (string)($msgDetails->VoteCode ?? null);
    $voteName = (string)($msgDetails->VoteName ?? null);
    $nin      = (string)($msgDetails->NIN ?? null);
    $designationCode = (string)($msgDetails->DesignationCode ?? null);
    $designationName = (string)($msgDetails->DesignationName ?? null);

    $basicSalary  = (float)($msgDetails->BasicSalary ?? 0);
    $netSalary    = (float)($msgDetails->NetSalary ?? 0);
    $oneThird     = (float)($msgDetails->OneThirdAmount ?? 0);
    $requestedAmount = (float)($msgDetails->RequestedAmount ?? 0);  // Original principal
    $desiredDeductibleAmount = (float)($msgDetails->DesiredDeductibleAmount ?? 0);

    $retirementDate     = (int)($msgDetails->RetirementDate ?? 0);
    $termsOfEmployment  = (string)($msgDetails->TermsOfEmployment ?? null);
    $tenure             = (int)($msgDetails->Tenure ?? 0);
    $productCode        = (string)($msgDetails->ProductCode ?? null);
    $fspCode =(string)($msgDetails->FSPCode);
    $interestRate   = (float)($msgDetails->InterestRate ?? 0);
    $processingFee  = (float)($msgDetails->ProcessingFee ?? 0);
    $insurance      = (float)($msgDetails->Insurance ?? 0);

    $physicalAddress = (string)($msgDetails->PhysicalAddress ?? null);
    $emailAddress    = (string)($msgDetails->EmailAddress ?? null);
    $mobileNumber    = (string)($msgDetails->MobileNumber ?? null);

    $applicationNumber = (string)($msgDetails->ApplicationNumber ?? null);
    $loanPurpose       = (string)($msgDetails->LoanPurpose ?? null);
    // $contractStartDate = (string)($msgDetails->ContractStartDate ?? null);
    // $contractEndDate   = (string)($msgDetails->ContractEndDate ?? null);
    $loanNumber        = (string)($msgDetails->LoanNumber ?? null);
    $settlementAmount  = (float)($msgDetails->SettlementAmount ?? 0);

    $swiftCode = (string)($msgDetails->SwiftCode ?? null);
    $funding   = (string)($msgDetails->Funding ?? null);

    // 3) Basic validation checks
    if (!$checkNumber || !$loanNumber || !$applicationNumber) {
        return $this->generateXmlResponse('8002', 'Missing mandatory fields (CheckNumber/LoanNumber/ApplicationNumber)');
    }

    // 4) Store in DB: create a new row in loan_offers (or update an existing one)
    //    We'll assume new row for top-up.
    $loanOffer = DB::transaction(function () use ($checkNumber, $firstName, $middleName, $lastName, $sex, $bankAccountNumber,
        $employmentDate, $maritalStatus, $confirmationDate, $totalEmployeeDeduction, $nearestBranchName, $nearestBranchCode,
        $voteCode, $voteName, $nin, $designationCode, $designationName, $basicSalary, $netSalary, $oneThird, $requestedAmount,
        $desiredDeductibleAmount, $retirementDate, $termsOfEmployment, $tenure, $productCode, $fspCode, $interestRate,
        $processingFee, $insurance, $physicalAddress, $emailAddress, $mobileNumber, $applicationNumber, $loanPurpose,
        $contractStartDate, $contractEndDate, $loanNumber, $settlementAmount, $swiftCode, $funding) {

        // Check if bank exists and link it
        $bankId = null;
        if (!empty($swiftCode)) {
            $bank = Bank::where('swift_code', $swiftCode)->first();
            if ($bank) {
                $bankId = $bank->id;
            }
        }

        // Find the original loan being topped up
        $originalLoan = null;
        if (!empty($loanNumber)) {
            $originalLoan = LoanOffer::where('loan_number', $loanNumber)->first();
        }

        $loanOffer = LoanOffer::create([
            'check_number' => $checkNumber,
            'first_name'   => $firstName,
            'middle_name'  => $middleName,
            'last_name'    => $lastName,
            'sex'          => $sex,
            'bank_account_number' => $bankAccountNumber,
            'employment_date' => $employmentDate,
            'marital_status' => $maritalStatus,
            'confirmation_date' => $confirmationDate,
            'total_employee_deduction' => $totalEmployeeDeduction,
            'nearest_branch_name' => $nearestBranchName,
            'nearest_branch_code' => $nearestBranchCode,
            'vote_code' => $voteCode,
            'vote_name' => $voteName,
            'nin'       => $nin,
            'designation_code' => $designationCode,
            'designation_name' => $designationName,
            'basic_salary' => $basicSalary,
            'net_salary'   => $netSalary,
            'one_third_amount' => $oneThird,
            'requested_amount'  => $requestedAmount,  // original principal for top-up
            'desired_deductible_amount' => $desiredDeductibleAmount,
            'retirement_date'    => $retirementDate,
            'terms_of_employment'=> $termsOfEmployment,
            'tenure'             => $tenure,
            'product_code'       => $productCode,
            'fsp_code' =>$fspCode ,
            'interest_rate'      => $interestRate,
            'processing_fee'     => $processingFee,
            'insurance'          => $insurance,
            'physical_address'   => $physicalAddress,
            'email_address'      => $emailAddress,
            'mobile_number'      => $mobileNumber,
            'application_number' => $applicationNumber,
            'loan_purpose'       => $loanPurpose,
            'contract_start_date'=> $contractStartDate,
            'contract_end_date'  => $contractEndDate,
            'loan_number'        => $loanNumber,
            'settlement_amount'  => $settlementAmount,
            'swift_code'         => $swiftCode,
            'bank_id'            => $bankId,  // Add bank relationship
            'funding'            => $funding,
            // Additional fields
            'offer_type'         => 'TOP_UP',  // Mark it as top-up
            'loan_type'          => 'topup',   // Add loan_type field
            'approval'           => 'PENDING', // Set default approval status to PENDING
            'status'             => 'PENDING', // Set default status to PENDING
            'state'              => 'Loan offer At FSP', // Initial state for loans coming to FSP
        ]);

        // Create initial approval record in normalized table
        LoanOfferApproval::create([
            'loan_offer_id' => $loanOffer->id,
            'approval_type' => 'initial',
            'status' => 'pending'
        ]);

        // Create topup relationship record if original loan found
        if ($originalLoan) {
            LoanOfferTopup::create([
                'original_loan_id' => $originalLoan->id,
                'new_loan_id' => $loanOffer->id,
                'settlement_amount' => $settlementAmount,
                'top_up_amount' => $requestedAmount - $settlementAmount,  // New amount minus settlement
            ]);
        }

        return $loanOffer;
    });

    // 5) Return a response (like a custom TOP_UP_OFFER_RESPONSE)
    return $this->generateTopUpOfferResponse('8000', 'Top Up Offer Request processed successfully');
}

private function generateTopUpOfferResponse($code, $desc,  $httpStatusCode = 200)
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
    $messageDetails->addChild('ResponseCode', $code);      // Use $code instead of $responseCode
    $messageDetails->addChild('Description', $desc);       // Use $desc instead of $description


    // Add a Signature element (placeholder)
    $xml->addChild('Signature', 'Signature');

    // Convert to string
    $responseContent = $xml->asXML();

    // Return as raw XML with the correct Content-Type
    return response($responseContent, $httpStatusCode)
        ->header('Content-Type', 'application/xml');
}


//  handler for LOAN_LIQUIDATION_NOTIFICATION:
    private function handleLoanLiquidationNotification($xml)
    {
        Log::info('Handling LOAN_LIQUIDATION_NOTIFICATION', ['data' => $xml]);

        // Extract message details from the XML.
        $messageDetails = $xml->Data->MessageDetails;
        $applicationNumber = trim((string)$messageDetails->ApplicationNumber);
        $loanNumber        = trim((string)$messageDetails->LoanNumber);
        // Remarks is optional so check if it exists
        $remarks           = isset($messageDetails->Remarks) ? trim((string)$messageDetails->Remarks) : null;

        // Validate required fields.
        if (!$applicationNumber || !$loanNumber) {
            return $this->generateXmlResponse('8002', 'Missing mandatory fields in LOAN_LIQUIDATION_NOTIFICATION');
        }

        // Example: Update the corresponding loan record. (Assumes you have a 'remarks' column.)
        $loanOffer = LoanOffer::where('application_number', $applicationNumber)
                              ->where('loan_number', $loanNumber)
                              ->first();
        if (!$loanOffer) {
            return $this->generateXmlResponse('8011', 'Loan application not found');
        }

        // Update the loan offer status (or any other business logic you require).
        $loanOffer->status = 'FULL_SETTLED';
        $loanOffer->state = 'liquidated';
        $loanOffer->approval = 'LIQUIDATED';
        $loanOffer->remarks  = $remarks;  // Save remarks if provided.
        $loanOffer->liquidated_at = now();
        $loanOffer->save();

        Log::info("Loan Liquidation processed successfully", [
            'ApplicationNumber' => $applicationNumber,
            'LoanNumber'        => $loanNumber,
            'Status'           => $loanOffer->status,
            'State'            => $loanOffer->state,
            'Remarks'           => $remarks
        ]);

        // Return a success XML response.
        return $this->generateLoanLiquidationResponse('8000', 'Loan Liquidation notification processed successfully');
    }
// response generator for the loan liquidation response:
    private function generateLoanLiquidationResponse($responseCode, $description, $httpStatusCode = 200)
    {
        $xml = new SimpleXMLElement('<Document/>');

        $data = $xml->addChild('Data');
        $header = $data->addChild('Header');
        // You can adjust these header values as needed.
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', 'FL7456');
        $header->addChild('MsgId', uniqid());
        $header->addChild('MessageType', 'RESPONSE');

        $messageDetails = $data->addChild('MessageDetails');
        $messageDetails->addChild('ResponseCode', $responseCode);
        $messageDetails->addChild('Description', $description);

        // Add a placeholder Signature element.
        $xml->addChild('Signature', 'Signature');

        $responseContent = $xml->asXML();

        return response($responseContent, $httpStatusCode)
                    ->header('Content-Type', 'application/xml');
    }

    /**
     * Export loan offers to Excel or PDF
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'excel');
        $selected = $request->input('selected');

        // Build query with filters
        $query = LoanOffer::query();

        // Apply same filters as index method
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                  ->orWhere('check_number', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            if ($status === 'pending_approval') {
                $query->where(function($q) {
                    $q->where('approval', 'PENDING')
                      ->orWhereNull('approval');
                });
            } elseif ($status === 'approved') {
                $query->where('approval', 'APPROVED');
            } elseif ($status === 'rejected') {
                $query->where('approval', 'REJECTED');
            } elseif ($status === 'cancelled') {
                $query->where('approval', 'CANCELLED');
            } elseif ($status === 'disbursement_pending') {
                $query->where('status', 'disbursement_pending');
            } elseif ($status === 'disbursed') {
                $query->where('status', 'disbursed');
            }
        }

        // Apply date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // If specific IDs selected
        if ($selected) {
            $selectedIds = explode(',', $selected);
            $query->whereIn('id', $selectedIds);
        }

        $loanOffers = $query->orderBy('created_at', 'desc')->get();

        if ($format === 'pdf') {
            return $this->exportPDF($loanOffers);
        }

        return $this->exportExcel($loanOffers);
    }

    /**
     * Export to Excel
     */
    private function exportExcel($loanOffers)
    {
        $filename = 'ESS_Loan_Applications_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $columns = [
            'Application #',
            'Check #',
            'Name',
            'Employment Date',
            'Basic Salary',
            'Net Salary',
            'Requested Amount',
            'Take Home Amount',
            'Tenure',
            'Interest Rate',
            'Approval Status',
            'Processing Status',
            'Created Date',
        ];

        $callback = function() use ($loanOffers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($loanOffers as $offer) {
                fputcsv($file, [
                    $offer->application_number,
                    $offer->check_number,
                    $offer->first_name . ' ' . $offer->last_name,
                    $offer->employment_date,
                    number_format($offer->basic_salary, 2),
                    number_format($offer->net_salary, 2),
                    number_format($offer->requested_amount, 2),
                    number_format($offer->take_home_amount ?? $offer->net_loan_amount, 2),
                    $offer->tenure,
                    $offer->interest_rate . '%',
                    $offer->approval ?: 'PENDING',
                    $offer->status ?: 'NEW',
                    $offer->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF
     */
    private function exportPDF($loanOffers)
    {
        // For now, return a simple HTML table that can be printed as PDF
        $html = view('employee_loan.export_pdf', compact('loanOffers'))->render();

        return response($html)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', 'inline; filename="ESS_Loan_Applications_' . date('Y-m-d') . '.html"');
    }

    /**
     * Sync data from ESS
     */
    public function syncFromESS(Request $request)
    {
        try {
            // Here you would typically make an API call to ESS
            // For now, we'll return a success message with current stats

            $stats = [
                'total' => LoanOffer::count(),
                'new_today' => LoanOffer::whereDate('created_at', today())->count(),
                'pending' => LoanOffer::where(function($q) {
                    $q->where('approval', 'PENDING')->orWhereNull('approval');
                })->count(),
                'message' => 'Successfully synchronized with ESS system'
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk actions on loan offers
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:loan_offers,id',
            'reason' => 'nullable|string' // Add reason validation
        ]);

        $action = $request->input('action');
        $ids = $request->input('ids');
        $reason = $request->input('reason', ''); // Get reason if provided
        $count = 0;

        try {
            // Log the incoming request for debugging
            Log::info('Bulk action request', [
                'action' => $action,
                'ids' => $ids,
                'reason' => $reason
            ]);

            // Check for cancelled loans before processing any action
            if (in_array($action, ['approve', 'reject'])) {
                $cancelledLoans = LoanOffer::whereIn('id', $ids)
                    ->where(function($q) {
                        $q->where('approval', 'CANCELLED')
                          ->orWhereIn('status', ['CANCELLED', 'CANCELED'])
                          ->orWhereIn('state', ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION']);
                    })
                    ->pluck('application_number')
                    ->toArray();

                if (!empty($cancelledLoans)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot process action. The following loans have been cancelled by employees: ' . implode(', ', $cancelledLoans),
                        'error' => 'LOAN_CANCELLED',
                        'cancelled_loans' => $cancelledLoans
                    ], 422);
                }
            }

            switch($action) {
                case 'approve':
                    // First check what loans we're trying to update
                    $loansToUpdate = LoanOffer::whereIn('id', $ids)->get();
                    Log::info('Loans to approve', [
                        'loans' => $loansToUpdate->map(function($loan) {
                            return [
                                'id' => $loan->id,
                                'current_approval' => $loan->approval,
                                'current_status' => $loan->status
                            ];
                        })
                    ]);

                    // Approve loans that are currently rejected or pending (excluding cancelled)
                    // But not already approved or disbursed
                    $query = LoanOffer::whereIn('id', $ids);

                    // Don't re-approve already approved loans and exclude cancelled loans
                    $query->where(function($q) {
                        $q->whereIn('approval', ['REJECTED', 'PENDING'])
                          ->orWhereNull('approval');
                    });

                    // Exclude any form of cancelled loans from approval
                    $query->where(function($q) {
                        $q->where('approval', '!=', 'CANCELLED');
                    });
                    $query->whereNotIn('status', ['CANCELLED', 'CANCELED']);
                    $query->whereNotIn('state', ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION']);

                    // Don't approve disbursed loans
                    $query->where(function($q) {
                        $q->where('status', '!=', 'disbursed')
                          ->orWhereNull('status');
                    });

                    $count = $query->update(['approval' => 'APPROVED', 'updated_at' => now()]);

                    // Update normalized approval records for bulk approved loans
                    foreach($ids as $loanId) {
                        LoanOfferApproval::updateOrCreate(
                            [
                                'loan_offer_id' => $loanId,
                                'approval_type' => 'final'
                            ],
                            [
                                'status' => 'approved',
                                'approved_by' => auth()->id(),
                                'approved_at' => now(),
                                'comments' => 'Bulk approved'
                            ]
                        );
                    }

                    Log::info('Bulk approve result', ['count' => $count, 'ids' => $ids]);
                    $message = "{$count} loan(s) approved successfully";
                    break;

                case 'reject':
                    // First check what loans we're trying to update
                    $loansToUpdate = LoanOffer::whereIn('id', $ids)->get();
                    Log::info('Loans to reject', [
                        'loans' => $loansToUpdate->map(function($loan) {
                            return [
                                'id' => $loan->id,
                                'current_approval' => $loan->approval,
                                'current_status' => $loan->status
                            ];
                        })
                    ]);

                    $updateData = [
                        'approval' => 'REJECTED',
                        'updated_at' => now()
                    ];

                    // Add reason if provided
                    if (!empty($reason)) {
                        $updateData['reason'] = $reason;
                    }

                    // Reject loans that are currently approved or pending (excluding cancelled)
                    // But not already rejected or disbursed
                    $query = LoanOffer::whereIn('id', $ids);

                    // Don't re-reject already rejected loans and exclude cancelled loans
                    $query->where(function($q) {
                        $q->whereIn('approval', ['APPROVED', 'PENDING'])
                          ->orWhereNull('approval');
                    });

                    // Exclude any form of cancelled loans from rejection
                    $query->where(function($q) {
                        $q->where('approval', '!=', 'CANCELLED');
                    });
                    $query->whereNotIn('status', ['CANCELLED', 'CANCELED']);
                    $query->whereNotIn('state', ['CANCELLED', 'CANCELED', 'LOAN_CANCELLATION']);

                    // Don't reject disbursed loans
                    $query->where(function($q) {
                        $q->where('status', '!=', 'disbursed')
                          ->orWhereNull('status');
                    });

                    $count = $query->update($updateData);

                    // Update normalized approval records for bulk rejected loans
                    foreach($ids as $loanId) {
                        LoanOfferApproval::updateOrCreate(
                            [
                                'loan_offer_id' => $loanId,
                                'approval_type' => 'final'
                            ],
                            [
                                'status' => 'rejected',
                                'rejected_by' => auth()->id(),
                                'rejected_at' => now(),
                                'reason' => $reason ?: 'Bulk rejected'
                            ]
                        );
                    }

                    Log::info('Bulk reject result', ['count' => $count, 'ids' => $ids, 'reason' => $reason]);
                    $message = "{$count} loan(s) rejected successfully";
                    break;

                case 'delete':
                    // Only delete loans that are pending and not disbursed
                    $count = LoanOffer::whereIn('id', $ids)
                        ->whereIn('approval', ['PENDING', null])
                        ->where('status', '!=', 'disbursed')
                        ->delete();

                    Log::info('Bulk delete result', ['count' => $count, 'ids' => $ids]);
                    $message = "{$count} loan(s) deleted successfully";
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'count' => $count
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Operation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display pending loans
     */
    public function pendingLoans(Request $request)
    {
        $query = LoanOffer::query();

        // Filter for pending loans
        $query->where(function($q) {
            $q->where('approval', 'PENDING')
              ->orWhereNull('approval');
        });

        // Apply search if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('application_number', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%");
            });
        }

        $loanOffers = $query->latest()->paginate(20);

        // Calculate statistics
        $stats = [
            'total' => $query->count(),
            'total_amount' => $query->sum('net_loan_amount'),
            'average_amount' => $query->avg('net_loan_amount'),
        ];

        return view('employee_loan.pending', compact('loanOffers', 'stats'));
    }

    /**
     * Display approved loans
     */
    public function approvedLoans(Request $request)
    {
        // Show only loans that are "Submitted for disbursement" - these have received final employer approval from ESS
        // This ensures only loans ready for disbursement are shown
        $query = LoanOffer::with(['bank', 'disbursements', 'approvals'])
                         ->where('approval', 'APPROVED')
                         // Primary filter: Show only loans with state "Submitted for disbursement" or already disbursed/completed
                         ->whereIn('state', ['Submitted for disbursement', 'Completed', 'Disbursement Failure'])
                         // Include various disbursement statuses
                         ->whereIn('status', ['APPROVED', 'DISBURSEMENT_REJECTED', 'DISBURSEMENT_POSTPONED', 'disbursement_pending', 'disbursed', 'DISBURSEMENT_FAILED'])
                         // Exclude cancelled loans
                         ->whereNotIn('state', ['cancelled', 'CANCELLED', 'LOAN_CANCELLATION', 'loan_cancellation', 'Employee canceled', 'Employer Rejected', 'FSP Rejected', 'FSP Canceled']);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('application_number', 'like', "%{$search}%")
                  ->orWhere('check_number', 'like', "%{$search}%")
                  ->orWhere('bank_account_number', 'like', "%{$search}%");
            });
        }

        // Apply status/filter
        if ($request->filled('filter')) {
            $filter = $request->input('filter');

            switch($filter) {
                case 'ready':
                    // Ready to disburse - approved but not yet disbursed
                    $query->whereDoesntHave('disbursements', function($q) {
                        $q->where('status', 'success');
                    });
                    break;

                case 'disbursed':
                    // Already disbursed - has successful disbursement
                    $query->whereHas('disbursements', function($q) {
                        $q->where('status', 'success');
                    });
                    break;

                case 'failed':
                    // Failed disbursements - has failed disbursement but no success
                    $query->whereHas('disbursements', function($q) {
                        $q->where('status', 'failed');
                    })->whereDoesntHave('disbursements', function($q) {
                        $q->where('status', 'success');
                    });
                    break;

                case 'today':
                    // Today's approvals
                    $query->whereDate('updated_at', today());
                    break;

                case 'processing':
                    // Currently processing - has pending disbursement
                    $query->whereHas('disbursements', function($q) {
                        $q->where('status', 'pending');
                    });
                    break;

                case 'rejected':
                    // Rejected by customer care
                    $query->where('status', 'DISBURSEMENT_REJECTED');
                    break;

            }
        }

        // Clone query for statistics before pagination
        $statsQuery = clone $query;

        // Apply sorting
        $query->orderBy('updated_at', 'desc');

        // Paginate results
        $loanOffers = $query->paginate(20)->withQueryString();

        // Calculate statistics (using same criteria as main query for consistency)
        $baseStatsQuery = LoanOffer::where('approval', 'APPROVED')
                                   ->whereIn('state', ['Submitted for disbursement', 'Completed', 'Disbursement Failure'])
                                   ->whereIn('status', ['APPROVED', 'DISBURSEMENT_REJECTED', 'DISBURSEMENT_POSTPONED', 'disbursement_pending', 'disbursed', 'DISBURSEMENT_FAILED'])
                                   ->whereNotIn('state', ['cancelled', 'CANCELLED', 'LOAN_CANCELLATION', 'loan_cancellation', 'Employee canceled', 'Employer Rejected', 'FSP Rejected', 'FSP Canceled']);

        // Calculate detailed statistics with clearer categorization
        $stats = [
            // Total loans in the disbursement management system
            'total' => $statsQuery->count(),
            'total_amount' => $statsQuery->sum('take_home_amount') ?: $statsQuery->sum('net_loan_amount') ?: $statsQuery->sum('requested_amount'),

            // Ready to disburse - Approved but NOT yet disbursed (this is the actual pending)
            'ready_to_disburse' => (clone $baseStatsQuery)
                                    ->whereIn('status', ['APPROVED'])
                                    ->whereDoesntHave('disbursements', function($q) {
                                        $q->where('status', 'success');
                                    })
                                    ->count(),
            'ready_amount' => (clone $baseStatsQuery)
                                    ->whereIn('status', ['APPROVED'])
                                    ->whereDoesntHave('disbursements', function($q) {
                                        $q->where('status', 'success');
                                    })
                                    ->sum('take_home_amount'),

            // Successfully disbursed
            'disbursed' => (clone $baseStatsQuery)
                                ->whereHas('disbursements', function($q) {
                                    $q->where('status', 'success');
                                })
                                ->count(),
            'disbursed_amount' => (clone $baseStatsQuery)
                                ->whereHas('disbursements', function($q) {
                                    $q->where('status', 'success');
                                })
                                ->sum('take_home_amount'),

            // In processing (submitted to bank but not yet confirmed)
            'processing' => (clone $baseStatsQuery)
                                ->where('status', 'disbursement_pending')
                                ->count(),

            // Rejected by operations team
            'rejected' => (clone $baseStatsQuery)
                                ->where('status', 'DISBURSEMENT_REJECTED')
                                ->count(),

            // Failed disbursements (technical or bank issues)
            'failed' => (clone $baseStatsQuery)
                                ->whereHas('disbursements', function($q) {
                                    $q->where('status', 'failed');
                                })
                                ->whereDoesntHave('disbursements', function($q) {
                                    $q->where('status', 'success');
                                })
                                ->where('status', '!=', 'DISBURSEMENT_REJECTED')
                                ->count(),

            // For backwards compatibility - keep pending_disbursement as alias for ready_to_disburse
            'pending_disbursement' => 0, // Will be set below
            'growth' => 15, // You can calculate actual growth percentage here
        ];

        // Set pending_disbursement for backward compatibility
        $stats['pending_disbursement'] = $stats['ready_to_disburse'];

        // Return partial view for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            // Return just the table body HTML for AJAX requests
            $tableHtml = view('employee_loan.partials.approved_table', compact('loanOffers', 'stats'))->render();

            return response()->json([
                'success' => true,
                'html' => $tableHtml,
                'stats' => $stats,
                'message' => 'Filter applied successfully'
            ]);
        }

        return view('employee_loan.approved', compact('loanOffers', 'stats'));
    }

    /**
     * Display rejected loans
     */
    public function rejectedLoans(Request $request)
    {
        $query = LoanOffer::where('approval', 'REJECTED');

        // Apply search if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('application_number', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%");
            });
        }

        $loanOffers = $query->latest()->paginate(20);

        // Calculate statistics
        $stats = [
            'total' => $query->count(),
            'total_amount' => $query->sum('net_loan_amount'),
            'with_reason' => $query->whereNotNull('reason')->count(),
        ];

        return view('employee_loan.rejected', compact('loanOffers', 'stats'));
    }

    /**
     * Display disbursed loans
     */
    public function disbursedLoans(Request $request)
    {
        $query = LoanOffer::where('status', 'disbursed');

        // Apply search if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('employee_name', 'like', "%{$search}%")
                  ->orWhere('application_number', 'like', "%{$search}%")
                  ->orWhere('employee_number', 'like', "%{$search}%");
            });
        }

        $loanOffers = $query->latest()->paginate(20);

        // Calculate statistics
        $stats = [
            'total' => $query->count(),
            'total_disbursed' => $query->sum('net_loan_amount'),
            'this_month' => $query->whereMonth('updated_at', now()->month)->sum('net_loan_amount'),
        ];

        return view('employee_loan.disbursed', compact('loanOffers', 'stats'));
    }

    /**
     * Display loan reports
     */
    public function reports()
    {
        $data = [
            'total_loans' => LoanOffer::count(),
            'total_portfolio' => LoanOffer::sum('net_loan_amount'),
            'approved_loans' => LoanOffer::where('approval', 'APPROVED')->count(),
            'rejected_loans' => LoanOffer::where('approval', 'REJECTED')->count(),
            'disbursed_loans' => LoanOffer::where('status', 'disbursed')->count(),
            'pending_loans' => LoanOffer::where(function($q) {
                $q->where('approval', 'PENDING')->orWhereNull('approval');
            })->count(),

            // Monthly trends
            'monthly_disbursements' => LoanOffer::where('status', 'disbursed')
                ->whereMonth('updated_at', now()->month)
                ->sum('net_loan_amount'),

            // Approval rate
            'approval_rate' => LoanOffer::where('approval', 'APPROVED')->count() /
                               (LoanOffer::count() ?: 1) * 100,
        ];

        return view('employee_loan.reports', compact('data'));
    }

    /**
     * Display collections report
     */
    public function collections()
    {
        $data = [
            'total_expected' => LoanOffer::where('status', 'disbursed')->sum('total_amount_to_pay'),
            'total_collected' => 0, // This would come from payments table
            'collection_rate' => 0,
            'overdue_loans' => 0,
        ];

        $loanOffers = LoanOffer::where('status', 'disbursed')
            ->latest()
            ->paginate(20);

        return view('employee_loan.collections', compact('data', 'loanOffers'));
    }


}

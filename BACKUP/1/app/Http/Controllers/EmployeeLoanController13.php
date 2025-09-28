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

        $loanOffer->approval= strtoupper($approval);
        $loanOffer->reason = $reason;
        $loanOffer->fsp_reference_number = $fspReferenceNumber;
        $loanOffer->loan_number = $loanNumber;
        $loanOffer->save();

        Log::info('Loan Final Approval processed', [
            'ApplicationNumber' => $applicationNumber,
            'ApprovalStatus' => $approval,
            'FSPReferenceNumber' => $fspReferenceNumber,
            'LoanNumber' => $loanNumber
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

        // Update loan offer status to 'CANCELLED' if not already cancelled or disbursed
        if (!in_array($loanOffer->approval, ['CANCELLED', 'DISBURSED'])) {
            $loanOffer->approval = 'CANCELLED';
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

public function indexLoanOffers(Request $request)
{
    // Start a new query
    $query = LoanOffer::query();

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

    // Handle the status filter
    if ($request->has('status') && $request->input('status')) {
        $query->where('status', $request->input('status'));
    }

    // Handle date filters
    if ($request->has('date_from') && $request->input('date_from')) {
        $query->whereDate('created_at', '>=', $request->input('date_from'));
    }

    if ($request->has('date_to') && $request->input('date_to')) {
        $query->whereDate('created_at', '<=', $request->input('date_to'));
    }

    // Order by the most recent and paginate
    $perPage = $request->input('per_page', 25); // Default to 25 items per page
    $loanOffers = $query->latest()->paginate($perPage)->withQueryString(); // withQueryString preserves filters

    // Calculate statistics for dashboard cards
    $pendingCount = LoanOffer::where('approval', 'PENDING')
                            ->orWhereNull('approval')
                            ->count();

    $approvedCount = LoanOffer::where('approval', 'APPROVED')
                              ->where(function($q) {
                                  $q->whereNull('status')
                                    ->orWhere('status', '!=', 'disbursed')
                                    ->orWhere('status', '!=', 'disbursement_pending');
                              })
                              ->count();

    $pendingNMBCount = LoanOffer::where('status', 'disbursement_pending')->count();

    // Calculate total disbursed today
    $totalDisbursed = LoanOffer::where('status', 'disbursed')
                               ->whereDate('updated_at', today())
                               ->sum('total_amount_to_pay');

    return view('employee_loan.index', compact(
        'loanOffers',
        'pendingCount',
        'approvedCount',
        'pendingNMBCount',
        'totalDisbursed'
    ));
}

    public function editLoanOffer($id)
    {
       $loanOffer = LoanOffer::with(['callbacks', 'paymentDestination'])->findOrFail($id);
       $destinations = PaymentDestination::orderBy('name')->get()->groupBy('type');

       // Use the enhanced view for better UI/UX
       // You can switch back to 'employee_loan.edit' for the simpler version
      return view('employee_loan.edit_enhanced', compact('loanOffer','destinations'));

    // return view('employee_loan.edit_urasaccos', compact('loanOffer','destinations'));
    }





  public function updateLoanOffer(Request $request, $id)
    {
        // dd('THIS IS THE NEW CODE. EXECUTION STOPPED.');
        $loanOffer = LoanOffer::findOrFail($id);
         $destinations = PaymentDestination::orderBy('name')->get()->groupBy('type');
        $message = "LoanOffer updated successfully.";

        if ($request->input('status') === 'SUBMITTED_FOR_DISBURSEMENT') {

            // --- NMB DISBURSEMENT PATH ---
            Log::info("Initiating NMB disbursement for LoanOffer ID: {$id}");
            $nmbResponse = $this->nmbService->disburseLoan($loanOffer);

            if (isset($nmbResponse['body']['payload']['RespStatus']) && $nmbResponse['body']['payload']['RespStatus'] === 'Success') {

                $batchId = $nmbResponse['body']['payload']['RespHeader']['BatchId'];
                $loanOffer->status = 'disbursement_pending';
                $loanOffer->state = 'submitted_for_disbursement';
                $loanOffer->nmb_batch_id = $batchId;
                $loanOffer->save();

                Log::info("NMB batch submitted successfully.", ['id' => $id, 'batch_id' => $batchId]);
                $notifyDisbursementResponse = $this->sendDisbursementNotification($loanOffer, 'Submitted to bank for processing.');
                $message = "SUCCESS: Disbursement request sent to NMB. Batch ID: {$batchId}.";

            } else {
                $errorMessage = $nmbResponse['body']['message']
                              ?? ($nmbResponse['body']['payload']['respHeader']['ErrorDesc']
                              ?? 'Unknown error from NMB.');

                $loanOffer->status = 'DISBURSEMENT_FAILED';
                $loanOffer->state = 'process_disbursement_failure';
                $loanOffer->reason = $errorMessage;
                $loanOffer->save();

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
           $loanOffer->payment_destination_id = $request->input('payment_destination_id', $loanOffer->payment_destination_id);
            if ($request->has('status') && $request->input('status')) {
                $newStatus = $request->input('status');
                $loanOffer->status = $newStatus;
                switch ($newStatus) {
                    case 'DISBURSEMENT_FAILED': $loanOffer->state = 'process_disbursement_failure'; break;
                    case 'APPROVED': $loanOffer->state = 'approved'; break;
                    case 'FULL_SETTLED': $loanOffer->state = 'liquidated'; break;
                    default: $loanOffer->state = $newStatus;
                }
            }
            $loanOffer->save();
            Log::info("LoanOffer updated via regular save.", ['id' => $id, 'status' => $loanOffer->status]);

            // This adds your original ESS notification logic back in for non-disbursement cases
            if ($loanOffer->status === 'FULL_SETTLED') {
                $remarks = $request->input('remark', 'Loan fully settled');
                // Assuming sendLiquidationNotification exists from your original code
                // $notifyResponse = $this->sendLiquidationNotification($loanOffer, $remarks);
                $message = "Loan fully settled."; // .$notifyResponse;
            } else {
                // Default notification for initial approval, etc.
                $notifyResponse = $this->notifyEssOnInitialApproval($loanOffer->id);
                $message = "Loan Offer details saved. Approval Notification: " . $notifyResponse;
            }
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => $message]);
        }

        return redirect()->route('loan-offers.edit', $id)->with('status', $message);
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
            Log::info('Loan disbursement CONFIRMED by NMB callback.', ['loan_id' => $loanOffer->id, 'batch_id' => $batchId]);
        } else {
            $failureReason = $firstRecord['statusDesc'] ?? 'Transaction failed at NMB.';
            $loanOffer->update([
                'status' => 'DISBURSEMENT_FAILED',
                'state' => 'process_disbursement_failure',
                'reason' => $failureReason,
            ]);
            Log::error('Loan disbursement FAILED via NMB callback.', ['loan_id' => $loanOffer->id, 'batch_id' => $batchId, 'reason' => $failureReason]);
        }
    } else {
        // Handle cases where the batch fails without individual records
        $failureReason = $request->input('paymentStatus.respHeader.failureReason', 'Batch failed without processing records.');
        $callback->final_status = 'failed';
        $callback->status_description = $failureReason;
        $loanOffer->update([
            'status' => 'DISBURSEMENT_FAILED',
            'state' => 'process_disbursement_failure',
            'reason' => $failureReason,
        ]);
        Log::error('NMB callback for Batch ID: ' . $batchId . ' contained no fileRecords. Batch failed.', ['reason' => $failureReason]);
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

    public function notifyEssOnInitialApproval($loanOfferId)
{
    $loanOffer = LoanOffer::find($loanOfferId);
    if (!$loanOffer) {
        return 'LoanOffer not found'; // Consider throwing an exception here instead
    }

    $dom = new \DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = false;

    $document = $dom->createElement('Document');
    $dom->appendChild($document);

    $data = $dom->createElement('Data');
    $document->appendChild($data);

    $header = $dom->createElement('Header');
    $data->appendChild($header);
    $header->appendChild($dom->createElement('Sender', 'URA_SACCOS_LTD_LOAN'));
    $header->appendChild($dom->createElement('Receiver', 'ESS_UTUMISHI'));
    $header->appendChild($dom->createElement('FSPCode', 'FL7456'));
    $header->appendChild($dom->createElement('MsgId', uniqid('fsp_', true)));
    $header->appendChild($dom->createElement('MessageType', 'LOAN_INITIAL_APPROVAL_NOTIFICATION'));

    $messageDetails = $dom->createElement('MessageDetails');
    $data->appendChild($messageDetails);

    $messageDetails->appendChild($dom->createElement('ApplicationNumber', $loanOffer->application_number));
    $messageDetails->appendChild($dom->createElement('Reason', $loanOffer->reason ?? 'Ok'));
    $messageDetails->appendChild($dom->createElement('FSPReferenceNumber', $loanOffer->fsp_reference_number ?? ''));
    $messageDetails->appendChild($dom->createElement('LoanNumber', $loanOffer->loan_number ?? ''));
    $messageDetails->appendChild($dom->createElement('TotalAmountToPay', $loanOffer->total_amount_to_pay ?? '2500000.58'));
    $messageDetails->appendChild($dom->createElement('OtherCharges', $loanOffer->other_charges ?? '2500.05'));
    $messageDetails->appendChild($dom->createElement('Approval', strtoupper($loanOffer->approval)));

    $dataC14N = $data->C14N();
    $privateKey = file_get_contents('/home/crm/emkopo.key');
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
    $client = new Client();
    try {
        $response = $client->post('http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
            'headers' => ['Content-Type' => 'application/xml'],
            'body' => $xmlContent,
        ]);
        $statusCode = $response->getStatusCode();
        $body = (string)$response->getBody();

        Log::info("LOAN_INITIAL_APPROVAL_NOTIFICATION posted OK for #{$loanOfferId}", ['response' => $body]);
        if ($statusCode == 200) {
            return "Notified ESS successfully. ESS response: {$body}";
        } else {
            return "ESS responded with code {$statusCode}. Response: {$body}";
        }
    } catch (\Exception $e) {
        Log::error('Error sending LOAN_INITIAL_APPROVAL_NOTIFICATION: ' . $e->getMessage());
        return "Error sending XML: " . $e->getMessage();
    }
}




public function notifyEssOfDisbursementFailure($loanOfferId, $reason)
{
    $loanOffer = LoanOffer::find($loanOfferId);
    if (!$loanOffer) {
        Log::error("LoanOffer not found for ID: {$loanOfferId}");
        return 'LoanOffer not found';
    }

    // Validate Loan State
    if ($loanOffer->state !== 'process_disbursement_failure') { // Adjust as per ESS's expected state
        Log::warning("LoanOffer ID: {$loanOfferId} is not in the correct state for disbursement failure notification. Current state: {$loanOffer->state}");
        return 'LoanOffer not in the correct state for disbursement failure notification';
    }

    // Validate Reason Field
    $acceptableReasons = [
        'insufficient_funds',
        'technical_error',
        'customer_request',
        'other',
        // Add other valid reasons as per ESS's specifications
    ];

    if (!in_array(strtolower($reason), $acceptableReasons)) {
        Log::warning("Invalid reason provided for LoanOffer ID: {$loanOfferId}: {$reason}");
        return 'Invalid reason provided for disbursement failure notification';
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
    $messageDetails->appendChild($dom->createElement('Reason', $reason));

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

    // Sending the XML to ESS
    $client = new Client();
    try {
        Log::info("Sending disbursement failure notification to ESS for LoanOffer ID: {$loanOfferId}");
        $response = $client->post('http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
            'headers' => ['Content-Type' => 'application/xml'],
            'body' => $xmlContent,
        ]);
        $statusCode = $response->getStatusCode();
        $body = (string) $response->getBody();

        Log::info("LOAN_DISBURSEMENT_FAILURE_NOTIFICATION sent to ESS for #{$loanOfferId}", ['response' => $body]);
        return $statusCode == 200 ? "Notified ESS successfully. ESS response: {$body}" : "ESS responded with code {$statusCode}. Response: {$body}";
    } catch (\Exception $e) {
        Log::error('Error sending LOAN_DISBURSEMENT_FAILURE_NOTIFICATION: ' . $e->getMessage(), ['LoanOfferID' => $loanOfferId]);
        return "Error sending XML: " . $e->getMessage();
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
        $response = $client->post('http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
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
            $response = $client->post('http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
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


// public function indexLoanOffers()
// {
//     // Fetch all records - or filter, or paginate, if needed
//     $loanOffers = LoanOffer::orderBy('id', 'desc')->paginate(20);

//     // Return them to a Blade template
//     return view('employee_loan.index', compact('loanOffers'));
// }



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
        'funding'            => $funding,
        // Additional fields
        'offer_type'         => 'TOP_UP',  // Mark it as top-up
        'approval'           => 'PENDING', // you can store a default approval status


    ]);

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
        $loanOffer->approval = 'LIQUIDATED';
        $loanOffer->remarks  = $remarks;  // Save remarks if provided.
        $loanOffer->save();

        Log::info("Loan Liquidation processed successfully", [
            'ApplicationNumber' => $applicationNumber,
            'LoanNumber'        => $loanNumber,
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


}

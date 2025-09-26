<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

class EmployeeLoanController extends Controller
{
    /**
     * Handle incoming XML requests and delegate based on MessageType.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
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
    
        // Extract the signature from the XML content
        $signature = $this->extractSignature($xmlContent);
        if (!$signature) {
            return $this->generateXmlResponse('8009', 'Invalid Signature');
        }
    
        // Verify digital signature
        if (!$this->verifySignature($xmlContent, $signature)) {
            return $this->generateXmlResponse('8009', 'Invalid Signature');
        }
    
        // Parse the XML and dispatch
        return $this->parseAndDispatch($xmlContent);
    }

    /**
     * Extract the Signature element from the XML content.
     *
     * @param string $xmlContent
     * @return string|null
     */
    private function extractSignature($xmlContent)
    {
        try {
            $xml = new SimpleXMLElement($xmlContent);
            if (isset($xml->Signature)) {
                $signature = (string) $xml->Signature;
                return $signature;
            }
            return null;
        } catch (\Exception $e) {
            Log::error('Error extracting signature:', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Check if the provided string is valid XML.
     *
     * @param string $xmlContent
     * @return bool
     */
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

    /**
     * Generate an XML response based on the provided parameters.
     *
     * @param string $responseCode
     * @param string $description
     * @param int    $httpStatusCode
     * @return \Illuminate\Http\Response
     */
    private function generateXmlResponse($responseCode, $description, $httpStatusCode = 200)
    {
        $xml = new SimpleXMLElement('<Document/>');

        $data = $xml->addChild('Data');
        $header = $data->addChild('Header');
        $header->addChild('Sender', 'ESS_UTUMISHI'); 
        $header->addChild('Receiver', 'URA SACCOS LTD LOAN');     
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

    /**
     * Verifies the digital signature of the XML.
     *
     * @param string $xmlContent
     * @param string $signature
     * @return bool
     */
    private function verifySignature($xmlContent, $signature)
    {
        
        $publicKeyPath = '/home/crm/esstraining.crt';

        if (!file_exists($publicKeyPath)) {
            Log::error('Public key file not found at path:', ['path' => $publicKeyPath]);
            return false;
        }

        $publicKeyContent = file_get_contents($publicKeyPath);
        $publicKey = openssl_pkey_get_public($publicKeyContent);

        if (!$publicKey) {
            Log::error('Invalid public key content');
            return false;
        }

        // Decode the base64-encoded signature
        $signatureDecoded = base64_decode($signature);

        if ($signatureDecoded === false) {
            Log::error('Failed to base64 decode the signature');
            return false;
        }

        // Parse the XML content to extract the <Data> element
        $dom = new \DOMDocument();
        $dom->loadXML($xmlContent);

        // Remove the <Signature> element
        $signatureNode = $dom->getElementsByTagName('Signature')->item(0);
        if ($signatureNode) {
            $signatureNode->parentNode->removeChild($signatureNode);
        }

        // Extract the <Data> element
        $dataNode = $dom->getElementsByTagName('Data')->item(0);

        if (!$dataNode) {
            Log::error('Data element not found in XML');
            return false;
        }

        // Canonicalize the <Data> element
        $dataElementCanonicalized = $dataNode->C14N();

        // Verify the signature using the canonicalized <Data> element
        $verification = openssl_verify($dataElementCanonicalized, $signatureDecoded, $publicKey, OPENSSL_ALGO_SHA256);

        if ($verification === 1) {
            openssl_free_key($publicKey);
            return true;
        } elseif ($verification === 0) {
            Log::error('Signature verification failed');
            openssl_free_key($publicKey);
            return false;
        } else {
            Log::error('Error during signature verification:', ['error' => openssl_error_string()]);
            openssl_free_key($publicKey);
            return false;
        }
    }

    /**
     * Parse XML and dispatch based on MessageType.
     *
     * @param string $xmlContent
     * @return \Illuminate\Http\Response
     */
    private function parseAndDispatch($xmlContent)
    {
        try {
            $xml = new SimpleXMLElement($xmlContent);

            $messageType = (string) $xml->Data->Header->MessageType ?? null;

            if (!$messageType) {
                return $this->generateXmlResponse('8002', 'MessageType not specified');
            }

            return $this->handleMessageType($messageType, $xml);
        } catch (\Exception $e) {
            Log::error('Error processing XML:', ['error' => $e->getMessage()]);
            return $this->generateXmlResponse('8001', 'Invalid XML format or processing error');
        }
    }

    /**
     * Dispatch to specific methods based on the MessageType.
     *
     * @param string           $messageType
     * @param SimpleXMLElement $xml
     * @return \Illuminate\Http\Response
     */
    private function handleMessageType($messageType, $xml)
    {
        switch ($messageType) {
            case 'LOAN_CHARGES_REQUEST':
                return $this->handleLoanChargesRequest($xml);
            // case 'LOAN_CALCULATOR':
            //     return $this->handleLoanCalculator($xml);
            case 'LOAN_OFFER_REQUEST':
                return $this->handleLoanOfferRequest($xml);
            case 'LOAN_APPROVAL':
                return $this->handleLoanApproval($xml);
            case 'LOAN_REJECTION':
                return $this->handleLoanRejection($xml);
            default:
                return $this->generateXmlResponse('8003', 'Unsupported MessageType');
        }
    }

    // Implement your business logic in these methods

    private function handleLoanChargesRequest($xml)
    {
        Log::info('Handling LOAN_CHARGES_REQUEST', ['data' => $xml]);

        // Extract necessary data from XML
        $applicationNumber = (string) $xml->Data->MessageDetails->ApplicationNumber ?? null;

        // Implement your business logic here
        // For example, process the loan charges request using the application number

        // On successful processing, return a success response
        return $this->generateXmlResponse('0000', 'Loan Charges Request processed successfully');
    }

    // private function handleLoanCalculator($xml)
    // {
    //     Log::info('Handling LOAN_CALCULATOR', ['data' => $xml]);

    //     // Extract necessary data from XML
    //     // Implement your business logic here

    //     // On successful processing, return a success response
    //     return $this->generateXmlResponse('0000', 'Loan Calculator processed successfully', 200);
    // }

    private function handleLoanCalculator($xml)
{
    Log::info('Handling LOAN_CALCULATOR', ['data' => $xml]);

    // Extract necessary data from XML
    // Assuming the XML structure contains the following elements:
    // - BasicSalary
    // - Allowances (which may have multiple Allowance elements)
    // - TakeHome
    // - NumberOfMonths

    // Extract basic salary
    $basicSalary = (float) $xml->Data->MessageDetails->BasicSalary ?? 0;

    // Extract allowances
    $allowances = [];
    if (isset($xml->Data->MessageDetails->Allowances->Allowance)) {
        foreach ($xml->Data->MessageDetails->Allowances->Allowance as $allowance) {
            $allowances[] = (float) $allowance;
        }
    }

    // Extract take-home pay
    $takeHome = (float) $xml->Data->MessageDetails->TakeHome ?? 0;

    // Extract number of months (loan tenure)
    $numberOfMonths = (int) $xml->Data->MessageDetails->NumberOfMonths ?? 0;

    // Validate inputs
    if ($basicSalary <= 0 || $takeHome <= 0 || $numberOfMonths <= 0) {
        return $this->generateXmlResponse('8004', 'Invalid input data');
    }

    // Calculate one-third of the salary
    $oneThirdSalary = $basicSalary / 3;

    // Calculate total allowances
    $totalAllowances = array_sum($allowances);

    // Calculate the loanable take-home amount (monthly payment M)
    $loanableTakeHome = $takeHome - ($oneThirdSalary + $totalAllowances);

    // Ensure loanableTakeHome is positive
    if ($loanableTakeHome <= 0) {
        return $this->generateXmlResponse('8005', 'Insufficient take-home pay for loan calculation');
    }

    // Fixed annual interest rate
    $annualInterestRate = 12; // As per your earlier message
    $monthlyInterestRate = $annualInterestRate / 100 / 12; // Convert annual interest rate to monthly

    // Using the formula to calculate the principal (loanApplicable)
    // P = M × [ (1 + r)^n - 1 ] / [ r × (1 + r)^n ]
    $rPlusOnePowN = pow(1 + $monthlyInterestRate, $numberOfMonths);
    $numerator = $loanableTakeHome * ($rPlusOnePowN - 1);
    $denominator = $monthlyInterestRate * $rPlusOnePowN;

    if ($denominator == 0) {
        return $this->generateXmlResponse('8006', 'Invalid interest rate or loan tenure');
    }

    $loanApplicable = $numerator / $denominator;

    // Calculate Monthly Deduction (fixed monthly payment)
    $monthlyDeduction = $loanableTakeHome; // Since loanableTakeHome is the monthly payment (M)

    // Calculate Total Loan with Interest
    $totalLoanWithInterest = $monthlyDeduction * $numberOfMonths;

    // Calculate Total Interest
    $totalInterest = $totalLoanWithInterest - $loanApplicable;

    // Calculate Processing Fee (Assume 0.25% of the loan applicable)
    $processingFee = $loanApplicable * 0.0025;

    // Calculate Insurance (Assume 1% of the loan applicable)
    $insurance = $loanApplicable * 0.01;

    // Calculate Disbursement Amount (The amount received after fees)
    $disbursementAmount = $loanApplicable - ($processingFee + $insurance);

    // Prepare response data
    $responseData = [
        'LoanableTakeHome' => number_format($loanableTakeHome, 2, '.', ''),
        'LoanableAmount' => number_format($loanApplicable, 2, '.', ''),
        'TotalLoanWithInterest' => number_format($totalLoanWithInterest, 2, '.', ''),
        'TotalInterest' => number_format($totalInterest, 2, '.', ''),
        'MonthlyDeduction' => number_format($monthlyDeduction, 2, '.', ''),
        'ProcessingFee' => number_format($processingFee, 2, '.', ''),
        'Insurance' => number_format($insurance, 2, '.', ''),
        'DisbursementAmount' => number_format($disbursementAmount, 2, '.', ''),
    ];

    // Generate XML response with loan calculation details
    return $this->generateLoanCalculatorResponse('0000', 'Loan Calculator processed successfully', $responseData);
}
/**
 * Generate an XML response for the Loan Calculator with detailed results.
 *
 * @param string $responseCode
 * @param string $description
 * @param array  $data
 * @param int    $httpStatusCode
 * @return \Illuminate\Http\Response
 */
private function generateLoanCalculatorResponse($responseCode, $description, $data, $httpStatusCode = 200)
{
    $xml = new SimpleXMLElement('<Document/>');

    $dataXml = $xml->addChild('Data');
    $header = $dataXml->addChild('Header');
    $header->addChild('Sender', 'URA SACCOS LTD LOAN'); // Adjust as needed
    $header->addChild('Receiver', 'ESS_UTUMISHI');     // Adjust as needed
    $header->addChild('FSPCode', 'FL7456');         // Adjust as needed
    $header->addChild('MsgId', uniqid());         // Generate a unique message ID
    $header->addChild('MessageType', 'LOAN_CALCULATOR_RESPONSE');

    $messageDetails = $dataXml->addChild('MessageDetails');
    $messageDetails->addChild('ResponseCode', $responseCode);
    $messageDetails->addChild('Description', $description);

    // Add loan calculation details
    $loanDetails = $messageDetails->addChild('LoanDetails');
    foreach ($data as $key => $value) {
        $loanDetails->addChild($key, $value);
    }

    // Optionally, you can sign the response here if required
    // For now, we'll just include a placeholder Signature element
    $xml->addChild('Signature', 'Signature');

    // Convert XML object to string
    $responseContent = $xml->asXML();

    // Return response with correct headers
    return response($responseContent, $httpStatusCode)
        ->header('Content-Type', 'application/xml');
}


    private function handleLoanApproval($xml)
    {
        Log::info('Handling LOAN_APPROVAL', ['data' => $xml]);

        // Extract necessary data from XML
        // Implement your business logic here

        // On successful processing, return a success response
        return $this->generateXmlResponse('0000', 'Loan Approval processed successfully');
    }

    private function handleLoanRejection($xml)
    {
        Log::info('Handling LOAN_REJECTION', ['data' => $xml]);

        // Extract necessary data from XML
        // Implement your business logic here

        // On successful processing, return a success response
        return $this->generateXmlResponse('0000', 'Loan Rejection processed successfully');
    }
}

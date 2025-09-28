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
     * @return \Illuminate\Http\Response|\Illuminate\View\View
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
     * Verifies the digital signature of the XML.
     *
     * @param string $xmlContent
     * @param string $signature
     * @return bool
     */
    private function verifySignature($xmlContent, $signature)
    {
        // The path to your ESS public key certificate
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

        openssl_free_key($publicKey);

        if ($verification === 1) {
            return true;
        } elseif ($verification === 0) {
            Log::error('Signature verification failed');
            return false;
        } else {
            Log::error('Error during signature verification:', ['error' => openssl_error_string()]);
            return false;
        }
    }

    /**
     * Parse XML and dispatch based on MessageType.
     *
     * @param string $xmlContent
     * @return \Illuminate\Http\Response|\Illuminate\View\View
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
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    private function handleMessageType($messageType, $xml)
    {
        switch ($messageType) {
            case 'PRODUCT_DETAIL':
                return $this->handleProductDetail($xml);
            case 'LOAN_CHARGES_REQUEST':
                return $this->handleLoanChargesRequest($xml);
            case 'LOAN_OFFER_REQUEST':
                return $this->handleLoanOfferRequest($xml);
            case 'LOAN_FINAL_APPROVAL_NOTIFICATION':
                return $this->handleLoanFinalApprovalNotification($xml);
            case 'LOAN_CANCELLATION_NOTIFICATION':
                return $this->handleLoanCancellationNotification($xml);
            default:
                return $this->generateXmlResponse('8003', 'Unsupported MessageType');
        }
    }

    /**
     * Generate an XML response based on the provided parameters.
     *
     * @param string $responseCode
     * @param string $description
     * @param string $messageType
     * @param int    $httpStatusCode
     * @return \Illuminate\Http\Response
     */
    private function generateXmlResponse($responseCode, $description, $messageType = 'RESPONSE', $httpStatusCode = 200)
    {
        $xml = new SimpleXMLElement('<Document/>');

        $data = $xml->addChild('Data');
        $header = $data->addChild('Header');
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', 'FL7456');
        $header->addChild('MsgId', uniqid());
        $header->addChild('MessageType', $messageType);

        $messageDetails = $data->addChild('MessageDetails');
        $messageDetails->addChild('ResponseCode', $responseCode);
        $messageDetails->addChild('Description', $description);

        // Sign the XML
        $signedXmlContent = $this->signXml($xml->asXML());

        return response($signedXmlContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Handle PRODUCT_DETAIL message.
     *
     * @param SimpleXMLElement $xml
     * @return \Illuminate\Http\Response
     */
    private function handleProductDetail($xml)
    {
        Log::info('Handling PRODUCT_DETAIL', ['data' => $xml]);

        // Extract necessary data from XML
        $messageDetailsList = $xml->Data->MessageDetails;

        // Initialize an array to store product details
        $products = [];

        foreach ($messageDetailsList as $messageDetails) {
            $product = [
                'DeductionCode' => (string) $messageDetails->DeductionCode ?? null,
                'ProductCode' => (string) $messageDetails->ProductCode ?? null,
                'ProductName' => (string) $messageDetails->ProductName ?? null,
                'ProductDescription' => (string) $messageDetails->ProductDescription ?? null,
                'ForExecutive' => (string) $messageDetails->ForExecutive ?? null,
                'MinimumTenure' => (string) $messageDetails->MinimumTenure ?? null,
                'MaximumTenure' => (string) $messageDetails->MaximumTenure ?? null,
                'InterestRate' => (string) $messageDetails->InterestRate ?? null,
                'ProcessFee' => (string) $messageDetails->ProcessFee ?? null,
                'Insurance' => (string) $messageDetails->Insurance ?? null,
                'MaxAmount' => (string) $messageDetails->MaxAmount ?? null,
                'MinAmount' => (string) $messageDetails->MinAmount ?? null,
                'RepaymentType' => (string) $messageDetails->RepaymentType ?? null,
                'Currency' => (string) $messageDetails->Currency ?? null,
                'InsuranceType' => (string) $messageDetails->InsuranceType ?? null,
                'TermsConditions' => [],
            ];

            // Extract Terms and Conditions
            if (isset($messageDetails->TermsCondition)) {
                foreach ($messageDetails->TermsCondition as $tc) {
                    $product['TermsConditions'][] = [
                        'TermsConditionNumber' => (string) $tc->TermsConditionNumber ?? null,
                        'Description' => (string) $tc->Description ?? null,
                        'TCEffectiveDate' => (string) $tc->TCEffectiveDate ?? null,
                    ];
                }
            }

            $products[] = $product;
        }

        // Implement your business logic here
        // For example, save the products to your database

        // Return a success response
        return $this->generateXmlResponse('0000', 'Product details processed successfully', 'PRODUCT_DETAIL_RESPONSE');
    }

    /**
     * Handle LOAN_CHARGES_REQUEST message.
     *
     * @param SimpleXMLElement $xml
     * @return \Illuminate\Http\Response
     */
    private function handleLoanChargesRequest($xml)
    {
        Log::info('Handling LOAN_CHARGES_REQUEST', ['data' => $xml]);

        // Extract necessary data from XML
        $messageDetails = $xml->Data->MessageDetails;

        // Extract required fields
        $checkNumber = (string) $messageDetails->CheckNumber ?? null;
        $basicSalary = (float) $messageDetails->BasicSalary ?? null;
        $netSalary = (float) $messageDetails->NetSalary ?? null;
        $oneThirdAmount = (float) $messageDetails->OneThirdAmount ?? null;
        $deductibleAmount = (float) $messageDetails->DeductibleAmount ?? null;
        $requestedAmount = (float) $messageDetails->RequestedAmount ?? null;
        $desiredDeductibleAmount = (float) $messageDetails->DesiredDeductibleAmount ?? null;
        $tenure = (int) $messageDetails->Tenure ?? null; // Repayment period

        // Extract allowances if provided
        $allowances = [];
        if (isset($messageDetails->Allowances->Allowance)) {
            foreach ($messageDetails->Allowances->Allowance as $allowance) {
                $allowances[] = (float) $allowance;
            }
        }

        // Validate mandatory fields
        if (!$checkNumber || !$basicSalary || !$netSalary || !$oneThirdAmount || !$deductibleAmount || !$tenure) {
            return $this->generateXmlResponse('8002', 'Missing mandatory fields in LOAN_CHARGES_REQUEST', 'LOAN_CHARGES_RESPONSE');
        }

        // Begin loan calculation using your provided logic

        // Calculate one third of the salary
        $oneThirdSalary = $oneThirdAmount;

        $totalAllowances = array_sum($allowances);

        // Calculate the loanable take-home amount (this will be treated as the monthly payment M)
        $loanableTakeHome = $deductibleAmount; // According to your data, deductibleAmount = NetSalary - OneThirdAmount

        // Ensure the loanable take-home is positive
        if ($loanableTakeHome <= 0) {
            return $this->generateXmlResponse('8005', 'Insufficient take-home pay for loan calculation', 'LOAN_CHARGES_RESPONSE');
        }

        // Fixed annual interest rate (you can adjust this rate as per your business rules)
        $annualInterestRate = 12; // 12% annual interest rate
        $monthlyInterestRate = $annualInterestRate / 100 / 12; // Convert annual interest rate to monthly

        // Using the formula to calculate the principal (loanApplicable)
        // P = M × [ (1 + r)^n - 1 ] / [ r × (1 + r)^n ]
        $powResult = pow(1 + $monthlyInterestRate, $tenure);

        // Avoid division by zero
        $denominator = $monthlyInterestRate * $powResult;
        if ($denominator == 0) {
            return $this->generateXmlResponse('8006', 'Invalid interest rate or loan tenure', 'LOAN_CHARGES_RESPONSE');
        }

        $loanApplicable = ($loanableTakeHome * ($powResult - 1)) / $denominator;

        // Calculate Monthly Deduction (fixed monthly payment)
        $monthlyDeduction = $loanableTakeHome; // Since loanableTakeHome is the monthly payment (M)

        // Total Loan with Interest is the monthly deduction multiplied by the number of months
        $totalLoanWithInterest = $monthlyDeduction * $tenure;

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
            'DesiredDeductibleAmount' => number_format($monthlyDeduction, 2, '.', ''),
            'TotalInsurance' => number_format($insurance, 2, '.', ''),
            'TotalProcessingFees' => number_format($processingFee, 2, '.', ''),
            'TotalInterestRateAmount' => number_format($totalInterest, 2, '.', ''),
            'OtherCharges' => '0.00', // Assuming no other charges
            'NetLoanAmount' => number_format($disbursementAmount, 2, '.', ''),
            'TotalAmountToPay' => number_format($totalLoanWithInterest, 2, '.', ''),
            'Tenure' => $tenure,
            'EligibleAmount' => number_format($loanApplicable, 2, '.', ''),
            'MonthlyReturnAmount' => number_format($monthlyDeduction, 2, '.', ''),
        ];

        // Generate the response XML
        return $this->generateLoanChargesResponse('0000', 'Loan Charges Request processed successfully', $responseData);
    }

    /**
     * Generate LOAN_CHARGES_RESPONSE message.
     *
     * @param string $responseCode
     * @param string $description
     * @param array  $data
     * @param int    $httpStatusCode
     * @return \Illuminate\Http\Response
     */
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

        // Add loan calculation details
        foreach ($data as $key => $value) {
            $messageDetails->addChild($key, $value);
        }

        // Sign the XML
        $signedXmlContent = $this->signXml($xml->asXML());

        return response($signedXmlContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Handle LOAN_OFFER_REQUEST message.
     *
     * @param SimpleXMLElement $xml
     * @return \Illuminate\Http\Response
     */
    private function handleLoanOfferRequest($xml)
    {
        Log::info('Handling LOAN_OFFER_REQUEST', ['data' => $xml]);

        // Extract necessary data from XML
        $messageDetails = $xml->Data->MessageDetails;
        $applicationNumber = (string) $messageDetails->ApplicationNumber ?? null;

        // Validate mandatory fields
        if (!$applicationNumber) {
            return $this->generateXmlResponse('8002', 'Missing ApplicationNumber in LOAN_OFFER_REQUEST', 'LOAN_INITIAL_APPROVAL_NOTIFICATION');
        }

        // Implement your business logic here
        // For example, validate the loan offer request, check eligibility, etc.

        // Assume the loan offer is accepted and send back an initial approval notification
        $responseData = [
            'ApplicationNumber' => $applicationNumber,
            'Reason' => 'Offer Accepted',
            'FSPReferenceNumber' => 'FSP_REF_' . uniqid(),
            'LoanNumber' => 'LN' . uniqid(),
            'TotalAmountToPay' => '2500000.58',
            'OtherCharges' => '2500.05',
            'Approval' => 'APPROVED',
        ];

        return $this->generateLoanInitialApprovalNotification($responseData);
    }

    /**
     * Generate LOAN_INITIAL_APPROVAL_NOTIFICATION message.
     *
     * @param array $data
     * @param int   $httpStatusCode
     * @return \Illuminate\Http\Response
     */
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

        // Sign the XML
        $signedXmlContent = $this->signXml($xml->asXML());

        return response($signedXmlContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Handle LOAN_FINAL_APPROVAL_NOTIFICATION message.
     *
     * @param SimpleXMLElement $xml
     * @return \Illuminate\Http\Response
     */
    private function handleLoanFinalApprovalNotification($xml)
    {
        Log::info('Handling LOAN_FINAL_APPROVAL_NOTIFICATION', ['data' => $xml]);

        // Extract necessary data from XML
        $messageDetails = $xml->Data->MessageDetails;
        $applicationNumber = (string) $messageDetails->ApplicationNumber ?? null;
        $approval = (string) $messageDetails->Approval ?? null;

        // Validate mandatory fields
        if (!$applicationNumber || !$approval) {
            return $this->generateXmlResponse('8002', 'Missing mandatory fields in LOAN_FINAL_APPROVAL_NOTIFICATION', 'LOAN_DISBURSEMENT_NOTIFICATION');
        }

        // Implement your business logic here
        if (strtoupper($approval) == 'APPROVED') {
            // Proceed to disbursement
            $responseData = [
                'ApplicationNumber' => $applicationNumber,
                'FSPReferenceNumber' => (string) $messageDetails->FSPReferenceNumber ?? '',
                'LoanNumber' => (string) $messageDetails->LoanNumber ?? '',
                'TotalAmountToPay' => '2500000',
                'DisbursementDate' => date('Y-m-d\TH:i:s'),
            ];

            return $this->generateLoanDisbursementNotification($responseData);
        } else {
            // Handle rejection
            $responseData = [
                'ApplicationNumber' => $applicationNumber,
                'Reason' => (string) $messageDetails->Reason ?? 'Loan not approved',
            ];

            return $this->generateLoanDisbursementFailureNotification($responseData);
        }
    }

    /**
     * Generate LOAN_DISBURSEMENT_NOTIFICATION message.
     *
     * @param array $data
     * @param int   $httpStatusCode
     * @return \Illuminate\Http\Response
     */
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

        // Sign the XML
        $signedXmlContent = $this->signXml($xml->asXML());

        return response($signedXmlContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Generate LOAN_DISBURSEMENT_FAILURE_NOTIFICATION message.
     *
     * @param array $data
     * @param int   $httpStatusCode
     * @return \Illuminate\Http\Response
     */
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

        // Sign the XML
        $signedXmlContent = $this->signXml($xml->asXML());

        return response($signedXmlContent, $httpStatusCode)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Handle LOAN_CANCELLATION_NOTIFICATION message.
     *
     * @param SimpleXMLElement $xml
     * @return \Illuminate\Http\Response
     */
    private function handleLoanCancellationNotification($xml)
    {
        Log::info('Handling LOAN_CANCELLATION_NOTIFICATION', ['data' => $xml]);

        // Extract necessary data from XML
        $messageDetails = $xml->Data->MessageDetails;
        $applicationNumber = (string) $messageDetails->ApplicationNumber ?? null;
        $reason = (string) $messageDetails->Reason ?? null;

        // Validate mandatory fields
        if (!$applicationNumber || !$reason) {
            return $this->generateXmlResponse('8002', 'Missing mandatory fields in LOAN_CANCELLATION_NOTIFICATION', 'RESPONSE');
        }

        // Implement your business logic here
        // For example, cancel the loan application in your system

        // On successful cancellation, return a success response
        return $this->generateXmlResponse('0000', 'Loan cancellation processed successfully', 'RESPONSE');
    }

    /**
     * Sign the XML content.
     *
     * @param string $xmlContent
     * @return string|null
     */
    private function signXml($xmlContent)
    {
        // Load your private key
        $privateKeyPath = '/home/crm/emkopo.key'; // Path to your private key
        $privateKeyPassword = ''; //  private key is password protected

        if (!file_exists($privateKeyPath)) {
            Log::error('Private key file not found at path:', ['path' => $privateKeyPath]);
            return null;
        }

        $privateKeyContent = file_get_contents($privateKeyPath);
        $privateKey = openssl_pkey_get_private($privateKeyContent, $privateKeyPassword);

        if (!$privateKey) {
            Log::error('Invalid private key content');
            return null;
        }

        // Remove existing Signature element if any
        $dom = new \DOMDocument();
        $dom->loadXML($xmlContent);
        $signatureNode = $dom->getElementsByTagName('Signature')->item(0);
        if ($signatureNode) {
            $signatureNode->parentNode->removeChild($signatureNode);
        }

        // Canonicalize the <Data> element
        $dataNode = $dom->getElementsByTagName('Data')->item(0);
        $dataElementCanonicalized = $dataNode->C14N();

        // Sign the canonicalized data
        openssl_sign($dataElementCanonicalized, $signature, $privateKey, OPENSSL_ALGO_SHA256);

        // Free the private key from memory
        openssl_free_key($privateKey);

        // Base64 encode the signature
        $signatureEncoded = base64_encode($signature);

        // Add the Signature element back to the XML
        $signatureElement = $dom->createElement('Signature', $signatureEncoded);
        $dom->documentElement->appendChild($signatureElement);

        // Return the signed XML
        $signedXmlContent = $dom->saveXML();

        return $signedXmlContent;
    }

    /**
     * Send Product Catalog to ESS (Message 01).
     *
     * @return \Illuminate\Http\Response
     */
    public function sendProductCatalog()
    {
        // Prepare product catalog data
        $products = $this->getProductCatalogData();

        // Generate the Product Catalog XML
        $xmlContent = $this->generateProductCatalogXml($products);

        // Sign the XML content
        $signedXmlContent = $this->signXml($xmlContent);

        if (!$signedXmlContent) {
            return response()->json(['message' => 'Failed to sign XML content'], 500);
        }

        // Send the XML to ESS
        $essUrl = 'http://154.118.230.140:9802/ess-loans/mvtyztwq/consume'; // ESS endpoint
        $response = $this->sendXmlToEss($signedXmlContent, $essUrl);

        // Handle the response
        if ($response && $response->getStatusCode() == 200) {
            Log::info('Product Catalog sent successfully to ESS.');
        } else {
            Log::error('Failed to send Product Catalog to ESS.', ['response' => $response ? $response->getBody()->getContents() : 'No response']);
        }

        return response()->json(['message' => 'Product Catalog sent'], 200);
    }

    /**
     * Get Product Catalog Data.
     *
     * @return array
     */
    private function getProductCatalogData()
    {
        // Fetch your product catalog data from the database or any other source
        // For demonstration, we'll use hardcoded data based on Message 01

        $products = [
            [
                'DeductionCode' => 'FL0001',
                'ProductCode' => 'LA1001',
                'ProductName' => 'Mkopo wa Simu',
                'ProductDescription' => 'Mkopo Elimu',
                'ForExecutive' => 'false',
                'MinimumTenure' => '12',
                'MaximumTenure' => '24',
                'InterestRate' => '10.00',
                'ProcessFee' => '15.00',
                'Insurance' => '0.75',
                'MaxAmount' => '5000000',
                'MinAmount' => '100000',
                'RepaymentType' => 'Flat',
                'Currency' => 'TZS',
                'InsuranceType' => 'DISTRIBUTED',
                'TermsConditions' => [
                    [
                        'TermsConditionNumber' => '123456',
                        'Description' => 'Payment Must be Made in Full',
                        'TCEffectiveDate' => '2024-02-22',
                    ],
                    [
                        'TermsConditionNumber' => '123457',
                        'Description' => 'Loan must be paid within time',
                        'TCEffectiveDate' => '2024-02-22',
                    ],
                ],
            ],
            // Add more products as needed
        ];

        return $products;
    }

    /**
     * Generate Product Catalog XML.
     *
     * @param array $products
     * @return string
     */
    private function generateProductCatalogXml($products)
    {
        $xml = new SimpleXMLElement('<Document/>');

        $dataXml = $xml->addChild('Data');
        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', 'URA SACCOS LTD LOAN');
        $header->addChild('Receiver', 'ESS_UTUMISHI');
        $header->addChild('FSPCode', 'FL7456');
        $header->addChild('MsgId', uniqid());
        $header->addChild('MessageType', 'PRODUCT_DETAIL');

        // Add each product
        foreach ($products as $product) {
            $messageDetails = $dataXml->addChild('MessageDetails');
            $messageDetails->addChild('DeductionCode', $product['DeductionCode']);
            $messageDetails->addChild('ProductCode', $product['ProductCode']);
            $messageDetails->addChild('ProductName', $product['ProductName']);
            $messageDetails->addChild('ProductDescription', $product['ProductDescription']);
            $messageDetails->addChild('ForExecutive', $product['ForExecutive']);
            $messageDetails->addChild('MinimumTenure', $product['MinimumTenure']);
            $messageDetails->addChild('MaximumTenure', $product['MaximumTenure']);
            $messageDetails->addChild('InterestRate', $product['InterestRate']);
            $messageDetails->addChild('ProcessFee', $product['ProcessFee']);
            $messageDetails->addChild('Insurance', $product['Insurance']);
            $messageDetails->addChild('MaxAmount', $product['MaxAmount']);
            $messageDetails->addChild('MinAmount', $product['MinAmount']);
            $messageDetails->addChild('RepaymentType', $product['RepaymentType']);
            $messageDetails->addChild('Currency', $product['Currency']);
            $messageDetails->addChild('InsuranceType', $product['InsuranceType']);

            // Add Terms and Conditions
            foreach ($product['TermsConditions'] as $tc) {
                $termsCondition = $messageDetails->addChild('TermsCondition');
                $termsCondition->addChild('TermsConditionNumber', $tc['TermsConditionNumber']);
                $termsCondition->addChild('Description', $tc['Description']);
                $termsCondition->addChild('TCEffectiveDate', $tc['TCEffectiveDate']);
            }
        }

        // We'll add the Signature after signing the XML

        $xmlContent = $xml->asXML();

        return $xmlContent;
    }

    /**
     * Send XML to ESS.
     *
     * @param string $xmlContent
     * @param string $url
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    private function sendXmlToEss($xmlContent, $url)
    {
        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/xml',
                ],
                'body' => $xmlContent,
                'verify' => '/home/crm/esstraining.crt', // Path to the ESS SSL certificate if needed
            ]);

            return $response;
        } catch (\Exception $e) {
            Log::error('Error sending XML to ESS:', ['error' => $e->getMessage()]);
            return null;
        }
    }
}

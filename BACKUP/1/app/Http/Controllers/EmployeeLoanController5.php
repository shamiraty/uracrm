<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;

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
            Log::error('Public key file not found at path:', ['path' => $publicKeyPath]);
            return false;
        }

        $publicKeyContent = file_get_contents($publicKeyPath);
        $publicKey = openssl_pkey_get_public($publicKeyContent);

        if (!$publicKey) {
            Log::error('Invalid public key content');
            return false;
        }

        $signatureDecoded = base64_decode($signature);
        if ($signatureDecoded === false) {
            Log::error('Failed to base64 decode the signature');
            return false;
        }

        $dom = new \DOMDocument();
        $dom->loadXML($xmlContent);
        $signatureNode = $dom->getElementsByTagName('Signature')->item(0);
        if ($signatureNode) {
            $signatureNode->parentNode->removeChild($signatureNode);
        }

        $dataNode = $dom->getElementsByTagName('Data')->item(0);
        if (!$dataNode) {
            Log::error('Data element not found in XML');
            return false;
        }

        $dataElementCanonicalized = $dataNode->C14N();
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

    private function parseAndDispatch($xmlContent)
    {
        try {
            $xml = new SimpleXMLElement($xmlContent);
            $messageType = (string)$xml->Data->Header->MessageType ?? null;

            if (!$messageType) {
                return $this->generateXmlResponse('8002', 'MessageType not specified');
            }

            return $this->handleMessageType($messageType, $xml);
        } catch (\Exception $e) {
            Log::error('Error processing XML:', ['error' => $e->getMessage()]);
            return $this->generateXmlResponse('8001', 'Invalid XML format or processing error');
        }
    }

    private function handleMessageType($messageType, $xml)
    {
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

        $annualInterestRate = 12; 
        $r = $annualInterestRate / 100 / 12;

        // If requestedAmount is given, use it as the principal and net loan amount, no recalculation of P.
        // No fees or insurance will reduce it, so netLoanAmount = requestedAmount exactly.
        $P = $requestedAmount > 0 ? $requestedAmount : 0;
        $M = $desiredDeductibleAmount > 0 ? $desiredDeductibleAmount : $deductibleAmount;

        // Determine scenario
        $scenarioPN = ($P > 0 && $tenure > 0);     // P & N => Solve M
        $scenarioPM = ($P > 0 && $tenure == 0 && $M > 0); // P & M => Solve N
        $scenarioMN = ($P == 0 && $M > 0 && $tenure > 0); // M & N => Solve P

        if ($tenure > 0) {
            $tenure = min($tenure, $maxTenureAllowed);
        }

        if ($scenarioPN) {
            // Given P & N => Solve M (no recalc of P since requestedAmount given)
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
            // Given P & M => Solve N (no recalc of P or M after)
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
                // Do not recalc P or M since requestedAmount was given and M given.
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

            // If we clamp N later, we can recalc P since requestedAmount=0 in this scenario.
        } else {
            return $this->generateXmlResponse('8002', 'Not enough data to calculate loan details');
        }

        // Final check N
        if (!isset($N)) {
            $N = $tenure > 0 ? $tenure : 0; 
        }

        if ($N <= 0) {
            return $this->generateXmlResponse('8006', 'Calculated or provided tenure is invalid');
        }

        // If N > maxTenureAllowed, clamp again and recalc if scenarioMN and requestedAmount=0
        if ($N > $maxTenureAllowed) {
            $N = $maxTenureAllowed;
            if ($scenarioPN) {
                // P & N => M. Recalc M since N changed, but do not recalc P
                $powResult = pow(1 + $r, $N);
                $numerator = $r * $powResult;
                $denominator = $powResult - 1;
                $M = $P * ($numerator / $denominator);
            } elseif ($scenarioMN && $requestedAmount == 0) {
                // M & N => P scenario, recalc P since requestedAmount=0
                $powResult = pow(1 + $r, $N);
                $num = $powResult - 1;
                $den = $r * $powResult;
                $P = ($M * $num) / $den;
            }
            // If scenarioPM, do nothing since P & M given, requestedAmount > 0 means no recalc of P or M.
        }

        // If requestedAmount > 0, P = requestedAmount as final and no fees or insurance reduce it:
        if ($requestedAmount > 0) {
            $processingFee = 0.00;
            $insurance = 0.00;
            $disbursementAmount = $P; // netLoanAmount = requestedAmount exactly
        } else {
            // Calculate fees only if requestedAmount not provided
            $processingFee = $P * 0.0025;
            $insurance = $P * 0.01;
            $disbursementAmount = $P - ($processingFee + $insurance);
        }

        $totalLoanWithInterest = $M * $N;
        $totalInterest = $totalLoanWithInterest - $P;

        $responseData = [
            'DesiredDeductibleAmount' => number_format($M, 2, '.', ''),
            'TotalInsurance' => number_format($insurance, 2, '.', ''),
            'TotalProcessingFees' => number_format($processingFee, 2, '.', ''),
            'TotalInterestRateAmount' => number_format($totalInterest, 2, '.', ''),
            'OtherCharges' => '0.00',
            'NetLoanAmount' => number_format($disbursementAmount, 2, '.', ''),
            'TotalAmountToPay' => number_format($totalLoanWithInterest, 2, '.', ''),
            'Tenure' => $N,
            'EligibleAmount' => number_format($P, 2, '.', ''),
            'MonthlyReturnAmount' => number_format($M, 2, '.', ''),
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
        $applicationNumber = (string)$messageDetails->ApplicationNumber ?? null;

        if (!$applicationNumber) {
            return $this->generateXmlResponse('8002', 'Missing ApplicationNumber in LOAN_OFFER_REQUEST');
        }

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

        if (!$applicationNumber || !$reason) {
            return $this->generateXmlResponse('8002', 'Missing mandatory fields in LOAN_CANCELLATION_NOTIFICATION');
        }

        // Implement your cancellation logic here

        return $this->generateXmlResponse('0000', 'Loan cancellation processed successfully');
    }
}

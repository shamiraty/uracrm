<?php

namespace App\Services;

use SimpleXMLElement;
use Illuminate\Http\Response;

class EssXmlResponseService
{
    const FSP_CODE = 'FL7456';
    const SENDER_NAME = 'URA SACCOS LTD LOAN';
    const RECEIVER_NAME = 'ESS_UTUMISHI';
    
    /**
     * Generate standard XML response
     */
    public function generateResponse(string $responseCode, string $description, int $httpStatusCode = 200): Response
    {
        $xml = new SimpleXMLElement('<Document/>');
        
        $data = $xml->addChild('Data');
        $header = $data->addChild('Header');
        $header->addChild('Sender', self::SENDER_NAME);
        $header->addChild('Receiver', self::RECEIVER_NAME);
        $header->addChild('FSPCode', self::FSP_CODE);
        $header->addChild('MsgId', $this->generateMessageId());
        $header->addChild('MessageType', 'RESPONSE');
        
        $messageDetails = $data->addChild('MessageDetails');
        $messageDetails->addChild('ResponseCode', $responseCode);
        $messageDetails->addChild('Description', $description);
        
        $xml->addChild('Signature', 'Signature');
        
        return $this->createXmlResponse($xml->asXML(), $httpStatusCode);
    }
    
    /**
     * Generate loan charges response
     */
    public function generateLoanChargesResponse(string $responseCode, string $description, array $data, int $httpStatusCode = 200): Response
    {
        $xml = new SimpleXMLElement('<Document/>');
        
        $dataXml = $xml->addChild('Data');
        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', self::SENDER_NAME);
        $header->addChild('Receiver', self::RECEIVER_NAME);
        $header->addChild('FSPCode', self::FSP_CODE);
        $header->addChild('MsgId', $this->generateMessageId());
        $header->addChild('MessageType', 'LOAN_CHARGES_RESPONSE');
        
        $messageDetails = $dataXml->addChild('MessageDetails');
        $messageDetails->addChild('ResponseCode', $responseCode);
        $messageDetails->addChild('Description', $description);
        
        // Add loan charges data
        $this->addDataToXml($messageDetails, $data);
        
        $xml->addChild('Signature', 'Signature');
        
        return $this->createXmlResponse($xml->asXML(), $httpStatusCode);
    }
    
    /**
     * Generate loan offer approval notification
     */
    public function generateLoanOfferApprovalNotification(array $loanData, string $approval, string $reason = null): Response
    {
        $xml = new SimpleXMLElement('<Document/>');
        
        $dataXml = $xml->addChild('Data');
        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', self::FSP_CODE);
        $header->addChild('Receiver', self::RECEIVER_NAME);
        $header->addChild('FSPCode', self::FSP_CODE);
        $header->addChild('MsgId', $this->generateMessageId());
        $header->addChild('MessageType', 'LOAN_INITIAL_APPROVAL_NOTIFICATION');
        
        $messageDetails = $dataXml->addChild('MessageDetails');
        $messageDetails->addChild('ApplicationNumber', $loanData['application_number']);
        $messageDetails->addChild('Reason', $reason ?: 'Ok');
        $messageDetails->addChild('FSPReferenceNumber', $loanData['fsp_reference_number'] ?? '');
        $messageDetails->addChild('LoanNumber', $loanData['loan_number'] ?? '');
        $messageDetails->addChild('TotalAmountToPay', $loanData['total_amount_to_pay'] ?? '');
        $messageDetails->addChild('OtherCharges', $loanData['other_charges'] ?? '');
        $messageDetails->addChild('Approval', strtoupper($approval));
        
        $xml->addChild('Signature', 'Signature');
        
        return $this->createXmlResponse($xml->asXML(), 200);
    }
    
    /**
     * Generate disbursement notification
     */
    public function generateDisbursementNotification(array $loanData, string $status = 'SUCCESS'): Response
    {
        $xml = new SimpleXMLElement('<Document/>');
        
        $dataXml = $xml->addChild('Data');
        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', self::FSP_CODE);
        $header->addChild('Receiver', self::RECEIVER_NAME);
        $header->addChild('FSPCode', self::FSP_CODE);
        $header->addChild('MsgId', $this->generateMessageId());
        
        if ($status === 'SUCCESS') {
            $header->addChild('MessageType', 'LOAN_DISBURSEMENT_NOTIFICATION');
            
            $messageDetails = $dataXml->addChild('MessageDetails');
            $messageDetails->addChild('ApplicationNumber', $loanData['application_number']);
            $messageDetails->addChild('FSPReferenceNumber', $loanData['fsp_reference_number'] ?? '');
            $messageDetails->addChild('LoanNumber', $loanData['loan_number'] ?? '');
            $messageDetails->addChild('TotalAmountToPay', $loanData['total_amount_to_pay'] ?? '');
            $messageDetails->addChild('DisbursementDate', date('Y-m-d\TH:i:s'));
        } else {
            $header->addChild('MessageType', 'LOAN_DISBURSEMENT_FAILURE_NOTIFICATION');
            
            $messageDetails = $dataXml->addChild('MessageDetails');
            $messageDetails->addChild('ApplicationNumber', $loanData['application_number']);
            $messageDetails->addChild('Reason', $loanData['failure_reason'] ?? 'Technical error');
        }
        
        $xml->addChild('Signature', 'Signature');
        
        return $this->createXmlResponse($xml->asXML(), 200);
    }
    
    /**
     * Generate top-up balance response
     */
    public function generateTopUpBalanceResponse(string $responseCode, string $description, array $balanceData): Response
    {
        $xml = new SimpleXMLElement('<Document/>');
        
        $dataXml = $xml->addChild('Data');
        $header = $dataXml->addChild('Header');
        $header->addChild('Sender', self::FSP_CODE);
        $header->addChild('Receiver', self::RECEIVER_NAME);
        $header->addChild('FSPCode', self::FSP_CODE);
        $header->addChild('MsgId', $this->generateMessageId());
        $header->addChild('MessageType', 'LOAN_TOP_UP_BALANCE_RESPONSE');
        
        $messageDetails = $dataXml->addChild('MessageDetails');
        $messageDetails->addChild('LoanNumber', $balanceData['loan_number'] ?? '');
        $messageDetails->addChild('FSPReferenceNumber', $balanceData['fsp_reference_number'] ?? '');
        $messageDetails->addChild('PaymentReferenceNumber', $balanceData['payment_reference_number'] ?? '');
        $messageDetails->addChild('TotalPayoffAmount', $balanceData['total_payoff_amount'] ?? '');
        $messageDetails->addChild('OutstandingBalance', $balanceData['outstanding_balance'] ?? '');
        $messageDetails->addChild('FinalPaymentDate', $balanceData['final_payment_date'] ?? '');
        $messageDetails->addChild('LastDeductionDate', $balanceData['last_deduction_date'] ?? '');
        $messageDetails->addChild('LastPayDate', $balanceData['last_pay_date'] ?? '');
        $messageDetails->addChild('EndDate', $balanceData['end_date'] ?? '');
        
        $xml->addChild('Signature', 'Signature');
        
        return $this->createXmlResponse($xml->asXML(), 200);
    }
    
    /**
     * Add data array to XML element
     */
    private function addDataToXml(SimpleXMLElement $element, array $data): void
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $child = $element->addChild($key);
                $this->addDataToXml($child, $value);
            } else {
                $element->addChild($key, htmlspecialchars((string)$value));
            }
        }
    }
    
    /**
     * Create HTTP response with XML content
     */
    private function createXmlResponse(string $xmlContent, int $statusCode): Response
    {
        return response($xmlContent, $statusCode)
            ->header('Content-Type', 'application/xml');
    }
    
    /**
     * Generate unique message ID
     */
    private function generateMessageId(): string
    {
        return uniqid('URA_', true);
    }
}
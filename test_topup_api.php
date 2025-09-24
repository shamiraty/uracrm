<?php

use Illuminate\Support\Facades\Log;

// Test the topup API endpoint directly using curl

$loanNumber = 'URL013572';

// Create the XML request
$xmlRequest = '<?xml version="1.0" encoding="UTF-8"?>
<Document>
    <Data>
        <Header>
            <Sender>ESS_UTUMISHI</Sender>
            <Receiver>URA SACCOS</Receiver>
            <FSPCode>FL7456</FSPCode>
            <MsgId>TEST_' . time() . '</MsgId>
            <MessageType>TOP_UP_PAY_0FF_BALANCE_REQUEST</MessageType>
        </Header>
        <MessageDetails>
            <LoanNumber>' . $loanNumber . '</LoanNumber>
        </MessageDetails>
    </Data>
    <Signature>TestSignature</Signature>
</Document>';

echo "Testing TOP_UP_PAY_0FF_BALANCE_REQUEST API\n";
echo "==========================================\n\n";

echo "Request XML:\n";
echo $xmlRequest . "\n\n";

// Make the request to the local endpoint
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'http://localhost/uraerp/public/api/employee_loan');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlRequest);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/xml',
    'Accept: application/xml'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo "CURL Error: $error\n";
    exit(1);
}

echo "HTTP Response Code: $httpCode\n\n";
echo "Response XML:\n";

// Format the XML for display
if ($response) {
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    if (@$dom->loadXML($response)) {
        echo $dom->saveXML();
        
        // Parse and display key values
        $responseXml = simplexml_load_string($response);
        if ($responseXml && isset($responseXml->Data->MessageDetails)) {
            $details = $responseXml->Data->MessageDetails;
            echo "\n\nParsed Response:\n";
            echo "================\n";
            echo "Loan Number: " . (string)$details->LoanNumber . "\n";
            echo "Total Payoff Amount: " . (string)$details->TotalPayoffAmount . "\n";
            echo "Outstanding Balance: " . (string)$details->OutstandingBalance . "\n";
            echo "FSP Reference: " . (string)$details->FSPReferenceNumber . "\n";
            echo "Payment Reference: " . (string)$details->PaymentReferenceNumber . "\n";
        }
    } else {
        echo $response;
    }
} else {
    echo "No response received\n";
}
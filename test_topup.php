<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Http\Controllers\EmployeeLoanController;
use App\Services\NmbDisbursementService;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Create a test XML request for the loan
$xmlString = '<?xml version="1.0" encoding="UTF-8"?>
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
            <LoanNumber>URL013572</LoanNumber>
        </MessageDetails>
    </Data>
    <Signature>TestSignature</Signature>
</Document>';

// Parse XML
$xml = simplexml_load_string($xmlString);

// Create controller instance with dependency
$nmbService = app(NmbDisbursementService::class);
$controller = new EmployeeLoanController($nmbService);

// Use reflection to call private method
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('handleTopUpPayoffBalance');
$method->setAccessible(true);

echo "Testing TOP_UP_PAY_0FF_BALANCE_REQUEST for loan URL013572\n";
echo "================================================\n\n";

echo "Loan Details:\n";
echo "- Loan Number: URL013572\n";
echo "- Principal Amount: 6,397,076.00\n";
echo "- Tenure: 36 months\n";
echo "- Installments Paid: 5\n\n";

try {
    // Call the method
    $response = $method->invoke($controller, $xml);
    
    // Get response content
    $content = $response->getContent();
    
    // Parse response XML
    $responseXml = simplexml_load_string($content);
    
    echo "Response received successfully!\n\n";
    echo "Response Details:\n";
    
    if ($responseXml) {
        $msgDetails = $responseXml->Data->MessageDetails;
        
        echo "- Loan Number: " . (string)$msgDetails->LoanNumber . "\n";
        echo "- FSP Reference: " . (string)$msgDetails->FSPReferenceNumber . "\n";
        echo "- Payment Reference: " . (string)$msgDetails->PaymentReferenceNumber . "\n";
        echo "- Total Payoff Amount: " . (string)$msgDetails->TotalPayoffAmount . "\n";
        echo "- Outstanding Balance: " . (string)$msgDetails->OutstandingBalance . "\n";
        echo "- Final Payment Date: " . (string)$msgDetails->FinalPaymentDate . "\n";
        echo "- Last Deduction Date: " . (string)$msgDetails->LastDeductionDate . "\n";
        echo "- Last Pay Date: " . (string)$msgDetails->LastPayDate . "\n";
        echo "- End Date: " . (string)$msgDetails->EndDate . "\n";
    }
    
    echo "\n\nFull XML Response:\n";
    echo "==================\n";
    
    // Format XML for display
    $dom = new DOMDocument('1.0');
    $dom->preserveWhiteSpace = false;
    $dom->formatOutput = true;
    $dom->loadXML($content);
    echo $dom->saveXML();
    
} catch (Exception $e) {
    echo "Error occurred: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
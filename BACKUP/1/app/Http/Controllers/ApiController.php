<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class ApiController extends Controller
{


public function sendProductDetails(Request $request)
{
    // Initialize DOMDocument
    $dom = new \DOMDocument('1.0', 'UTF-8');
    $dom->formatOutput = false; // Ensure no additional whitespace

    // Create the root Document element
    $document = $dom->createElement('Document');
    $dom->appendChild($document);

    // Create the Data element and append it to the Document
    $data = $dom->createElement('Data');
    $document->appendChild($data);

    // Construct the Header element
    $header = $dom->createElement('Header');
    $data->appendChild($header);

    // Populate the Header with details
    $header->appendChild($dom->createElement('Sender', 'URA_SACCOS_LTD_LOAN'));
    $header->appendChild($dom->createElement('Receiver', 'ESS_UTUMISHI'));
    $header->appendChild($dom->createElement('FSPCode', 'FL7456'));
    // $header->appendChild($dom->createElement('MsgId', 'FSP12346704134'));
    $header->appendChild($dom->createElement('MsgId', 'FSP' . uniqid('', true)));
    $header->appendChild($dom->createElement('MessageType', 'PRODUCT_DETAIL'));

    // MessageDetails construction (can be dynamically generated or fetched)
    $messageDetailsArray = [

         [


        // 'DeductionCode' => 'FL7456',
        // 'ProductCode' => '769',
        // 'ProductName' => 'MKOPO WA MAENDELEO',
        // 'ProductDescription' => 'URA SACCOS LTD',
        // 'ForExecutive' => 'false',
        // 'MinimumTenure' => '6',
        // 'MaximumTenure' => '48',
        // 'InterestRate' => '12.00',
        // 'ProcessFee' => '0.25',
        // 'Insurance' => '1.00',
        // 'MaxAmount' => '150000000',
        // 'MinAmount' => '1000000',

        'DeductionCode' => 'FL7457',
        'ProductCode' => '769A',
        'ProductName' => 'MKOPO BINAFSI',
        'ProductDescription' => 'URA SACCOS LTD',
        'ForExecutive' => 'false',
        'MinimumTenure' => '2',
        'MaximumTenure' => '48',
        'InterestRate' => '12.00',
        'ProcessFee' => '0.25',
        'Insurance' => '1.00',
        'MaxAmount' => '40000000',
        'MinAmount' => '200000',
        'RepaymentType' => 'Flat',
        'Currency' => 'TZS',
        'InsuranceType' => 'UP_FRONT',
            'TermsConditions' => [
                [
                    'TermsConditionNumber' => '123456',
                    'Description' => 'Awe mwanachama hai wa URA SACCOS anayechangia akiba yake kila mwezi,na hisa zisizopungua 30 zenye thamani ya Tshs. 150,000/=',
                    'TCEffectiveDate' => '2024-02-22',
                ],
                [
                    'TermsConditionNumber' => '123457',
                    'Description' => 'Mwanachama atakayekuwa chini ya uchunguzi wa tuhuma mbalimbali au anaeendelea na mashtaka,hataruhusiwa kupata huduma ya mikopo hadi hapo hukumu ya mashtaka yake itakapotoka na kuthibitisha kutokuwa na hatia',
                    'TCEffectiveDate' => '2024-02-22',
                ],
                [
                    'TermsConditionNumber' => '123458',
                    'Description' => 'Maombi ya mkopo yatafanyiwa thathmini na afisa mikopo kabla ya kuthibitishwa na mwajiri',
                    'TCEffectiveDate' => '2024-02-22',
                ],
                [
                    'TermsConditionNumber' => '123459',
                    'Description' => 'Afisa mikopo anaweza kukataa kipitisha maombi ya mkopo kwa kuanisha sanbabu husika. Mfano,endapo mwanachama ana mkopo mwingine ulioshindwa kulipika',
                    'TCEffectiveDate' => '2024-02-22',
                ],
                [
                    'TermsConditionNumber' => '123460',
                    'Description' => 'Kinga ya mkopo na adaya mkopo inaweza kubadilika muda wowote kulingana na wakati',
                    'TCEffectiveDate' => '2024-02-22',
                ],
            ],
        ],
        // Additional entries can be added similarly
    ];

    // Add each MessageDetails entry to the Data element
    foreach ($messageDetailsArray as $details) {
        $messageDetails = $dom->createElement('MessageDetails');
        $data->appendChild($messageDetails);

        foreach ($details as $key => $value) {
            if ($key === 'TermsConditions') {
                foreach ($value as $term) {
                    $termsCondition = $dom->createElement('TermsCondition');
                    $messageDetails->appendChild($termsCondition);
                    foreach ($term as $termKey => $termValue) {
                        $termsCondition->appendChild($dom->createElement($termKey, $termValue));
                    }
                }
            } else {
                $messageDetails->appendChild($dom->createElement($key, $value));
            }
        }
    }

    // Canonicalize the Data element to prepare for signing
    $dataElement = $data->C14N();

    // Load the private key for signing
    $privateKey = file_get_contents('/home/crm/emkopo.key');
    $pkeyid = openssl_pkey_get_private($privateKey);

    if (!$pkeyid) {
        Log::error('Failed to load private key');
        return response()->json(['error' => 'Failed to load private key'], 500);
    }

    // Sign the data using SHA256withRSA
    $signature = '';
    openssl_sign($dataElement, $signature, $pkeyid, OPENSSL_ALGO_SHA256);
    openssl_free_key($pkeyid);

    // Encode the signature in Base64
    $base64Signature = base64_encode($signature);

    // Check the signature length
    if (strlen($base64Signature) !== 344) {
        Log::error('Signature length mismatch');
        return response()->json(['error' => 'Signature length mismatch'], 500);
    }

    // Append the Signature element
    $signatureElement = $dom->createElement('Signature', $base64Signature);
    $document->appendChild($signatureElement);

    // Convert the entire Document to a string
    $xmlContent = $dom->saveXML();

    // Initialize GuzzleHTTP client to send the request
    $client = new Client();

    try {
        // $response = $client->request('POST', 'http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [

            $response = $client->request('POST', 'http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
            'headers' => ['Content-Type' => 'application/xml'],
            'body' => $xmlContent,
        ]);

        // Handle and return the API response
        return response()->json([
            'status' => $response->getStatusCode(),
            'response' => $response->getBody()->getContents(),
        ]);
    } catch (\Exception $e) {
        Log::error('Error sending request: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


public function triggerDecommission()
{
    $productCodes = ['769']; // Predefined product codes

    $client = new Client();
    $xmlContent = $this->buildXmlContent($productCodes);

    try {
        $response = $client->post('http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
            'headers' => ['Content-Type' => 'application/xml'],
            'body' => $xmlContent,
        ]);

        Log::info('Decommission request sent successfully for products: ' . implode(', ', $productCodes));
        return response()->json(['message' => 'Decommission request sent successfully', 'response' => $response->getBody()->getContents()], 200);
    } catch (\Exception $e) {
        Log::error('Error sending decommission request: ' . $e->getMessage());
        return response()->json(['error' => 'Failed to send decommission request', 'message' => $e->getMessage()], 500);
    }
}

private function buildXmlContent(array $productCodes)
{
    $dom = new \DOMDocument('1.0', 'UTF-8');
    $document = $dom->createElement('Document');
    $dom->appendChild($document);
    $data = $dom->createElement('Data');
    $document->appendChild($data);
    $header = $dom->createElement('Header');
    $data->appendChild($header);

    $header->appendChild($dom->createElement('Sender', 'URA_SACCOS_LTD_LOAN'));
    $header->appendChild($dom->createElement('Receiver', 'ESS_UTUMISHI'));
    $header->appendChild($dom->createElement('FSPCode', 'FL7456'));
    // $header->appendChild($dom->createElement('MsgId', 'FSP1234670498')); // Correct MsgId as per API
    $header->appendChild($dom->createElement('MsgId', 'FSP' . uniqid('', true)));


    $header->appendChild($dom->createElement('MessageType', 'PRODUCT_DECOMMISSION'));


    foreach ($productCodes as $code) {
        $messageDetails = $dom->createElement('MessageDetails');
        $messageDetails->appendChild($dom->createElement('ProductCode', $code));
        $data->appendChild($messageDetails);
    }

    // Generate and append the signature
    $this->appendSignature($dom, $data);

    return $dom->saveXML();
}

private function appendSignature(\DOMDocument $dom, \DOMNode $data)
{
    $privateKeyPath = '/home/crm/emkopo.key'; // Adjust path as necessary
    $privateKey = file_get_contents($privateKeyPath);
    $pkeyid = openssl_pkey_get_private($privateKey);
    if (!$pkeyid) {
        throw new \Exception("Private key not loaded.");
    }

    $dataElement = $data->C14N();
    $signature = '';
    openssl_sign($dataElement, $signature, $pkeyid, OPENSSL_ALGO_SHA256);
    openssl_free_key($pkeyid);

    $base64Signature = base64_encode($signature);
    $signatureElement = $dom->createElement('Signature', $base64Signature);
    $dom->documentElement->appendChild($signatureElement);
}
}

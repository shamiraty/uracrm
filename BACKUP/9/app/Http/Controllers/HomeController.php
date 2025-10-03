<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;

use App\Models\Enquiry;
use App\Models\LoanApplication;
use Illuminate\Http\Request;
use Carbon\Carbon;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
class HomeController extends Controller
{
    public function index()
    {
         $monthlyLoanData = LoanApplication::getMonthlyDataForCurrentYear();
        // Metrics for the dashboard
        $enquiryFrequencyApproved = $this->getMonthlyFrequency('Enquiry', 'approved');
        $loanApplicationFrequencyPending = $this->getLoanApplicationFrequency('pending');
        $monthlyLoanApplications = $this->getMonthlyLoanApplicationFrequencies(); // New data
        $enquiryTypeFrequency = $this->getEnquiryTypeFrequency();
        $loanApplicationStatusFrequency = $this->getLoanApplicationStatusFrequency();
        
        // Loan Application Pipeline Data
        $loanPipelineData = $this->getLoanApplicationPipeline();
        
        // Metrics Cards - Real Data
        $enquiryFrequencyAllTime = $this->getEnquiryFrequencyAllTime();
        $loanApplicationFrequencyAllTime = $this->getLoanApplicationFrequencyAllTime();
        $enquiryTypeMembership = $this->getEnquiryTypeFrequencyByType('join_membership');
        $enquiryTypeShare = $this->getEnquiryTypeFrequencyByType('share_enquiry');
        $enquiryTypeDeduction = $this->getEnquiryTypeFrequencyByType('deduction_add');

        // Additional Real KPI Data
        $totalMembers = \App\Models\Member::count();
        $activeLoans = LoanApplication::whereIn('status', ['approved', 'disbursed', 'active'])->count();
        // Get total shares from enquiries with share type
        $totalShares = \App\Models\Payment::whereHas('enquiry', function($query) {
            $query->where('type', 'share_enquiry');
        })->sum('amount');
        $monthlyRevenue = \App\Models\Payment::whereMonth('payment_date', Carbon::now()->month)
                                               ->whereYear('payment_date', Carbon::now()->year)
                                               ->sum('amount');
        
        // Calculate trends - Since members table doesn't have created_at, use enquiries for member trends
        // Using created_at from enquiries table which should have timestamps
        $lastMonthMembers = Enquiry::where('type', 'join_membership')
                                   ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                                   ->whereYear('created_at', Carbon::now()->subMonth()->year)
                                   ->count();
        $currentMonthMembers = Enquiry::where('type', 'join_membership')
                                      ->whereMonth('created_at', Carbon::now()->month)
                                      ->whereYear('created_at', Carbon::now()->year)
                                      ->count();
        $membersTrend = $lastMonthMembers > 0 ? round((($currentMonthMembers - $lastMonthMembers) / $lastMonthMembers) * 100, 1) : 0;
        
        $lastMonthLoans = LoanApplication::whereIn('status', ['approved', 'disbursed', 'active'])
                                          ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                                          ->whereYear('created_at', Carbon::now()->subMonth()->year)
                                          ->count();
        $currentMonthLoans = LoanApplication::whereIn('status', ['approved', 'disbursed', 'active'])
                                            ->whereMonth('created_at', Carbon::now()->month)
                                            ->whereYear('created_at', Carbon::now()->year)
                                            ->count();
        $loansTrend = $lastMonthLoans > 0 ? round((($currentMonthLoans - $lastMonthLoans) / $lastMonthLoans) * 100, 1) : 0;
        
        $lastMonthRevenue = \App\Models\Payment::whereMonth('payment_date', Carbon::now()->subMonth()->month)
                                                ->whereYear('payment_date', Carbon::now()->subMonth()->year)
                                                ->sum('amount');
        $revenueTrend = $lastMonthRevenue > 0 ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;
        
        $lastMonthShares = \App\Models\Payment::whereHas('enquiry', function($query) {
                                                   $query->where('type', 'share_enquiry');
                                               })
                                               ->whereMonth('payment_date', Carbon::now()->subMonth()->month)
                                               ->whereYear('payment_date', Carbon::now()->subMonth()->year)
                                               ->sum('amount');
        $currentMonthShares = \App\Models\Payment::whereHas('enquiry', function($query) {
                                                      $query->where('type', 'share_enquiry');
                                                  })
                                                  ->whereMonth('payment_date', Carbon::now()->month)
                                                  ->whereYear('payment_date', Carbon::now()->year)
                                                  ->sum('amount');
        $sharesTrend = $lastMonthShares > 0 ? round((($currentMonthShares - $lastMonthShares) / $lastMonthShares) * 100, 1) : 0;

        //last 10  enquires,  
         $enquiries = Enquiry::orderBy('date_received', 'desc')->limit(10)->get();

        // Get overdue enquiries data for top 10 users
        $overdueData = $this->getTopUsersWithOverdueEnquiries();

        // Pass the data to the view
        return view('dashboard', compact(
            'enquiryFrequencyApproved',
            'loanApplicationFrequencyPending',
            'monthlyLoanApplications', // Include monthly data
            'enquiryTypeFrequency',
            'loanApplicationStatusFrequency',
            'loanPipelineData',
            'enquiryFrequencyAllTime',
            'loanApplicationFrequencyAllTime',
            'enquiryTypeMembership',
            'enquiryTypeShare',
            'enquiryTypeDeduction',
            'enquiries',
            'overdueData',
            'totalMembers',
            'activeLoans',
            'totalShares',
            'monthlyRevenue',
            'membersTrend',
            'loansTrend',
            'revenueTrend',
            'sharesTrend'
        ));
    }

    private function getMonthlyFrequency($model, $status)
    {
        return Enquiry::where('status', $status)
            ->whereYear('date_received', Carbon::now()->year)
            ->selectRaw('MONTH(date_received) as month, COUNT(*) as frequency')
            ->groupBy('month')
            ->get();
    }

    private function getLoanApplicationFrequency($status)
    {
        return LoanApplication::whereIn('status', [$status, 'paid'])
            ->whereYear('created_at', Carbon::now()->year)
            ->selectRaw('COUNT(*) as frequency')
            ->first();
    }

    private function getMonthlyLoanApplicationFrequencies()
    {
        // Initialize arrays for the counts
        $monthlyPaidFrequencies = array_fill(0, 12, 0);
        $monthlyPendingFrequencies = array_fill(0, 12, 0);
        $currentYear = Carbon::now()->year;

        // Get paid loan applications by month
        $paidApplications = LoanApplication::where('status', 'paid')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as frequency')
            ->groupBy('month')
            ->get();

        foreach ($paidApplications as $application) {
            $monthlyPaidFrequencies[$application->month - 1] = $application->frequency; // month is 1-indexed
        }

        // Get pending loan applications by month
        $pendingApplications = LoanApplication::where('status', 'pending')
            ->whereYear('created_at', $currentYear)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as frequency')
            ->groupBy('month')
            ->get();

        foreach ($pendingApplications as $application) {
            $monthlyPendingFrequencies[$application->month - 1] = $application->frequency; // month is 1-indexed
        }

        return [
            'paid' => $monthlyPaidFrequencies,
            'pending' => $monthlyPendingFrequencies,
        ];
    }

    private function getEnquiryTypeFrequency()
    {
        // Count all enquiry types including loan_application from Enquiry table
        // This shows all enquiries submitted to CRM
        return Enquiry::select('type')
            ->selectRaw('COUNT(*) as frequency')
            ->groupBy('type')
            ->get();
    }

    private function getLoanApplicationStatusFrequency()
    {
        return LoanApplication::select('status')
            ->selectRaw('COUNT(*) as frequency')
            ->groupBy('status')
            ->get();
    }

    private function getEnquiryFrequencyAllTime()
    {
        return Enquiry::selectRaw('COUNT(*) as frequency')->first();
    }

    private function getLoanApplicationFrequencyAllTime()
    {
        return LoanApplication::selectRaw('COUNT(*) as frequency')->first();
    }

    private function getEnquiryTypeFrequencyByType($type)
    {
        return Enquiry::where('type', $type)
            ->selectRaw('COUNT(*) as frequency')
            ->first();
    }
    
    private function getLoanApplicationPipeline()
    {
        // Get total loan applications from enquiries (all submitted to CRM)
        $totalLoanEnquiries = Enquiry::where('type', 'loan_application')->count();
        
        // Get loan applications by status from LoanApplication table (in progress)
        $statusCounts = LoanApplication::select('status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
        
        // Calculate loans not yet processed (in enquiries but not in LoanApplication)
        $totalInProgress = array_sum($statusCounts);
        $notProcessed = max(0, $totalLoanEnquiries - $totalInProgress);
        
        return [
            'total_enquiries' => $totalLoanEnquiries,
            'not_processed' => $notProcessed,
            'in_progress' => $totalInProgress,
            'status_breakdown' => $statusCounts,
            'approved' => $statusCounts['approved'] ?? 0,
            'pending' => $statusCounts['pending'] ?? 0,
            'rejected' => $statusCounts['rejected'] ?? 0,
            'disbursed' => $statusCounts['disbursed'] ?? 0,
            'paid' => $statusCounts['paid'] ?? 0,
        ];
    }



// public function consumeExternalService()
// {
//     // Step 1: Build the XML message
//     $xml = new \SimpleXMLElement('<Document/>');

//     // Add namespace if required (replace 'your-namespace-here' with actual namespace)
//     // $xml = new \SimpleXMLElement('<Document xmlns="your-namespace-here"/>');

//     $data = $xml->addChild('Data');
//     $dataDom = dom_import_simplexml($data);
//     $dataDom->setAttribute('Id', 'data'); // Assign an ID for signing reference

//     $header = $data->addChild('Header');
//     $header->addChild('Sender', 'FSPSystem');
//     $header->addChild('Receiver', 'ESS_UTUMISHI');
//     $header->addChild('FSPCode', 'FL7407');
//     $header->addChild('MsgId', 'FSP123467020');
//     $header->addChild('MessageType', 'PRODUCT_DETAIL');

//     // Add first MessageDetails
//     $messageDetails1 = $data->addChild('MessageDetails');
//     $messageDetails1->addChild('DeductionCode', 'FL0001');
//     $messageDetails1->addChild('ProductCode', 'LA1001');
//     $messageDetails1->addChild('ProductName', 'Mkopo wa Simu');
//     $messageDetails1->addChild('ProductDescription', 'Mkopo Elimu');
//     $messageDetails1->addChild('ForExecutive', 'false');
//     $messageDetails1->addChild('MinimumTenure', '12');
//     $messageDetails1->addChild('MaximumTenure', '24');
//     $messageDetails1->addChild('InterestRate', '10.00');
//     $messageDetails1->addChild('ProcessFee', '15.00');
//     $messageDetails1->addChild('Insurance', '0.75');
//     $messageDetails1->addChild('MaxAmount', '5000000');
//     $messageDetails1->addChild('MinAmount', '100000');
//     $messageDetails1->addChild('RepaymentType', 'Flat');
//     $messageDetails1->addChild('Currency', 'TZS');
//     $messageDetails1->addChild('InsuranceType', 'DISTRIBUTED || UP_FRONT');

//     // Add TermsCondition nodes
//     $termsCondition1 = $messageDetails1->addChild('TermsCondition');
//     $termsCondition1->addChild('TermsConditionNumber', '123456');
//     $termsCondition1->addChild('Description', 'Payment Must be Made in Full');
//     $termsCondition1->addChild('TCEffectiveDate', '2024-02-22');

//     $termsCondition2 = $messageDetails1->addChild('TermsCondition');
//     $termsCondition2->addChild('TermsConditionNumber', '123457');
//     $termsCondition2->addChild('Description', 'Loan must be paid within time');
//     $termsCondition2->addChild('TCEffectiveDate', '2024-02-22');

//     // Convert the XML object to a string
//     $xmlString = $xml->asXML();

//     // Step 2: Sign the XML message
//     $signedXml = $this->signXmlMessage($xmlString);

//     // Optional: Save the signed XML for inspection
//     file_put_contents(storage_path('app/signed_xml.xml'), $signedXml);

//     // Step 3: Send the signed XML via HTTP POST
//     $client = new Client();

//     try {
//         $response = $client->request('POST', 'http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
//             'headers' => [
//                 'Content-Type' => 'application/xml',
//             ],
//             'body' => $signedXml,
//         ]);

//         // Get the response body
//         $body = $response->getBody()->getContents();

//         // Parse the response as XML
//         $xmlResponse = simplexml_load_string($body);

//         // Convert XML to JSON and then to an array
//         $json = json_encode($xmlResponse);
//         $data = json_decode($json, true);

//         // Pass the data to the view
//         return view('body.testapi', ['data' => $data]);

//     } catch (GuzzleException $e) {
//         if ($e->hasResponse()) {
//             $statusCode   = $e->getResponse()->getStatusCode();
//             $responseBody = $e->getResponse()->getBody()->getContents();
//             \Log::error("Guzzle HTTP error ($statusCode): $responseBody");
//             return response()->json(['error' => $responseBody], $statusCode);
//         } else {
//             \Log::error('Guzzle error: ' . $e->getMessage());
//             return response()->json(['error' => $e->getMessage()], 500);
//         }
//     }
// }

// public function signXmlMessage($xmlString)
// {
//     $doc = new \DOMDocument();
//     $doc->preserveWhiteSpace = false;
//     $doc->formatOutput = false;

//     if (!$doc->loadXML($xmlString)) {
//         \Log::error('Failed to load XML string into DOMDocument.');
//         return response()->json(['error' => 'Invalid XML.'], 500);
//     }

//     $objDSig = new XMLSecurityDSig();
//     $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

//     // Add reference with URI pointing to the element with Id='data'
//     $objDSig->addReference(
//         $doc,
//         XMLSecurityDSig::SHA256,
//         ['http://www.w3.org/2000/09/xmldsig#enveloped-signature'],
//         ['uri' => '#data']
//     );

//     // Create a new Security key
//     $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, [
//         'type' => 'private',
//     ]);

//     // Load the private key
//     $privateKeyPath = '/home/crm/emkopo.key'; // Update this path if needed
//     try {
//         $objKey->loadKey($privateKeyPath, true);
//     } catch (\Exception $e) {
//         \Log::error('Error loading private key: ' . $e->getMessage());
//         return response()->json(['error' => 'Error loading private key.'], 500);
//     }

//     // Sign the XML
//     try {
//         $objDSig->sign($objKey);
//     } catch (\Exception $e) {
//         \Log::error('Error during signing: ' . $e->getMessage());
//         return response()->json(['error' => 'Error during signing.'], 500);
//     }

//     // Add the associated public key to the signature (KeyInfo)
//     $certPath = '/home/crm/emkopo.crt'; // Update this path if needed
//     try {
//         $certContent = file_get_contents($certPath);
//         if ($certContent === false) {
//             throw new \Exception('Failed to read certificate file.');
//         }
//         $objDSig->add509Cert($certContent, true, false, ['subjectName' => true]);
//     } catch (\Exception $e) {
//         \Log::error('Error loading public certificate: ' . $e->getMessage());
//         return response()->json(['error' => 'Error loading public certificate.'], 500);
//     }

//     // Append the signature to the XML (place it after the <Data> element)
//     $rootElement = $doc->documentElement;
//     $objDSig->appendSignature($rootElement);

//     // Verify that the SignatureValue is populated
//     $signatureValueNode = $doc->getElementsByTagName('SignatureValue')->item(0);
//     if ($signatureValueNode) {
//         $signatureValue = $signatureValueNode->nodeValue;
//         if (empty($signatureValue)) {
//             \Log::error('SignatureValue is empty.');
//             return response()->json(['error' => 'SignatureValue is empty.'], 500);
//         }
//     } else {
//         \Log::error('SignatureValue element not found.');
//         return response()->json(['error' => 'SignatureValue element not found.'], 500);
//     }

//     // Optional: Validate the signature locally
//     $objXMLSecDSig = new XMLSecurityDSig();
//     $signatureNode = $objXMLSecDSig->locateSignature($doc);
//     if (!$signatureNode) {
//         \Log::error('Cannot locate Signature Node');
//         return response()->json(['error' => 'Cannot locate Signature Node.'], 500);
//     }

//     $objXMLSecDSig->canonicalizeSignedInfo();
//     $key = $objXMLSecDSig->locateKey();
//     $key->loadKey($certPath, true, true);

//     $result = $objXMLSecDSig->verify($key);
//     if ($result !== 1) {
//         \Log::error('Signature verification failed.');
//         return response()->json(['error' => 'Signature verification failed.'], 500);
//     } else {
//         \Log::info('Signature verified successfully.');
//     }

//     // Save the signed XML
//     $signedXmlContent = $doc->saveXML();

//     // Optional: Save the signed XML for inspection
//     file_put_contents(storage_path('app/signed_xml.xml'), $signedXmlContent);

//     return $signedXmlContent;
// }

// public function consumeExternalService()
// {
//     // Step 1: Build the XML message
//     $xml = new \SimpleXMLElement('<Document/>');

//     // Assign an ID to the Data element for signing reference
//     $data = $xml->addChild('Data');
//     $data->addAttribute('Id', 'data'); // Correct way to add an attribute in SimpleXMLElement

//     $header = $data->addChild('Header');
//     $header->addChild('Sender', 'FSPSystem');
//     $header->addChild('Receiver', 'ESS_UTUMISHI');
//     $header->addChild('FSPCode', 'FL7407');
//     $header->addChild('MsgId', 'FSP123467020');
//     $header->addChild('MessageType', 'PRODUCT_DETAIL');

//     // Add first MessageDetails
//     $messageDetails1 = $data->addChild('MessageDetails');
//     $messageDetails1->addChild('DeductionCode', 'FL0001');
//     $messageDetails1->addChild('ProductCode', 'LA1001');
//     $messageDetails1->addChild('ProductName', 'Mkopo wa Simu');
//     $messageDetails1->addChild('ProductDescription', 'Mkopo Elimu');
//     $messageDetails1->addChild('ForExecutive', 'false');
//     $messageDetails1->addChild('MinimumTenure', '12');
//     $messageDetails1->addChild('MaximumTenure', '24');
//     $messageDetails1->addChild('InterestRate', '10.00');
//     $messageDetails1->addChild('ProcessFee', '15.00');
//     $messageDetails1->addChild('Insurance', '0.75');
//     $messageDetails1->addChild('MaxAmount', '5000000');
//     $messageDetails1->addChild('MinAmount', '100000');
//     $messageDetails1->addChild('RepaymentType', 'Flat');
//     $messageDetails1->addChild('Currency', 'TZS');
//     $messageDetails1->addChild('InsuranceType', 'DISTRIBUTED || UP_FRONT');

//     // Add TermsCondition nodes
//     $termsCondition1 = $messageDetails1->addChild('TermsCondition');
//     $termsCondition1->addChild('TermsConditionNumber', '123456');
//     $termsCondition1->addChild('Description', 'Payment Must be Made in Full');
//     $termsCondition1->addChild('TCEffectiveDate', '2024-02-22');

//     $termsCondition2 = $messageDetails1->addChild('TermsCondition');
//     $termsCondition2->addChild('TermsConditionNumber', '123457');
//     $termsCondition2->addChild('Description', 'Loan must be paid within time');
//     $termsCondition2->addChild('TCEffectiveDate', '2024-02-22');

//     // Add second MessageDetails if needed
//     // ...

//     // Convert the XML object to a string
//     $xmlString = $xml->asXML();

//     // Step 2: Sign the XML message
//     $signedXml = $this->signXmlMessage($xmlString);

//     // Optional: Save the signed XML for inspection
//     file_put_contents(storage_path('app/signed_xml.xml'), $signedXml);

//     // Step 3: Send the signed XML via HTTP POST
//     $client = new Client();

//     try {
//         $response = $client->request('POST', 'http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
//             'headers' => [
//                 'Content-Type' => 'application/xml',
//             ],
//             'body' => $signedXml,
//         ]);

//         // Get the response body
//         $body = $response->getBody()->getContents();

//         // Parse the response as XML
//         $xmlResponse = simplexml_load_string($body);

//         // Convert XML to JSON and then to an array
//         $json = json_encode($xmlResponse);
//         $data = json_decode($json, true);

//         // Pass the data to the view
//         return view('body.testapi', ['data' => $data]);

//     } catch (GuzzleException $e) {
//         if ($e->hasResponse()) {
//             $statusCode   = $e->getResponse()->getStatusCode();
//             $responseBody = $e->getResponse()->getBody()->getContents();
//             \Log::error("Guzzle HTTP error ($statusCode): $responseBody");
//             return response()->json(['error' => $responseBody], $statusCode);
//         } else {
//             \Log::error('Guzzle error: ' . $e->getMessage());
//             return response()->json(['error' => $e->getMessage()], 500);
//         }
//     }
// }

// public function signXmlMessage($xmlString)
// {
//     $doc = new \DOMDocument();
//     $doc->preserveWhiteSpace = false;
//     $doc->formatOutput = false;

//     if (!$doc->loadXML($xmlString)) {
//         \Log::error('Failed to load XML string into DOMDocument.');
//         return response()->json(['error' => 'Invalid XML.'], 500);
//     }

//     $xpath = new \DOMXPath($doc);

//     // Find the Data element with Id='data'
//     $dataElement = $xpath->query("//*[@Id='data']")->item(0);

//     if (!$dataElement) {
//         \Log::error('Data element with Id="data" not found.');
//         return response()->json(['error' => 'Data element with Id="data" not found.'], 500);
//     }

//     $objDSig = new XMLSecurityDSig();
//     $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

//     // Add reference to the Data element
//     $objDSig->addReference(
//         $dataElement,
//         XMLSecurityDSig::SHA256,
//         ['http://www.w3.org/2001/10/xml-exc-c14n#'],
//         ['uri' => '#data']
//     );

//     // Create a new Security key
//     $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, [
//         'type' => 'private',
//     ]);

//     // Load the private key content
//     $privateKeyPath = '/home/crm/emkopo.key'; // Update this path if needed
//     $privateKeyContent = file_get_contents($privateKeyPath);
//     if ($privateKeyContent === false) {
//         \Log::error('Error reading private key file.');
//         return response()->json(['error' => 'Error reading private key file.'], 500);
//     }

//     try {
//         $objKey->loadKey($privateKeyContent, false); // 'false' since we're loading the key content, not a file
//     } catch (\Exception $e) {
//         \Log::error('Error loading private key: ' . $e->getMessage());
//         return response()->json(['error' => 'Error loading private key.'], 500);
//     }

//     // Sign the XML
//     try {
//         $objDSig->sign($objKey, $dataElement); // Sign the Data element
//     } catch (\Exception $e) {
//         \Log::error('Error during signing: ' . $e->getMessage());
//         return response()->json(['error' => 'Error during signing.'], 500);
//     }

//     // Add the associated public key to the signature (KeyInfo)
//     $certPath = '/home/crm/emkopo.crt'; // Update this path if needed
//     $certContent = file_get_contents($certPath);
//     if ($certContent === false) {
//         \Log::error('Error reading certificate file.');
//         return response()->json(['error' => 'Error reading certificate file.'], 500);
//     }

//     try {
//         $objDSig->add509Cert($certContent, true, false, ['subjectName' => true]);
//     } catch (\Exception $e) {
//         \Log::error('Error loading public certificate: ' . $e->getMessage());
//         return response()->json(['error' => 'Error loading public certificate.'], 500);
//     }

//     // Append the signature to the Document element, after the Data element
//     $rootElement = $doc->documentElement;
//     $objDSig->appendSignature($rootElement);

//     // Move the Signature element after the Data element
//     $signatureElement = $doc->getElementsByTagName('Signature')->item(0);
//     if ($dataElement && $signatureElement) {
//         $rootElement->removeChild($signatureElement);
//         if ($dataElement->nextSibling) {
//             $rootElement->insertBefore($signatureElement, $dataElement->nextSibling);
//         } else {
//             $rootElement->appendChild($signatureElement);
//         }
//     }

//     // Verify that the SignatureValue is populated
//     $signatureValueNode = $doc->getElementsByTagName('SignatureValue')->item(0);
//     if ($signatureValueNode) {
//         $signatureValue = $signatureValueNode->nodeValue;
//         if (empty($signatureValue)) {
//             \Log::error('SignatureValue is empty.');
//             return response()->json(['error' => 'SignatureValue is empty.'], 500);
//         }
//     } else {
//         \Log::error('SignatureValue element not found.');
//         return response()->json(['error' => 'SignatureValue element not found.'], 500);
//     }

//     // Optional: Validate the signature locally
//     $objXMLSecDSig = new XMLSecurityDSig();
//     $signatureNode = $objXMLSecDSig->locateSignature($doc);
//     if (!$signatureNode) {
//         \Log::error('Cannot locate Signature Node');
//         return response()->json(['error' => 'Cannot locate Signature Node.'], 500);
//     }

//     $objXMLSecDSig->canonicalizeSignedInfo();
//     $key = $objXMLSecDSig->locateKey();
//     $key->loadKey($certContent, false, true); // Load the certificate content

//     $result = $objXMLSecDSig->verify($key);
//     if ($result !== 1) {
//         \Log::error('Signature verification failed.');
//         return response()->json(['error' => 'Signature verification failed.'], 500);
//     } else {
//         \Log::info('Signature verified successfully.');
//     }

//     // Save the signed XML
//     $signedXmlContent = $doc->saveXML();

//     // Optional: Save the signed XML for inspection
//     file_put_contents(storage_path('app/signed_xml.xml'), $signedXmlContent);

//     return $signedXmlContent;
// }

public function consumeExternalService()
{
    $xml = $this->generateXmlPayload();

    // Sign the XML message
    try {
        $signedXml = $this->signXmlMessage($xml);
    } catch (\Exception $e) {
        \Log::error("Error signing XML: " . $e->getMessage());
        return response()->json(['error' => 'Signing error: ' . $e->getMessage()], 500);
    }

    // Optional: Save the signed XML for inspection
    file_put_contents(storage_path('app/signed_xml.xml'), $signedXml);

    // Send the signed XML
    return $this->sendSignedXml($signedXml);
}

protected function generateXmlPayload()
{
    $xml = new \SimpleXMLElement('<Document/>');
    $data = $xml->addChild('Data');
    $data->addAttribute('Id', 'data');
    $header = $data->addChild('Header');
    $header->addChild('Sender', 'FSPSystem');
    $header->addChild('Receiver', 'ESS_UTUMISHI');
    $header->addChild('FSPCode', 'FL7407');
    $header->addChild('MsgId', 'FSP123467020');
    $header->addChild('MessageType', 'PRODUCT_DETAIL');

    // Message details, add more as required
    $messageDetails1 = $data->addChild('MessageDetails');
    $messageDetails1->addChild('DeductionCode', 'FL0001');
    $messageDetails1->addChild('ProductCode', 'LA1001');
    // Additional message details...

    return $xml->asXML();
}

protected function sendSignedXml($signedXml)
{
    $client = new \GuzzleHttp\Client();
    try {
        $response = $client->request('POST', 'http://154.118.230.140:9802/ess-loans/mvtyztwq/consume', [
            'headers' => ['Content-Type' => 'application/xml'],
            'body' => $signedXml,
        ]);
        return $this->parseXmlResponse($response->getBody()->getContents());
    } catch (\GuzzleHttp\Exception\GuzzleException $e) {
        $this->logErrorResponse($e);
        return response()->json(['error' => 'Request failed: ' . $e->getMessage()], 500);
    }
    $signatureValueNode = $doc->getElementsByTagName('Signature')->item(0);
    if ($signatureValueNode) {
        $signatureValue = $signatureValueNode->nodeValue;
        \Log::info('Signature generated successfully: ' . $signatureValue);
        if (empty($signatureValue)) {
            \Log::error('SignatureValue is empty.');
            return response()->json(['error' => 'SignatureValue is empty.'], 500);
        }
    } else {
        \Log::error('SignatureValue element not found.');
        return response()->json(['error' => 'SignatureValue element not found.'], 500);
    }
}

protected function signXmlMessage($xmlString)
{
    $doc = new \DOMDocument();
    $doc->preserveWhiteSpace = false;
    $doc->formatOutput = false;

    if (!$doc->loadXML($xmlString)) {
        throw new \Exception('Invalid XML format.');
    }

    $xpath = new \DOMXPath($doc);
    $dataElement = $xpath->query("//*[@Id='data']")->item(0);
    if (!$dataElement) {
        throw new \Exception('Data element with Id="data" not found.');
    }

    // Initialize XML Security and Signature
    $objDSig = new XMLSecurityDSig();
    $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);
    $objDSig->addReference($dataElement, XMLSecurityDSig::SHA256, ['http://www.w3.org/2001/10/xml-exc-c14n#'], ['uri' => '#data']);

    // Load and add private key
    $objKey = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, ['type' => 'private']);
    $privateKeyPath = '/home/crm/emkopo.key';
    $objKey->loadKey(file_get_contents($privateKeyPath), false);
    $objDSig->sign($objKey, $dataElement);

    // Add public certificate to the signature
    $certPath = '/home/crm/emkopo.crt';
    $certContent = file_get_contents($certPath);
    $objDSig->add509Cert($certContent, true, false, ['subjectName' => true]);

    // Append signature and move after Data element
    $rootElement = $doc->documentElement;
    $objDSig->appendSignature($rootElement);
    $signatureElement = $doc->getElementsByTagName('Signature')->item(0);
    $rootElement->removeChild($signatureElement);
    $rootElement->insertBefore($signatureElement, $dataElement->nextSibling);

    return $doc->saveXML();
}

protected function parseXmlResponse($responseBody)
{
    $xmlResponse = simplexml_load_string($responseBody);
    return json_decode(json_encode($xmlResponse), true);
}

protected function logErrorResponse($exception)
{
    if ($exception->hasResponse()) {
        $statusCode = $exception->getResponse()->getStatusCode();
        $responseBody = $exception->getResponse()->getBody()->getContents();
        \Log::error("Guzzle HTTP error ($statusCode): $responseBody");
    } else {
        \Log::error('Guzzle error: ' . $exception->getMessage());
    }
}

public function showData()
{
    $data = $this->consumeExternalServiceWithSSL(); // Assume this returns an array of data

    // Return the view and pass the data to it
    return view('welcome', ['data' => $data]);
}

private function getTopUsersWithOverdueEnquiries()
{
    $threeDaysAgo = Carbon::now()->subDays(3);
    
    // Get all overdue enquiries with assigned users
    $overdueEnquiries = Enquiry::with('assignedUsers')
        ->where('created_at', '<=', $threeDaysAgo)
        ->whereNotIn('status', ['completed', 'rejected'])
        ->get();
    
    // Count overdue enquiries per user
    $userOverdueCounts = [];
    
    foreach ($overdueEnquiries as $enquiry) {
        foreach ($enquiry->assignedUsers as $user) {
            if (!isset($userOverdueCounts[$user->id])) {
                $userOverdueCounts[$user->id] = [
                    'name' => $user->name,
                    'count' => 0
                ];
            }
            $userOverdueCounts[$user->id]['count']++;
        }
    }
    
    // Sort by count and take top 10
    usort($userOverdueCounts, function($a, $b) {
        return $b['count'] - $a['count'];
    });
    
    $topUsers = array_slice($userOverdueCounts, 0, 10);
    
    // Format data for the chart
    $labels = array_column($topUsers, 'name');
    $data = array_column($topUsers, 'count');
    
    return [
        'labels' => $labels,
        'data' => $data
    ];
}

}
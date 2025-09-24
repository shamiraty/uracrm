<?php

require 'vendor/autoload.php'; // Make sure this path is correct to load Guzzle

use GuzzleHttp\Client;

$client = new Client();
$response = $client->request('POST', 'https://41.59.228.68:8082/api/v1/sendSMS', [
    'verify' => false, // Disable SSL verification
    'form_params' => [
        'msisdn' => '255755400927',
        'message' => 'Asante Mungu',
        'key' => 'xYz123#',
    ]
]);

echo $response->getBody();

<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'nmb' => [
        'base_url' => env('NMB_BASE_URL', 'https://uat.nmbbank.co.tz'),
        'shared_secret' => env('NMB_SHARED_SECRET'),
        'user_id' => env('NMB_USER_ID'),  // JWT auth user (e.g., C001957567C)
        'operational_user_id' => env('NMB_OPERATIONAL_USER_ID', 'C001957567C'), // Payload userid
        'client_id' => env('NMB_CLIENT_ID', 'urasaccoss'), // Payload clientId
        'institution' => env('NMB_INSTITUTION', 'URASACCOS'),
        'account_code' => env('NMB_ACCOUNT_CODE'),
        'account_description' => env('NMB_ACCOUNT_DESCRIPTION', 'URASACCOS Loan Disbursement'),
        'enabled' => env('NMB_ENABLED', false),
        'timeout' => env('NMB_TIMEOUT', 30),
        'retry_attempts' => env('NMB_RETRY_ATTEMPTS', 3),
        'batch_size_limit' => env('NMB_BATCH_SIZE_LIMIT', 100),
        'callback_url' => env('NMB_CALLBACK_URL'),
    ],

];

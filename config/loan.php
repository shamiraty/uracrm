<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Loan System Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the loan management system
    |
    */

    // Controller to use for ESS integration
    'use_improved_controller' => env('USE_IMPROVED_LOAN_CONTROLLER', false),
    
    // Disbursement service configuration
    'disbursement_service' => [
        'use_improved' => env('USE_IMPROVED_DISBURSEMENT', false),
        'channel_thresholds' => [
            'tiss_minimum' => 20000000, // 20 million TZS
        ]
    ],
    
    // ESS Integration
    'ess' => [
        'public_key_path' => env('ESS_PUBLIC_KEY_PATH', '/home/crm/ess_utumishi_go_tz.crt'),
        'fsp_code' => env('ESS_FSP_CODE', 'FL7456'),
        'sender_name' => env('ESS_SENDER_NAME', 'URA SACCOS LTD LOAN'),
        'receiver_name' => env('ESS_RECEIVER_NAME', 'ESS_UTUMISHI'),
    ],
    
    // NMB Configuration (already in services.php but can be referenced here)
    'nmb' => [
        'use_improved_service' => env('USE_IMPROVED_NMB_SERVICE', false),
    ]
];
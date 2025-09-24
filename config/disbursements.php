<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Disbursement Configuration
    |--------------------------------------------------------------------------
    |
    | Configure automated loan disbursement settings including batch times,
    | limits, notifications, and retry policies.
    |
    */
    
    // Schedule Configuration
    'schedule' => [
        'enabled' => env('DISBURSEMENT_SCHEDULE_ENABLED', true),
        'times' => [
            'morning' => '09:00',
            'afternoon' => '12:00',
            'evening' => '15:00',
        ],
        'timezone' => env('DISBURSEMENT_TIMEZONE', 'Africa/Dar_es_Salaam'),
    ],
    
    // Batch Processing
    'batch' => [
        'max_size' => env('DISBURSEMENT_BATCH_SIZE', 100),
        'timeout' => env('DISBURSEMENT_TIMEOUT', 300), // 5 minutes
        'retry_failed' => env('DISBURSEMENT_RETRY_FAILED', true),
        'retry_attempts' => env('DISBURSEMENT_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('DISBURSEMENT_RETRY_DELAY', 120), // 2 hours in minutes
    ],
    
    // Notifications
    'notifications' => [
        'enabled' => env('DISBURSEMENT_NOTIFICATIONS_ENABLED', true),
        'channels' => ['mail', 'database'], // Available: mail, database, sms
        'alert_email' => env('DISBURSEMENT_ALERT_EMAIL', 'finance@urasaccos.co.tz'),
        'notification_emails' => explode(',', env('DISBURSEMENT_NOTIFICATION_EMAILS', 'finance@urasaccos.co.tz,operations@urasaccos.co.tz')),
        'send_summary' => env('DISBURSEMENT_SEND_SUMMARY', true),
    ],
    
    // Business Rules
    'rules' => [
        'auto_approve_limit' => env('DISBURSEMENT_AUTO_APPROVE_LIMIT', 10000000), // 10 million TZS
        'require_dual_approval' => env('DISBURSEMENT_DUAL_APPROVAL', true),
        'max_daily_amount' => env('DISBURSEMENT_MAX_DAILY_AMOUNT', 1000000000), // 1 billion TZS
        'exclude_weekends' => env('DISBURSEMENT_EXCLUDE_WEEKENDS', false),
        'exclude_holidays' => env('DISBURSEMENT_EXCLUDE_HOLIDAYS', true),
    ],
    
    // Monitoring
    'monitoring' => [
        'track_performance' => env('DISBURSEMENT_TRACK_PERFORMANCE', true),
        'alert_on_failure' => env('DISBURSEMENT_ALERT_ON_FAILURE', true),
        'failure_threshold' => env('DISBURSEMENT_FAILURE_THRESHOLD', 10), // Alert if more than 10 failures
        'success_rate_threshold' => env('DISBURSEMENT_SUCCESS_RATE_THRESHOLD', 90), // Alert if success rate below 90%
    ],
    
    // Holidays (Tanzania public holidays)
    'holidays' => [
        '01-01', // New Year's Day
        '01-12', // Zanzibar Revolution Day
        '04-07', // Karume Day
        '04-26', // Union Day
        '05-01', // Labour Day
        '07-07', // Saba Saba Day
        '08-08', // Nane Nane Day
        '10-14', // Nyerere Day
        '12-09', // Independence Day
        '12-25', // Christmas Day
        '12-26', // Boxing Day
        // Add moveable holidays like Easter, Eid, etc.
    ],
    
    // Logging
    'logging' => [
        'enabled' => env('DISBURSEMENT_LOGGING_ENABLED', true),
        'channel' => env('DISBURSEMENT_LOG_CHANNEL', 'daily'),
        'level' => env('DISBURSEMENT_LOG_LEVEL', 'info'),
        'retain_days' => env('DISBURSEMENT_LOG_RETAIN_DAYS', 30),
    ],
    
];
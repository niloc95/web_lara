<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WebSchedulr Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the specific configuration settings for the
    | WebSchedulr application.
    |
    */
    
    'language' => env('APP_LOCALE', 'english'),
    
    // Google Calendar Integration
    'google' => [
        'sync_enabled' => env('GOOGLE_SYNC_FEATURE', false),
        'client_id' => env('GOOGLE_CLIENT_ID', ''),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', '')
    ],
];
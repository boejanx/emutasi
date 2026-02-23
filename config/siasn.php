<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SIASN API Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi ini digunakan untuk interaksi dengan SDK / API Web Service SIASN
    | (black-coffee04/ws-siasn-php). 
    | 
    */

    'consumer_key'    => env('SIASN_CONSUMER_KEY', ''),
    'consumer_secret' => env('SIASN_CONSUMER_SECRET', ''),
    'client_id'       => env('SIASN_CLIENT_ID', ''),
    
    // Berisi temporary SSO Token. Umumnya didapat dari SIASN SSO Auth API (opsional untuk hardcode)
    'sso_access_token' => env('SIASN_SSO_TOKEN', ''),
    
    // Kredensial tambahan jika diperlukan untuk generate API auth
    'sso_username'    => env('SIASN_SSO_USERNAME', ''),
    'sso_password'    => env('SIASN_SSO_PASSWORD', ''),
];

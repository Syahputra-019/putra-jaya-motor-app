<?php

// config/midtrans.php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Configuration
    |--------------------------------------------------------------------------
    */

    // Ambil dari dashboard Midtrans → Settings → Access Keys
    'server_key'    => env('MIDTRANS_SERVER_KEY', ''),
    'client_key'    => env('MIDTRANS_CLIENT_KEY', ''),

    // true = sandbox (testing), false = production
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),

    // Aktifkan 3DS untuk keamanan
    'is_3ds'        => env('MIDTRANS_IS_3DS', true),

    // Aktifkan sanitasi parameter
    'is_sanitized'  => env('MIDTRANS_IS_SANITIZED', true),
];
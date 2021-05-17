<?php

return [
    'encryption_key' => env('API_ENCRYPTION_KEY'),
    'public_key_prefix' => env('API_PUBLIC_KEY_PREFIX', 'api_key_pub'),
    'private_key_prefix' => env('API_PRIVATE_KEY_PREFIX', 'api_key_prv')
];

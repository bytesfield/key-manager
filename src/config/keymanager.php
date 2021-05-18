<?php

return [
    'encryption_key' => env('API_ENCRYPTION_KEY', '47f9c579776a486ce0592803c1174132ae190286dc87a498d938560f8bf31563'),
    'public_key_prefix' => env('API_PUBLIC_KEY_PREFIX', 'api_key_pub'),
    'private_key_prefix' => env('API_PRIVATE_KEY_PREFIX', 'api_key_prv')
];

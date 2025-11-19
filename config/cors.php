<?php

return [
    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:4000'], // frontend của bạn

    'allowed_headers' => ['*'],

    'exposed_headers' => [],
    
    'max_age' => 0,

    'supports_credentials' => true, // quan trọng khi fetch có credentials
];

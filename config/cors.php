<?php

return [
    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://project-mini-fe-62le.vercel.app', // frontend Vercel
        'http://localhost:4000',                    // frontend local
        'http://localhost:3000',                    // frontend local
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],
    
    'max_age' => 0,

    'supports_credentials' => true, // quan trọng khi fetch có credentials
];

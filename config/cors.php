<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie', '*'], // agrega '*' para cubrir rutas sin prefijo

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'https://front.proyelco.com',
        'https://front.prueba.proyelco.com',
        'http://localhost:5173',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];

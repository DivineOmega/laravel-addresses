<?php

return [
    'validation' => [
        'uk-postcode' => true,
        'country-code' => true,
    ],
    'geocoding' => [
        'enabled' => true,
        'google-maps' => [
            'api-key' => env('GEOCODING_GOOGLE_MAPS_API_KEY'),
        ],
        'strict' => false,
    ]
];
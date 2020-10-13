<?php

return [
    'gbr-validation' => [
        'enabled' => true
    ],
    'geocoding' => [
        'enabled' => true,
        'google-maps' => [
            'api-key' => env('GEOCODING_GOOGLE_MAPS_API_KEY'),
        ]
    ]
];
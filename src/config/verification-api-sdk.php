<?php

return [
    'verification-server' => [
        'host' => env('RESOURCES_VERIFICATION_EXTERNAL_HOST', 'https://verification.dots.live/'),
        'token' => env('VERIFICATION_INTERNAL_GATEWAY_TOKEN'),
    ],
];

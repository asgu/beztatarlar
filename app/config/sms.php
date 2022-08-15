<?php

return [
    'api' => env('SMS_API', 'https://smsc.ru'),
    'lifetime' => env('SMS_MESSAGE_LIFETIME', 30),

    'connection' => [
        'login' => env('SMS_CONNECTION_LOGIN', ''),
        'password' => env('SMS_CONNECTION_PASSWORD', ''),
    ],
];

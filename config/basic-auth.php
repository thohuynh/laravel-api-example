<?php

return [
    'envs_log'          => [
        'local', 'testing', 'develop', 'staging', 'production'
    ],
    'user_log'          => env('BASIC_AUTH_USER_LOG', 'admin'),
    'password_log'      => env('BASIC_AUTH_USER_LOG', 'Pass3ord!1234'),
    'error_message_log' => env('BASIC_AUTH_ERROR_MSG_LOG', 'You have to supply your credentials to access this resource.'),
];

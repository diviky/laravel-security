<?php

declare(strict_types=1);

return [

    // One Time Password request input name
    'otp_input' => 'one_time_password',

    // User's table column for google2fa secret
    'otp_secret_column' => 'two_factor_secret',

    // One Time Password View
    'view' => 'security::auth.2fa',

    // One Time Password error message
    'error_messages' => [
        'wrong_otp' => "The 'One Time Password' typed was wrong.",
        'cannot_be_empty' => 'One Time Password cannot be empty.',
    ],

    // Throw exceptions or just fire events?
    'throw_exceptions' => true,
];

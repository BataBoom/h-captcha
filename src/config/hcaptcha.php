<?php

return [
    'secret' => env('HCAPTCHA_SECRET'),
    'sitekey' => env('HCAPTCHA_SITEKEY'),
    'options' => [
        'timeout' => 30,
    ],
];
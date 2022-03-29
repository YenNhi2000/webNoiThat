<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => '1152695542153806', //client face của bạn
        'client_secret' => '85a1717053b11ebc789affe5d0da3d1a', //client app service face của bạn
        'redirect' => 'http://localhost:8080/websiteNoiThat/facebook/callback', //callback trả về
    ],

    'google' => [
        'client_id' => '15523630847-7td32tt600un3vdms2hrmitt1jqdsvnh.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'http://localhost:8080/websiteNoiThat/google/callback'
    ],
];

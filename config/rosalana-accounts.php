<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Rosalana Auth Settings
    |--------------------------------------------------------------------------
    |
    | Here you can define the settings for the Rosalana Auth.
    | Be sure to set the correct URL and token for the Rosalana Auth. To get the token
    | you can generate it in the Rosalana Support Settings.
    |
    */
    'auth' => [
        'url' => env('ROSALANA_ACCOUNTS_URL', 'http://localhost:8000'),
        'token' => env('ROSALANA_ACCOUNTS_TOKEN'),
        'origin' => env('FRONTEND_URL', 'http://localhost:3000'),
    ],

];

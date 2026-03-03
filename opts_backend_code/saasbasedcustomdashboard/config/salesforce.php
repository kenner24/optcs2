<?php

return [
    'client_id' => env('SALESFORCE_CLIENT_ID'),
    'client_secret' => env('SALESFORCE_CLIENT_SECRET'),
    'redirect_uri' => env('SALESFORCE_REDIRECT_URI', 'http://localhost:5173/connectors'),
    'api_base_url' =>  env('SALESFORCE_API_BASE_URL'),
    'login_api_base_url' =>  env('SALESFORCE_LOGIN_API_BASE_URL', 'https://login.salesforce.com/'),
    'api_version' =>  env('SALESFORCE_API_VERSION', 'v57.0'),
];

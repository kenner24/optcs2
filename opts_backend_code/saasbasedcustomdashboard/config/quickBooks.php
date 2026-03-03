<?php

return [
    'client_id' => env('QUICK_BOOKS_CLIENT_ID'),
    'base_url' => env('QUICK_BOOKS_BASE_URL', 'development'),
    'client_secret' => env('QUICK_BOOKS_CLIENT_SECRET'),
    'auth_request_url' => env('QUICK_BOOKS_AUTH_REQUEST_URL'),
    'token_endpoint_url' => env('QUICK_BOOKS_TOKEN_ENDPOINT_URL'),
    'oauth_scope' => env('QUICK_BOOKS_OAUTH_SCOPE', 'com.intuit.quickbooks.accounting openid profile email phone address'),
    'oauth_redirect_uri' => env('QUICK_BOOKS_OAUTH_REDIRECT_URI', 'http://localhost:5173/connectors'),
    'api_base_url' => env('QUICK_BOOKS_API_BASE_URL'),
    'minor_version' => env("QUICK_BOOKS_MINOR_VERSION", 65),
];

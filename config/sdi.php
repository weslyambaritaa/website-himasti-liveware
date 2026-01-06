<?php

return [
    // API
    'api_users_url' => env('API_USERS_URL', ''),
    // SSO
    'sso_client_id' => env('SSO_CLIENT_ID', ''),
    'sso_client_secret' => env('SSO_CLIENT_SECRET', ''),
    'sso_token_url' => env('SSO_TOKEN_URL', ''),
    'sso_authorize_url' => env('SSO_AUTHORIZATION_URL', ''),
    // FORCE HTTPS
    'force_https' => env('FORCE_HTTPS', false),
];

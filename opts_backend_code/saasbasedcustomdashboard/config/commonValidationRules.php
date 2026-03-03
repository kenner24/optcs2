<?php

return [
    'socialLoginCommonRules' => [
        'provider_name' => ['required', 'string'],
        'provider_id' => ['required', 'string'],
        'device_name' => ['required', 'string'],
        'email' => ['required', 'email:rfc,dns'],
    ],
];

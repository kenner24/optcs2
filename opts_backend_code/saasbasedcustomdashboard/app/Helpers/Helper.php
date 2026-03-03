<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

if (!function_exists('generateTokenWithTimeStamp')) {
    function generateTokenWithTimeStamp($email)
    {
        $token =  Str::random(40);
        $time = now()->addHours(2)->timestamp;
        return [
            'token' => $token,
            'encryptedToken' => Crypt::encryptString("{$token}|{$time}|{$email}")
        ];
    }
}

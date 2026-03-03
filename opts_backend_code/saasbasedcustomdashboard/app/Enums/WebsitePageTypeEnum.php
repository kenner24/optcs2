<?php

namespace App\Enums;


enum WebsitePageTypeEnum: string
{
    case ABOUT_US = 'about_us';
    case FAQ = 'faq';
    case PRIVACY_POLICY = 'privacy_policy';
    case TERMS_OF_USE = 'terms_of_use';
}

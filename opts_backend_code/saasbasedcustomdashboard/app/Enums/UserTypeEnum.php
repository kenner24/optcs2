<?php

namespace App\Enums;


enum UserTypeEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case COMPANY = 'company';
    case STAFF = 'staff';
}

<?php

namespace App\Enums;


enum UserStatusEnum: string
{
    case ACTIVE = 'active';
    case IN_ACTIVE = 'inactive';
}

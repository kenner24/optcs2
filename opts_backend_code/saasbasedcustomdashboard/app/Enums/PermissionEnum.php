<?php

namespace App\Enums;


enum PermissionEnum: string
{
    case REPORTS = 'reports';
    case CONNECTOR = 'connectors';
    case SUB_ACCOUNT = 'sub-account';
}

<?php

namespace App\Enums;


enum FileUploadPathEnum: string
{
    case ADMIN_FILE_UPLOAD = '/admin/uploads';
    case COMPANY_FILE_UPLOAD = '/company/images';
    case STAFF_FILE_UPLOAD = '/staff/images';
}

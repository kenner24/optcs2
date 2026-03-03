<?php

namespace App\Enums;


enum SalesForceQueryEnum: string
{
    case ALL_LEADS_QUERY = 'SELECT FIELDS(All) FROM Lead LIMIT 200';
}

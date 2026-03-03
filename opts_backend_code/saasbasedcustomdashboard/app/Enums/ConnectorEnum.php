<?php

namespace App\Enums;


enum ConnectorEnum: string
{
    case SALESFORCE = 'salesforce';
    case QUICKBOOKS = 'quickbooks';
    case GOOGLESHEET = 'googlesheet';
}

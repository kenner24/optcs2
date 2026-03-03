<?php

namespace App\Enums;


enum QuickBooksApiListEnum: string
{
    case CASH_FLOW_REPORT_API = '/v3/company/{companyId}/reports/CashFlow';
    case PROFIT_AND_LOSS_REPORT_API = '/v3/company/{companyId}/reports/ProfitAndLoss';
    case BALANCE_SHEET_REPORT = '/v3/company/{companyId}/reports/BalanceSheet';
    case READ_BUDGET_DETAIL_API = '/v3/company/{companyId}/budget/{budgetId}';
    case QUERY_API = '/v3/company/{companyId}/query';
}

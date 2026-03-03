<?php

namespace App\Enums;


enum QuickBooksReportNameEnum: string
{
    case BALANCE_SHEET = 'BalanceSheet';
    case PROFIT_AND_LOSS = 'ProfitAndLoss';
    case PROFIT_AND_LOSS_DETAIL = 'ProfitAndLossDetail';
    case TRIAL_BALANCE = 'Trial Balance';
    case CASH_FLOW = 'CashFlow';
    case INVENTORY_VALUATION = 'InventoryValuation';
    case CUSTOMER_SALES = 'CustomerSales';
    case ITEM_SALES = 'ItemSales';
    case DEPARTMENT_SALES = 'DepartmentSales';
    case CLASS_SALES = 'ClassSales';
    case CUSTOMER_INCOME = 'CustomerIncome';
    case CUSTOMER_BALANCE = 'CustomerBalance';
    case CUSTOMER_BALANCE_DETAIL = 'CustomerBalanceDetail';
    case AGED_RECEIVABLES = 'AgedReceivables';
    case AGED_RECEIVABLE_DETAIL = 'AgedReceivableDetail';
    case VENDOR_BALANCE = 'VendorBalance';
    case VENDOR_BALANCE_DETAIL = 'VendorBalanceDetail';
    case AGED_PAYABLES = 'AgedPayables';
    case AGED_PAYABLE_DETAIL = 'AgedPayableDetail';
    case VENDOR_EXPENSES = 'VendorExpenses';
    case ACCOUNT_LIST_DETAIL = 'AccountListDetail';
    case GENERAL_LEDGER_DETAIL = 'GeneralLedgerDetail';
    case TAX_SUMMARY = 'TaxSummary';
}

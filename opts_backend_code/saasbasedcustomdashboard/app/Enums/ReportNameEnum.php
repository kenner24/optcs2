<?php

namespace App\Enums;


enum ReportNameEnum: string
{
    case Submitted_Production = "submitted_production";
    case YTD_Submitted_Production = "ytd_submitted_production";
    case Paid_Annuity = "paid_annuity";
    case Paid_Annuity_Vs_DTI = "paid_annuity_dti";
    case Pending_Business = "pending_business";
    case Open_Opportunities = "open_opportunities";
    case Leads_Generated = "leads_generated";
    case First_Appointment_Scheduled = "1st_appt_scheduled";
    case Stick_Ratio_Show_Rate = "stick_ration_show_rate";
    case Seminar_Responses_Ratio = "seminar_responses_ratio";
    case Cost_Per_Client = "cost_per_client";
    case Profitability_Percentage = "profitability_percentage";
    case Days_To_Issue = "days_to_issue";
    case New_Assets_From_Existing_Clients = "new_assets_from_existing_clients";
}

<?php

namespace App\Enums;


enum SalesForceStagesEnum: string
{
    case CLOSED_LOST = 'Closed Lost';
    case CLOSED_WON = 'Closed Won';
    case QUALIFICATION = 'Qualification';
    case NEGOTIATION_REVIEW = 'Negotiation/Review';
    case DECISION_MAKERS = 'Id. Decision Makers';
    case PROPOSAL_PRICE_QUOTE = 'Proposal/Price Quote';
    case VALUE_PROPOSITION = 'Value Proposition';
}

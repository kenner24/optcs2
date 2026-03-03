<?php

namespace App\Enums;


enum SalesForceGraphqlQueryEnum: string
{
  case GET_ALL_LEADS_BY_YEAR_QUERY = 'query leads($year: Long) {
        uiapi {
          query {
            Lead(
              where: {
                CreatedDate: {
                  CALENDAR_YEAR:{
                    value:{
                      eq: $year
                    }
                  }
                }
              }
            ){
              edges {
                node {
                  Id
                  Name{
                    value
                  }
                  Email {
                    value
                  }
                  Industry {
                    value
                  }
                  Name {
                    value
                  }
                  Status {
                    value
                  }
                  AnnualRevenue{
                    value,
                  }
                  LeadSource{
                    value
                  }
                  CreatedDate{
                    value
                  }
                  ConvertedDate{
                    value
                  }
                }
              }
              totalCount
              pageInfo{
                hasNextPage
                hasPreviousPage
                startCursor
                endCursor
              }
            }
          }
        }
      }';
  case GET_PAGINATED_LEAD_DATA_QUERY = 'query leads($after: String, $year: Long) {
        uiapi {
          query {
            Lead(
              after: $after,
              where: {
                CreatedDate: {
                  CALENDAR_YEAR:{
                    value:{
                      eq: $year
                    }
                  }
                }
              }
            ){
              edges {
                node {
                  Id
                  Name{
                    value
                  }
                  Email {
                    value
                  }
                  Industry {
                    value
                  }
                  Name {
                    value
                  }
                  Status {
                    value
                  }
                  AnnualRevenue{
                    value,
                  }
                  LeadSource{
                    value
                  }
                  CreatedDate{
                    value
                  }
                  ConvertedDate{
                    value
                  }
                }
              }
              totalCount
              pageInfo{
                hasNextPage
                hasPreviousPage
                startCursor
                endCursor
              }
            }
          }
        }
      }';
  case GET_ALL_OPPORTUNITY_BY_YEAR_QUERY = 'query opportunity($year: Long) {
      uiapi {
        query {
          Opportunity(
            where: {
              CreatedDate: {
                CALENDAR_YEAR:{
                  value:{
                    eq: $year
                  }
                }
              }
            }
          ){
            edges {
              node {
                Id
                Name {
                  value
                }
                LeadSource{
                  value
                }
                CreatedDate{
                  value
                }
                CloseDate{
                  value
                }
                Amount{
                  value
                }
                ExpectedRevenue{
                  value
                }
                StageName{
                  value
                }
              }
            }
            totalCount
            pageInfo{
              hasNextPage
              hasPreviousPage
              startCursor
              endCursor
            }
          }
        }
      }
    }';
  case GET_PAGINATED_OPPORTUNITY_DATA_QUERY = 'query opportunity($after: String, $year: Long) {
      uiapi {
        query {
          Opportunity(
            after: $after,
            where: {
              CreatedDate: {
                CALENDAR_YEAR:{
                  value:{
                    eq: $year
                  }
                }
              }
            }
          ){
            edges {
              node {
                Id
                Name {
                  value
                }
                LeadSource{
                  value
                }
                CreatedDate{
                  value
                }
                CloseDate{
                  value
                }
                Amount{
                  value
                }
                ExpectedRevenue{
                  value
                }
                StageName{
                  value
                }
              }
            }
            totalCount
            pageInfo{
              hasNextPage
              hasPreviousPage
              startCursor
              endCursor
            }
          }
        }
      }
    }';
}

import { useSelector } from "react-redux";
import TableFirstTable from "../../Components/overview/TableFirstTable";
import TableTotalFamiliesServed from "../../Components/overview/TableTotalFamiliesServed";
import CashFlowForecastChart from "../../Components/reports/financial/CashFlowForecastChart";
import CashOnHandChart from "../../Components/reports/financial/CashOnHandChart";
import CurrentLiabilitiesChart from "../../Components/reports/financial/CurrentLiabilitiesChart";
import MonthlyExpensesChart from "../../Components/reports/financial/MonthlyExpensesChart";
import ProfitabilityPercentageChart from "../../Components/reports/financial/ProfitabilityPercentageChart";
import TableProduction from "../../Components/overview/TableProduction";
import TableLeadingIndicator from "../../Components/overview/TableLeadingIndicator";
import { selectUserDetails } from "../../redux/slices/UserProfileSlice";
import Top10OpenOpportunities from "../../Components/overview/Top10OpenOpportunities";
import Last10OpenOpportunities from "../../Components/overview/Last10OpenOpportunities";
import OpportunityByStageChart from "../../Components/overview/OpportunityByStageChart";
import LeadsPerWeekChart from "../../Components/overview/LeadsPerWeekChart";
import OpportunityClosedPerMonthChart from "../../Components/overview/OpportunityClosedPerMonthChart";

const OverviewPage = () => {
  const userDetails = useSelector(selectUserDetails);
  const permissionArr = Array.isArray(userDetails?.assigned_permissions) ? userDetails?.assigned_permissions : [];
  return (
    <>
      <div className="row">
        <div className="col-md-6">
          <Top10OpenOpportunities />
        </div>
        <div className="col-md-6">
          <Last10OpenOpportunities />
        </div>
      </div>
      <div className="row">
        <div className="col-md-6">
          <div className="financial_ddiv">
            <div className="financial_div">Opportunity By Stage</div>
            <div className="financial_div_custom">
              <OpportunityByStageChart />
            </div>
          </div>
        </div>
        <div className="col-md-6">
          <div className="row">
            <div>
              <div className="financial_ddiv">
                <div className="financial_div">Opportunity Closed Per Month (Won/Lost)</div>
                <div className="financial_div_custom">
                  <OpportunityClosedPerMonthChart />
                </div>
              </div>
            </div>
            <div>
              <div className="financial_ddiv">
                <div className="financial_div">Leads Converted per Week</div>
                <div className="financial_div_custom">
                  <LeadsPerWeekChart />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div className="row">
        <div className="col-md-4">
          <TableFirstTable title="Last 7 Days" />
        </div>
        <div className="col-md-4">
          <TableFirstTable title="Last 30 Days" />
        </div>
        <div className="col-md-4">
          <TableFirstTable title="YTD" />
        </div>
      </div>
      <div className="row mt-2">
        <div className="col-md-12">
          {
            permissionArr?.includes("reports")
            &&
            <div className="row" >
              <div className="col-md-12 mb-3">
                <div className="financial_ddiv">
                  <div className="financial_div">Financials</div>
                  <div className="financial_div_custom">
                    <div className="row">
                      <div className="col-md-6 mb-3">
                        <CashOnHandChart />
                      </div>
                      {/* <div className="col-md-4 mb-3">
                        <CashFlowForecastChart />
                      </div> */}
                      <div className="col-md-6 mb-3">
                        <MonthlyExpensesChart />
                      </div>
                    </div>
                    <div className="row">
                      <div className="col-md-6 mb-3">
                        <ProfitabilityPercentageChart />
                      </div>
                      <div className="col-md-6 mb-3">
                        <CurrentLiabilitiesChart />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          }
          <div className="row">
            <div className="col-md-6">
              <TableTotalFamiliesServed title="Total Families Served" />
              <TableProduction title="Production" />
            </div>
            <div className="col-md-6">
              <TableLeadingIndicator title="Leading Indicators" />
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

export default OverviewPage;
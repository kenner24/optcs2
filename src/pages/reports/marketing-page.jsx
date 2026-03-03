import ApptScheduledChart from "../../Components/reports/marketing/ApptScheduledChart";
import CostPerClientChart from "../../Components/reports/marketing/CostPerClientChart";
import LeadGeneratedChart from "../../Components/reports/marketing/LeadGeneratedChart";
import LeadsPerFunnelAverageChart from "../../Components/reports/marketing/LeadsPerFunnelAverageChart";
import OpenLeadsPerFunnelChart from "../../Components/reports/marketing/OpenLeadsPerFunnelChart";
import SeminarResponseRatesChart from "../../Components/reports/marketing/SeminarResponseRatesChart";
import StickRatioShowRateChart from "../../Components/reports/marketing/StickRatioShowRateChart";

const MarketingPage = () => {
  return (
    <>
      <div className="financial_div">Marketing</div>
      <div className="financial_div_custom">
        <div className="connector_divs">
          <div className="row">
            <div className="col-md-6 mb-3">
              <LeadGeneratedChart />
            </div>
            <div className="col-md-6 mb-3">
              <ApptScheduledChart />
            </div>
          </div>
          <div className="row">
            <div className="col-md-6 mb-3">
              <StickRatioShowRateChart />
            </div>
            <div className="col-md-6 mb-3">
              <SeminarResponseRatesChart />
            </div>
          </div>
          <div className="row">
            <div className="col-md-6 mb-3">
              <CostPerClientChart />
            </div>
            <div className="col-md-6 mb-3">
              <OpenLeadsPerFunnelChart />
            </div>
          </div>
          <div className="row">
            <div className="col-md-6">
              <LeadsPerFunnelAverageChart />
            </div>
            <div className="col-md-6">
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

export default MarketingPage;
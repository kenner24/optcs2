import OpenOpportunitiesChart from "../../Components/reports/sales/OpenOpportunitiesChart";
import PaidAnnuityChart from "../../Components/reports/sales/PaidAnnuityChart";
import PaidAnnuityVsDTIChart from "../../Components/reports/sales/PaidAnnuityVsDTIChart";
import PendingBusinessChart from "../../Components/reports/sales/PendingBusinessChart";
import SubmittedProductionChart from "../../Components/reports/sales/SubmittedProductionChart";
import SubmittedProductionComparisonChart from "../../Components/reports/sales/SubmittedProductionComparisonChart";
import YTDSubmittedProductionChart from "../../Components/reports/sales/YTDSubmittedProductionChart";

const SalesPage = () => {
  return (
    <>
      <div className="financial_div">Sales</div>
      <div className="financial_div_custom">
        <div className="connector_divs">
          <div className="row">
            <div className="col-md-6 mb-3">
              <SubmittedProductionChart />
            </div>
            <div className="col-md-6 mb-3">
              <SubmittedProductionComparisonChart />
            </div>
          </div>
          <div className="row">
            <div className="col-md-6 mb-3">
              <YTDSubmittedProductionChart />
            </div>
            <div className="col-md-6 mb-3">
              <PaidAnnuityChart />
            </div>
          </div>
          <div className="row">
            <div className="col-md-6 mb-3">
              <PaidAnnuityVsDTIChart />
            </div>
            <div className="col-md-6 mb-3">
              <PendingBusinessChart />
            </div>
          </div>
          <div className="row">
            <div className="col-md-6">
              <OpenOpportunitiesChart />
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

export default SalesPage;
import DaysToIssueChart from "../../Components/reports/operations/DaysToIssueChart";
import NewAssetsFromExistingClientsChart from "../../Components/reports/operations/NewAssetsFromExistingClientsChart";
import ReviewPreparedChart from "../../Components/reports/operations/ReviewPreparedChart";

const OperationPage = () => {
  return (
    <>
      <div className="financial_div">Service /Operations</div>
      <div className="financial_div_custom">
        <div className="connector_divs">
          <div className="row">
            <div className="col-md-6 mb-3">
              <NewAssetsFromExistingClientsChart />
            </div>
            <div className="col-md-6 mb-3">
              <DaysToIssueChart />
            </div>
          </div>
          <div className="row">
            <div className="col-md-6 mb-3">
              <ReviewPreparedChart />
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

export default OperationPage;
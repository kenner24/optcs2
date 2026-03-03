import Select from "react-select";
import CashOnHandChart from "../../Components/reports/financial/CashOnHandChart";
import CurrentLiabilitiesChart from "../../Components/reports/financial/CurrentLiabilitiesChart";
import MonthlyExpensesChart from "../../Components/reports/financial/MonthlyExpensesChart";
import ProfitabilityPercentageChart from "../../Components/reports/financial/ProfitabilityPercentageChart";
import { getYearList } from "../../helper/helper";
import { useState } from "react";
import moment from "moment";

const FinancialPage = () => {
  const yearOptions = getYearList(2000);
  const [selectedYear, setSelectedYear] = useState(moment().format("YYYY"));
  const onChangeHandler = (data) => {
    if (data?.value) {
      setSelectedYear(data.value);
    } else {
      setSelectedYear(moment().format("YYYY"));
    }
  }

  return (
    <>
      <div className="financial_div">Financial</div>
      <div className="financial_div_custom">
        <div className="connector_divs">
          <div className="row">
            <div className="col-md-6 mb-3">
            </div>
            <div className="col-md-6 mb-3 d-flex align-items-center">
              <span className="mr-1">Filter: </span>
              <Select
                placeholder="Select Year..."
                className="flex-grow-1 mr-2"
                onChange={onChangeHandler}
                options={yearOptions}
                isClearable
                isSearchable
              />
            </div>
          </div>
          <div className="row">
            <div className="col-md-6 mb-3">
              <CashOnHandChart selectedYear={selectedYear} />
            </div>
            {/* <div className="col-md-6 mb-3">
              <CashFlowForecastChart />
            </div> */}
            <div className="col-md-6 mb-3">
              <CurrentLiabilitiesChart selectedYear={selectedYear} />
            </div>
          </div>
          <div className="row">
            <div className="col-md-6 mb-3">
              <ProfitabilityPercentageChart selectedYear={selectedYear} />
            </div>
            <div className="col-md-6 mb-3">
              <MonthlyExpensesChart selectedYear={selectedYear} />
            </div>
          </div>
          <div className="row">
          </div>
        </div>
      </div>
    </>
  );
}

export default FinancialPage;
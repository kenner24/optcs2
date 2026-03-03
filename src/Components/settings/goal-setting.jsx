import Table from "react-bootstrap/Table";
import Button from "react-bootstrap/Button";
import Form from "react-bootstrap/Form";
import Spinner from "react-bootstrap/Spinner";
import Select from "react-select";
import { useEffect, useState } from "react";
import { getYearList } from "../../helper/helper";
import {
  useGetReportChartGoalsDataQuery,
  useSaveReportGoalSettingMutation
} from "../../redux/api/report/report-api";
import { toast } from "react-toastify";

function GoalSetting() {
  const reportsArr = [
    {
      label: "Submitted Production",
      value: "submitted_production",
    },
    {
      label: "YTD Submitted Production",
      value: "ytd_submitted_production",
    },
    {
      label: "Paid Annuity",
      value: "paid_annuity",
    },
    {
      label: "Paid Annuity Vs DTI",
      value: "paid_annuity_dti",
    },
    {
      label: "Pending Business",
      value: "pending_business",
    },
    {
      label: "Open Opportunities",
      value: "open_opportunities",
    },
    {
      label: "Leads Generated",
      value: "leads_generated",
    },
    {
      label: "1st Appt Scheduled",
      value: "1st_appt_scheduled",
    },
    {
      label: "Stick Ratio/ Show Rate ",
      value: "stick_ration_show_rate",
    },
    {
      label: "Seminar Responses Ratio",
      value: "seminar_responses_ratio",
    },
    {
      label: "Cost Per Client",
      value: "cost_per_client",
    },
    {
      label: "Profitability Percentage",
      value: "profitability_percentage",
    },
    {
      label: "Days To Issue",
      value: "days_to_issue",
    },
    {
      label: "New Assets From Existing Clients",
      value: "new_assets_from_existing_clients",
    },
  ];
  const [SaveReportGoalSetting] = useSaveReportGoalSettingMutation();
  const [selectedReport, setSelectedReport] = useState(null);
  const [selectedYear, setSelectedYear] = useState(null);
  const [skip, setSkip] = useState(true);
  const [showTable, setShowTable] = useState(false);
  const { currentData: prevGoalsData, isFetching } = useGetReportChartGoalsDataQuery({
    report_name: selectedReport?.value,
    year: selectedYear?.value
  }, {
    skip: skip,
    refetchOnMountOrArgChange: true
  });
  const [prevMonthGoals, setPrevMonthGoals] = useState({
    Jan: 0,
    Feb: 0,
    Mar: 0,
    Apr: 0,
    May: 0,
    Jun: 0,
    Jul: 0,
    Aug: 0,
    Sep: 0,
    Oct: 0,
    Nov: 0,
    Dec: 0,
  });
  const [monthGoals, setMonthGoals] = useState({
    Jan: 0,
    Feb: 0,
    Mar: 0,
    Apr: 0,
    May: 0,
    Jun: 0,
    Jul: 0,
    Aug: 0,
    Sep: 0,
    Oct: 0,
    Nov: 0,
    Dec: 0,
  });
  const yearOptions = getYearList(2000);

  const reportSelectHandler = (data) => {
    if (data?.value) {
      setSelectedReport(data);
    } else {
      setSelectedReport(null);
      setSkip(true);
    }
  }

  const selectYearHandler = (data) => {
    if (data?.value) {
      setSelectedYear(data);
    } else {
      setSelectedYear(null);
      setSkip(true);
    }
  }

  const saveGoalHandler = () => {
    SaveReportGoalSetting({
      report_name: selectedReport?.value,
      year: selectedYear?.value,
      goals: monthGoals
    })
      .then((res) => {
        if (res?.data?.success) {
          toast(res?.data?.message);
        }
      })
      .catch((err) => {
        toast(err?.data?.message || err?.message);
      });
  }

  useEffect(() => {
    if (selectedReport !== null && selectedYear !== null) {
      setSkip(false);
      setShowTable(false);
    }
  }, [selectedReport, selectedYear]);

  useEffect(() => {
    if (prevGoalsData?.data?.length > 0) {
      const temp = {};
      prevGoalsData?.data.forEach(element => {
        temp[element.month] = element.value;
      });
      setPrevMonthGoals(temp);
      setShowTable(true);
      toast("Data retrieved successfully");
    } else {
      setPrevMonthGoals({
        Jan: 0,
        Feb: 0,
        Mar: 0,
        Apr: 0,
        May: 0,
        Jun: 0,
        Jul: 0,
        Aug: 0,
        Sep: 0,
        Oct: 0,
        Nov: 0,
        Dec: 0,
      });
      setShowTable(prevGoalsData != undefined);
    }
  }, [prevGoalsData])


  return (
    <>
      <div>
        <div className="d-flex align-items-center">
          <span>&nbsp; Report : &nbsp;</span>
          <Select
            placeholder="Select Report..."
            className="flex-grow-1"
            onChange={reportSelectHandler}
            options={reportsArr}
            isClearable
            isSearchable
          />
        </div>
        <div className="d-flex align-items-center">
          <span>&nbsp; Year : &nbsp;</span>
          <Select
            placeholder="Select Year..."
            className="flex-grow-1 mt-2"
            onChange={selectYearHandler}
            options={yearOptions}
            isClearable
            isSearchable
          />
        </div>
      </div>
      <div className="mt-2">
        {
          isFetching &&
          <Spinner animation="border" size="lg" />
        }
        {
          showTable &&
          <>
            <div className="d-flex align-items-center justify-content-around mb-2">
              <div className="d-flex align-items-center justify-content-around">
                <span>Report Name: &nbsp;</span>
                <span>{selectedReport?.label}</span>
              </div>
              <div className="d-flex align-items-center justify-content-around">
                <span>Year: &nbsp;</span>
                <span>{selectedYear?.label}</span>
              </div>
            </div>
            <Table striped bordered hover>
              <thead>
                <tr>
                  <th>Month</th>
                  <th>Previous Saved Goal</th>
                  <th>New Goal</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>January</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_jan"
                      readOnly
                      value={prevMonthGoals?.Jan}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_jan"
                      value={monthGoals?.Jan}
                      onChange={(event) => setMonthGoals({
                        ...monthGoals,
                        Jan: parseFloat(event.target.value),
                      })}
                      min="0"
                    />
                  </td>
                </tr>
                <tr>
                  <td>February</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_feb"
                      readOnly
                      value={prevMonthGoals?.Feb}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_feb"
                      value={monthGoals?.Feb}
                      onChange={(event) => setMonthGoals({
                        ...monthGoals,
                        Feb: parseFloat(event.target.value),
                      })}
                      min="0"
                    />
                  </td>
                </tr>
                <tr>
                  <td>March</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_mar"
                      readOnly
                      value={prevMonthGoals?.Mar}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_mar"
                      value={monthGoals?.Mar}
                      onChange={(event) => setMonthGoals({
                        ...monthGoals,
                        Mar: parseFloat(event.target.value),
                      })}
                      min="0"
                    />
                  </td>
                </tr>
                <tr>
                  <td>April</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_apr"
                      readOnly
                      value={prevMonthGoals?.Apr}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_apr"
                      value={monthGoals?.Apr}
                      onChange={(event) => setMonthGoals({
                        ...monthGoals,
                        Apr: parseFloat(event.target.value),
                      })}
                      min="0"
                    />
                  </td>
                </tr>
                <tr>
                  <td>May</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_may"
                      readOnly
                      value={prevMonthGoals?.May}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_may"
                      value={monthGoals?.May}
                      onChange={(event) => setMonthGoals({
                        ...monthGoals,
                        May: parseFloat(event.target.value),
                      })}
                      min="0"
                    />
                  </td>
                </tr>
                <tr>
                  <td>June</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_jun"
                      readOnly
                      value={prevMonthGoals?.Jun}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_jun"
                      value={monthGoals?.Jun}
                      onChange={(event) => setMonthGoals({
                        ...monthGoals,
                        Jun: parseFloat(event.target.value),
                      })}
                      min="0"
                    />
                  </td>
                </tr>
                <tr>
                  <td>July</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_jul"
                      readOnly
                      value={prevMonthGoals?.Jul}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_jul"
                      value={monthGoals?.Jul}
                      onChange={(event) => setMonthGoals({
                        ...monthGoals,
                        Jul: parseFloat(event.target.value),
                      })}
                      min="0"
                    />
                  </td>
                </tr>
                <tr>
                  <td>August</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_aug"
                      readOnly
                      value={prevMonthGoals?.Aug}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_aug"
                      value={monthGoals?.Aug}
                      onChange={(event) => setMonthGoals({
                        ...monthGoals,
                        Aug: parseFloat(event.target.value),
                      })}
                      min="0"
                    />
                  </td>
                </tr>
                <tr>
                  <td>September</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_sep"
                      readOnly
                      value={prevMonthGoals?.Sep}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_sep"
                      value={monthGoals?.Sep}
                      onChange={(event) => setMonthGoals({
                        ...monthGoals,
                        Sep: parseFloat(event.target.value),
                      })}
                      min="0"
                    />
                  </td>
                </tr>
                <tr>
                  <td>October</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_oct"
                      readOnly
                      value={prevMonthGoals?.Oct}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_oct"
                      value={monthGoals?.Oct}
                      onChange={(event) => setMonthGoals({
                        ...monthGoals,
                        Oct: parseFloat(event.target.value),
                      })}
                      min="0"
                    />
                  </td>
                </tr>
                <tr>
                  <td>November</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_nov"
                      readOnly
                      value={prevMonthGoals?.Nov}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_nov"
                      value={monthGoals?.Nov}
                      onChange={(event) => setMonthGoals({
                        ...monthGoals,
                        Nov: parseFloat(event.target.value),
                      })}
                      min="0"
                    />
                  </td>
                </tr>
                <tr>
                  <td>December</td>
                  <td>
                    <Form.Control
                      type="number"
                      name="prev_dec"
                      readOnly
                      value={prevMonthGoals?.Dec}
                    />
                  </td>
                  <td>
                    <Form.Control
                      type="number"
                      name="new_dec"
                      value={monthGoals?.Dec}
                      onChange={(event) =>
                        setMonthGoals({
                          ...monthGoals,
                          Dec: parseFloat(event.target.value),
                        })
                      }
                      min="0"
                    />
                  </td>
                </tr>
              </tbody>
            </Table>
            <Button variant="success" onClick={saveGoalHandler}>Save</Button>
          </>
        }
      </div>
    </>
  )
}

export default GoalSetting;
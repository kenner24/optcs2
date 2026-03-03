import { useEffect, useState } from "react";
import { toast } from "react-toastify";
import Select from "react-select";
import Button from "react-bootstrap/Button";
import Spinner from "react-bootstrap/Spinner";
import Table from "react-bootstrap/Table";
import { useSaveConnectorPreferencesMutation } from "../../../redux/api/connector/connector-api";
import { ConnectorTypeConstant } from "../../../constants/connectorType.constant";
import { useGetBudgetListQuery } from "../../../redux/api/connector/quickbook-api";
import { getYearList } from "../../../helper/helper";

export default function QuickBookOption({ preferences }) {
  const { data: budgetList, isFetching, error, refetch } = useGetBudgetListQuery();
  const [SaveConnectorPreferences, {
    isLoading: savingPreference
  }] = useSaveConnectorPreferencesMutation();
  const [options, setOptions] = useState([]);
  const [budgetId, setBudgetId] = useState(null);
  const [year, setYear] = useState(null);
  const yearOptions = getYearList(2000);
  const [prevSelectedBudgets, setPrevSelectedBudgets] = useState([]);

  if (!error?.data?.success) {
    toast(error?.data?.message);
  }

  useEffect(() => {
    if (budgetList?.data.length > 0) {
      const temp = budgetList.data.map(element => {
        return {
          value: element.budget_id,
          label: element.name,
        }
      });
      setOptions(temp);
    }

    if (preferences?.data.length > 0 && budgetList?.data.length > 0) {
      const selectedBudgets = preferences.data.filter((ele) => ele?.field_name === "budget_id" && ele?.year !== null)
        .map(ele => {
          const budget = budgetList.data.find((x) => x?.budget_id === ele?.field_value);
          return {
            year: ele.year,
            budgetName: budget?.name
          }
        });
      setPrevSelectedBudgets(selectedBudgets);
    }
  }, [budgetList, preferences]);

  const onChangeHandler = (data) => {
    if (data?.value) {
      setBudgetId(data.value);
    } else {
      setBudgetId(null);
    }
  }

  const onYearChangeHandler = (data) => {
    if (data?.value) {
      setYear(data.value);
    } else {
      setYear(null);
    }
  }

  const saveBudgetDetails = async () => {
    try {

      if (budgetId === null) {
        toast("Please select budget from dropdown.");
      }

      if (year === null) {
        toast("Please select year from dropdown.");
      }

      const result = await SaveConnectorPreferences({
        "connector_type": ConnectorTypeConstant.QUICK_BOOKS,
        "column_name": "budget_id",
        "column_value": budgetId,
        "year": year
      });

      if (result?.success) {
        toast("We have started processing the data from quick books when its is done you will get notify via mail", {
          autoClose: 10000,
          delay: 3000
        });
      }

      const errors = result?.error?.data?.errors;
      if (result?.error?.status === 422) {
        for (const property in errors) {
          toast(errors[property][0]);
        }
        return;
      }

      toast(result?.data?.message || result?.message);
    } catch (error) {
      const errors = error?.data?.errors;
      if (error?.status === 422) {
        for (const property in errors) {
          toast(errors[property][0]);
        }
        return;
      }

      toast(error?.data?.message || error?.message);
    }
  }

  return (
    <>
      <h5 className="mt-3">Select the budget you want to configure with the application</h5>
      <div>
        <div className="d-flex mt-2">
          <Select
            placeholder="Select Year..."
            className="flex-grow-1 mr-2"
            onChange={onYearChangeHandler}
            options={yearOptions}
            isClearable
            isSearchable
          />
          <Select
            placeholder="Select Budget..."
            className="flex-grow-1 mr-2"
            onChange={onChangeHandler}
            options={options}
            isLoading={isFetching}
            isClearable
            isSearchable
          />
          <Button variant="secondary" onClick={refetch}>
            <i className={isFetching ? "fa-solid fa-arrows-rotate fa-spin" : "fa-solid fa-arrows-rotate"}></i>
          </Button>
        </div>

        <Button className="mt-2" variant="success" onClick={saveBudgetDetails}>
          Save {" "}
          {savingPreference && <Spinner animation="border" size="sm" />}
        </Button>
      </div>
      <div className="mt-3">
        {prevSelectedBudgets?.length > 0 &&
          <Table responsive="sm">
            <thead>
              <tr>
                <th>Year</th>
                <th>Selected Budget</th>
              </tr>
            </thead>
            <tbody>
              {
                prevSelectedBudgets.map(ele => {
                  return (<tr key={ele?.year}>
                    <td>{ele?.year}</td>
                    <td>{ele?.budgetName}</td>
                  </tr>)
                })
              }
            </tbody>
          </Table>
        }
      </div>
    </>
  );
}
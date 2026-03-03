import Select from "react-select"
import Button from "react-bootstrap/Button";
import Spinner from "react-bootstrap/Spinner";
import Table from "react-bootstrap/Table";
import { useEffect, useState } from "react";
import { toast } from "react-toastify";
import {
  useGetGoogleSheetListQuery,
  useGetGoogleSpreadSheetListQuery,
  useGetSheetHeadersListQuery,
} from "../../../redux/api/connector/google-sheet-api";
import { useSaveConnectorPreferencesMutation } from "../../../redux/api/connector/connector-api";
import { ConnectorTypeConstant } from "../../../constants/connectorType.constant";
import { firstLetterUpperCase } from "../../../helper/helper";

const GoogleSheetOption = ({ preferences }) => {

  const [SaveConnectorPreferences, {
    isLoading: savingPreference
  }] = useSaveConnectorPreferencesMutation();

  // spreadsheet
  const [spreadSheetOptions, setSpreadSheetOptions] = useState([]);
  const [spreadSheetId, setSpreadSheetId] = useState(null);
  const [prevSelectedSpreadSheet, setPrevSelectedSpreadSheet] = useState(null);

  // sheet
  const [sheetOptions, setSheetOptions] = useState([]);
  const [sheetId, setSheetId] = useState(null);
  const [sheetName, setSheetName] = useState(null);
  const [prevSelectedSheet, setPrevSelectedSheet] = useState(null);
  const [skipSheetList, setSkipSheetList] = useState(true);

  // sheet headers
  const [skipSheetHeaders, setSkipSheetHeaders] = useState(true);
  const [headerOptions, setHeaderOptions] = useState([]);

  // mapped col
  const [mappedCol, setMappedCol] = useState({
    name: null,
    age: null,
    dob: null,
  });

  const {
    data: spreadSheetList,
    isFetching: spreadSheetFetching,
    error: spreadSheetError,
    refetch: spreadSheetRefetch
  } = useGetGoogleSpreadSheetListQuery();

  const {
    data: sheetList,
    isFetching: sheetDataFetching,
    error: sheetError,
    refetch: sheetRefetch
  } = useGetGoogleSheetListQuery(spreadSheetId, {
    skip: skipSheetList
  });

  const {
    data: sheetHeaders,
    isFetching: sheetHeadersFetching,
    error: sheetHeadersError,
    refetch: sheetHeadersRefetch
  } = useGetSheetHeadersListQuery({
    id: spreadSheetId,
    sheet_name: sheetName
  }, {
    skip: skipSheetHeaders,
  });

  const spreadSheetChangeHandler = (data) => {
    if (data !== null) {
      setSpreadSheetId(data.value);
      setSkipSheetList(false);
    } else {
      setSpreadSheetId(null);
      setSkipSheetList(true);
    }

    setSheetId(null);
    setSheetOptions([]);
    setSheetName(null);
    setSkipSheetHeaders(true);
  }

  const sheetChangeHandler = (data) => {
    if (data !== null) {
      setSheetId(data.value);
      setSheetName(data.label);
      setSkipSheetHeaders(false);
    } else {
      setSheetId(null);
      setSheetName(null);
      setSkipSheetHeaders(true);
    }
  }

  const saveSheetDetails = async () => {
    try {

      if (sheetId === null) {
        toast("Please select sheet from dropdown.");
        return;
      }

      if (spreadSheetId === null) {
        toast("Please select Spreadsheet from dropdown.");
        return;
      }
      
      for (const key in mappedCol) {
        if (mappedCol[key] === null) {
          toast(`Please select ${firstLetterUpperCase(key)} from dropdown`);
          return;
        }
      }

      const result = await Promise.all([
        SaveConnectorPreferences({
          "connector_type": ConnectorTypeConstant.GOOGLE_SHEET,
          "column_name": "spreadsheet_id",
          "column_value": spreadSheetId
        }),
        SaveConnectorPreferences({
          "connector_type": ConnectorTypeConstant.GOOGLE_SHEET,
          "column_name": "sheet_id",
          "column_value": sheetId
        })
      ]);

      if(result[0]?.success){
        toast("We have started processing the data from google sheets when its is done you will get notify via mail", {
          autoClose: 10000,
          delay: 5000
        });
      }

      toast(result[0]?.data?.message || result[0]?.message);

    } catch (error) {
      if (error?.status === 422) {
        const errors = error?.data?.errors;
        for (const property in errors) {
          toast(errors[property][0]);
        }
        return;
      }

      toast(error?.data?.message || error?.message);
    }
  }

  if (!spreadSheetError?.data?.success) {
    toast(spreadSheetError?.data?.message);
  }

  if (!sheetError?.data?.success) {
    toast(sheetError?.data?.message);
  }

  if (!sheetHeadersError?.data?.success) {
    toast(sheetHeadersError?.data?.message);
  }

  useEffect(() => {
    if (spreadSheetList?.data.length > 0) {
      const temp = spreadSheetList.data.map(element => {
        return {
          value: element.spreadsheet_id,
          label: element.spreadsheet_name,
        }
      });
      setSpreadSheetOptions(temp);
    }

    if (sheetList?.data.length > 0) {
      const temp = sheetList.data.map(element => {
        return {
          value: element.sheet_id,
          label: element.sheet_name,
        }
      });
      setSheetOptions(temp);
    }

    if (sheetHeaders?.data.length > 0) {
      const temp = sheetHeaders.data.map(element => {
        return {
          value: element,
          label: element,
        }
      });
      setHeaderOptions(temp);
    }
  }, [spreadSheetList, sheetList, sheetHeaders]);


  useEffect(() => {
    if (preferences?.data.length > 0 && spreadSheetList?.data.length > 0) {
      const spreadSheetId = preferences.data.find((ele) => ele?.field_name === "spreadsheet_id");
      if (spreadSheetId) {
        const spreadSheet = spreadSheetList.data.find((ele) => ele?.spreadsheet_id == spreadSheetId?.field_value);
        setPrevSelectedSpreadSheet(spreadSheet);
        setSpreadSheetId(spreadSheet?.spreadsheet_id);
        setSkipSheetList(false);
      }
    }

    if (preferences?.data.length > 0 && sheetList?.data.length > 0) {
      const sheetId = preferences.data.find((ele) => ele?.field_name === "sheet_id");
      if (sheetId) {
        const sheet = sheetList.data.find((ele) => ele?.sheet_id == sheetId?.field_value);
        setPrevSelectedSheet(sheet);
      }
    }
  }, [preferences])


  return (
    <>
      <h5 className="mt-3">Select the google sheet you want to configure</h5>
      <h6>
        Previously Selected Google SpreadSheet: {prevSelectedSpreadSheet?.spreadsheet_name ?? "N/A"}
      </h6>

      <h6>
        Previously Selected Google Sheet: {prevSelectedSheet?.sheet_name ?? "N/A"}
      </h6>
      <div>
        <div className="d-flex">
          <Select
            className="flex-grow-1 mr-2"
            onChange={spreadSheetChangeHandler}
            options={spreadSheetOptions}
            isLoading={spreadSheetFetching}
            defaultValue={prevSelectedSpreadSheet}
            isClearable
            isSearchable
          />
          <Button variant="secondary" onClick={spreadSheetRefetch}>
            <i className={spreadSheetFetching ? "fa-solid fa-arrows-rotate fa-spin" : "fa-solid fa-arrows-rotate"}></i>
          </Button>
        </div>
        <div className="d-flex mt-2">
          <Select
            className="flex-grow-1 mr-2"
            onChange={sheetChangeHandler}
            options={sheetOptions}
            isLoading={sheetDataFetching}
            isClearable
            isSearchable
          />
          <Button variant="secondary" onClick={sheetRefetch}>
            <i className={sheetDataFetching ? "fa-solid fa-arrows-rotate fa-spin" : "fa-solid fa-arrows-rotate"}></i>
          </Button>
        </div>
        <div>
          <Table className="subaccount_table google_sheet_table">
            <thead>
              <tr>
                <th>Field Name</th>
                <th>Google Sheet Fields</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Name</td>
                <td>
                  <div className="d-flex">
                    <Select
                      className="flex-grow-1 mr-2"
                      onChange={(data) => {
                        if (data?.value) {
                          setMappedCol({
                            ...mappedCol,
                            name: data?.value
                          });
                        } else {
                          setMappedCol({
                            ...mappedCol,
                            name: null
                          });
                        }
                      }}
                      options={headerOptions}
                      isLoading={sheetHeadersFetching}
                      isClearable
                      isSearchable
                    />
                    <Button variant="secondary" onClick={sheetHeadersRefetch}>
                      <i className={sheetHeadersFetching ? "fa-solid fa-arrows-rotate fa-spin" : "fa-solid fa-arrows-rotate"}></i>
                    </Button>
                  </div>
                </td>
              </tr>
              <tr>
                <td>Age</td>
                <td>
                  <div className="d-flex">
                    <Select
                      className="flex-grow-1 mr-2"
                      onChange={(data) => {
                        if (data?.value) {
                          setMappedCol({
                            ...mappedCol,
                            age: data?.value
                          });
                        } else {
                          setMappedCol({
                            ...mappedCol,
                            age: null
                          });
                        }
                      }}
                      options={headerOptions}
                      isLoading={sheetHeadersFetching}
                      isClearable
                      isSearchable
                    />
                    <Button variant="secondary" onClick={sheetHeadersRefetch}>
                      <i className={sheetHeadersFetching ? "fa-solid fa-arrows-rotate fa-spin" : "fa-solid fa-arrows-rotate"}></i>
                    </Button>

                  </div>
                </td>
              </tr>
              <tr>
                <td>DOB</td>
                <td>
                  <div className="d-flex">
                    <Select
                      className="flex-grow-1 mr-2"
                      onChange={(data) => {
                        if (data?.value) {
                          setMappedCol({
                            ...mappedCol,
                            dob: data?.value
                          });
                        } else {
                          setMappedCol({
                            ...mappedCol,
                            dob: null
                          });
                        }
                      }}
                      options={headerOptions}
                      isLoading={sheetHeadersFetching}
                      isClearable
                      isSearchable
                    />
                    <Button variant="secondary" onClick={sheetHeadersRefetch}>
                      <i className={sheetHeadersFetching ? "fa-solid fa-arrows-rotate fa-spin" : "fa-solid fa-arrows-rotate"}></i>
                    </Button>
                  </div>
                </td>
              </tr>
            </tbody>
          </Table>
        </div>

        <Button className="mt-2" variant="success" onClick={saveSheetDetails}>
          Save {" "}
          {savingPreference && <Spinner animation="border" size="sm" />}
        </Button>
      </div>
    </>
  )
}

export default GoogleSheetOption;
import React, { useEffect, useState } from "react";
import { useNavigate, useSearchParams } from "react-router-dom";
import { toast } from "react-toastify";
import Spinner from "react-bootstrap/Spinner";
import { useAddConnectorMutation, useGetOauthAuthorizationURLQuery } from "../../redux/api/connector/connector-api";
import {
  selectUserDetails,
  setGoogleSheetAccess,
  setQuickBookAccess,
  setSalesForceAccess
} from "../../redux/slices/UserProfileSlice";
import { useDispatch, useSelector } from "react-redux";
import { ConnectorTypeConstant } from "../../constants/connectorType.constant";
import { useGetUserProfileQuery } from "../../redux/api/user/user-api";
import ConnectorOption from "../../Components/connector/connector-option";

const stateVariables = {
  GOOGLE_SHEET: "go_sh",
  QUICK_BOOKS: "qui_co",
  SALES_FORCE: "sal_fo",
}

function ConnectorPage() {
  const [salesForceLoading, setSalesForceLoading] = useState(false);
  const [googleSheetLoading, setGoogleSheetLoading] = useState(false);
  const [quickBooksLoading, setQuickBooksLoading] = useState(false);
  const [quickBookAuthState, setQuickBookAuthState] = useState(localStorage.getItem(stateVariables.QUICK_BOOKS));
  const [googleSheetAuthState, setGoogleSheetAuthState] = useState(localStorage.getItem(stateVariables.GOOGLE_SHEET));
  const userDetails = useSelector(selectUserDetails);
  const dispatch = useDispatch();
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  const [AddConnector] = useAddConnectorMutation();
  const {
    data: salesForceData,
    refetch: refetchSalesForceAuthUrl
  } = useGetOauthAuthorizationURLQuery(ConnectorTypeConstant.SALES_FORCE, {
    skip: userDetails?.sales_force_access
  });
  const {
    data: googleSheetData,
    refetch: refetchGoogleSheetAuthUrl
  } = useGetOauthAuthorizationURLQuery(ConnectorTypeConstant.GOOGLE_SHEET, {
    skip: userDetails?.google_sheet_access
  });
  const {
    data: quickBooksData,
    refetch: refetchQuickBooksAuthUrl
  } = useGetOauthAuthorizationURLQuery(ConnectorTypeConstant.QUICK_BOOKS, {
    skip: userDetails?.quick_books_access
  });
  const { data: userProfile } = useGetUserProfileQuery();

  const salesForceOnClick = () => {
    if (userDetails?.sales_force_access) {
      return;
    }
    if (salesForceData?.data?.authorization_url) {
      setSalesForceLoading(true);
      window.location.href = salesForceData?.data?.authorization_url;
    } else {
      toast("Something went wrong. Please try again.");
      refetchSalesForceAuthUrl();
    }
  };

  const googleSheetOnClick = () => {
    if (userDetails?.google_sheet_access) {
      return;
    }

    if (googleSheetData?.data?.authorization_url) {
      const queryParameters = new URLSearchParams(googleSheetData?.data?.authorization_url);
      const state = queryParameters.get("state");
      localStorage.setItem(stateVariables.GOOGLE_SHEET, state);
      setGoogleSheetAuthState(state);
      setGoogleSheetLoading(true);
      window.location.href = googleSheetData?.data?.authorization_url;
    } else {
      toast("Cannot add connector at this moment");
      refetchGoogleSheetAuthUrl();
    }
  };

  const quickBooksOnClick = () => {
    if (userDetails?.quick_books_access) {
      return;
    }

    if (quickBooksData?.data?.authorization_url) {
      const queryParameters = new URLSearchParams(quickBooksData?.data?.authorization_url);
      localStorage.setItem(stateVariables.QUICK_BOOKS, queryParameters.get("state"));
      setQuickBookAuthState(queryParameters.get("state"));
      setQuickBooksLoading(true);
      window.location.href = quickBooksData?.data?.authorization_url;
    } else {
      toast("Something went wrong. Please try again");
      refetchQuickBooksAuthUrl();
    }
  };

  useEffect(() => {
    const authorizationCode = searchParams.get("code");
    const quickBookRealmId = searchParams.get("realmId");
    const state = searchParams.get("state");

    // quick books
    if (
      authorizationCode?.length > 0 &&
      quickBookRealmId?.length > 0 &&
      state !== null &&
      state === quickBookAuthState
    ) {

      setQuickBooksLoading(true);

      AddConnector({
        connector_type: ConnectorTypeConstant.QUICK_BOOKS,
        authorization_code: authorizationCode,
        realmId: quickBookRealmId
      }).unwrap()
        .then((result) => {
          if (result?.success) {
            dispatch(setQuickBookAccess({
              quick_books_access: true
            }));
          }
          toast(result?.message || result?.data?.message);
          setQuickBooksLoading(false);
          navigate("/connectors");
        })
        .catch((error) => {
          if (error?.status === 422) {
            const errors = error?.data?.errors;
            for (const property in errors) {
              toast(errors[property][0]);
            }
            return;
          }

          toast(error?.data?.message || error?.message);
          setSalesForceLoading(false);
          setQuickBooksLoading(false);
          setGoogleSheetLoading(false);
          navigate("/connectors");
        });
      return;
    } else if (
      authorizationCode?.length > 0 &&
      state !== null &&
      googleSheetAuthState !== null &&
      state === googleSheetAuthState
    ) { // google sheets
      setGoogleSheetLoading(true);

      AddConnector({
        connector_type: ConnectorTypeConstant.GOOGLE_SHEET,
        authorization_code: authorizationCode
      }).unwrap()
        .then(result => {
          if (result?.success) {
            dispatch(setGoogleSheetAccess({
              google_sheet_access: true
            }));
          }
          toast(result?.message || result?.data?.message);
          setGoogleSheetLoading(false);
          navigate("/connectors");

        }).catch(error => {
          if (error?.status === 422) {
            const errors = error?.data?.errors;
            for (const property in errors) {
              toast(errors[property][0]);
            }
            return;
          }

          toast(error?.data?.message || error?.message);
          setSalesForceLoading(false);
          setQuickBooksLoading(false);
          setGoogleSheetLoading(false);
          navigate("/connectors");
        });
      return;
    } else if (authorizationCode?.length > 0) { // sales force

      setSalesForceLoading(true);

      AddConnector({
        connector_type: ConnectorTypeConstant.SALES_FORCE,
        authorization_code: authorizationCode
      }).unwrap()
        .then(result => {
          if (result?.success) {
            dispatch(setSalesForceAccess({
              sales_force_access: true
            }));
            toast("We have started processing the data from sales force when its is done you will get notify via mail", {
              autoClose: 10000,
              delay: 5000
            });
          }
          toast(result?.message || result?.data?.message);
          setSalesForceLoading(false);
          navigate("/connectors");

        }).catch(error => {
          if (error?.status === 422) {
            const errors = error?.data?.errors;
            for (const property in errors) {
              toast(errors[property][0]);
            }
            return;
          }

          toast(error?.data?.message || error?.message);
          setSalesForceLoading(false);
          setQuickBooksLoading(false);
          setGoogleSheetLoading(false);
          navigate("/connectors");
        });
      return;
    }
  }, []);



  return (
    <>
      <div className="financial_div">Connectors</div>
      <div className="financial_div_custom">
        <div className="connector_divs">
          <div className="row">
            <div className="col-md-10">
              <div className="row">
                <div
                  className={userDetails?.sales_force_access ? "col-md-4" : "col-md-4 connector_div_element"}
                  onClick={salesForceOnClick}
                  aria-hidden="true"
                >
                  <div className="connector_first_Divvmain_outer">
                    <div className="connector_first_Divv">
                      <img src="./con1.png" alt="salesforce" />
                      <div className="salesforce_title">Salesforce &nbsp;
                        {
                          (userDetails?.sales_force_access && !salesForceLoading) &&
                          <i className="fa-sharp fa-regular fa-circle-check"></i>
                        }
                        {
                          (!userDetails?.sales_force_access && !salesForceLoading) &&
                          <i className="fa-sharp fa-regular fa-circle-xmark"></i>
                        }
                        {salesForceLoading && <Spinner animation="border" size="sm" />}
                      </div>
                    </div>
                    <div className="after_connector_div"></div>
                  </div>
                </div>
                <div
                  className={userDetails?.google_sheet_access ? "col-md-4" : "col-md-4 connector_div_element"}
                  onClick={googleSheetOnClick}
                  aria-hidden="true"
                >
                  <div className="connector_first_Divvmain_outer">
                    <div className="connector_first_Divv">
                      <img src="./con2.png" alt="google_sheet" />
                      <div className="salesforce_title">Google Sheet&nbsp;
                        {
                          (userDetails?.google_sheet_access && !googleSheetLoading) &&
                          <i className="fa-sharp fa-regular fa-circle-check"></i>
                        }
                        {
                          (!userDetails?.google_sheet_access && !googleSheetLoading) &&
                          <i className="fa-sharp fa-regular fa-circle-xmark"></i>
                        }
                        {googleSheetLoading ? <Spinner animation="border" size="sm" /> : ""}

                      </div>
                    </div>
                    <div className="after_connector_div second"></div>
                  </div>
                </div>
                <div
                  className={userDetails?.quick_books_access ? "col-md-4" : "col-md-4 connector_div_element"}
                  onClick={quickBooksOnClick}
                  aria-hidden="true"
                >
                  <div className="connector_first_Divvmain_outer">
                    <div className="connector_first_Divv">
                      <img src="./con3.png" alt="quickbooks" />
                      <div className="salesforce_title">QuickBooks&nbsp;
                        {
                          (userDetails?.quick_books_access && !quickBooksLoading) &&
                          <i className="fa-sharp fa-regular fa-circle-check"></i>
                        }
                        {
                          (!userDetails?.quick_books_access && !quickBooksLoading) &&
                          <i className="fa-sharp fa-regular fa-circle-xmark"></i>
                        }
                        {quickBooksLoading ? <Spinner animation="border" size="sm" /> : ""}
                      </div>
                    </div>
                    <div className="after_connector_div third"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div className="row">
            {
              (
                userDetails?.google_sheet_access ||
                userDetails?.quick_books_access
              )
              &&
              <ConnectorOption userDetails={userDetails} />
            }
          </div>
        </div>
      </div>
    </>
  );
}

export default ConnectorPage;

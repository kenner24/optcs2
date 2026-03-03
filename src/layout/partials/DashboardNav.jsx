import React, { useState } from "react";
import Dropdown from "react-bootstrap/Dropdown";
import { toast } from "react-toastify";
import { useSignOutMutation } from "../../redux/api/auth/auth-api";
import { useNavigate, Link } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
import { resetProfile, selectUserDetails } from "../../redux/slices/UserProfileSlice";
import { apiSlice } from "../../redux/api/api-slice";

function DashboardNav() {
  const userDetails = useSelector(selectUserDetails);
  const dispatch = useDispatch();
  const notify = (text) => toast(text);
  const [logout] = useSignOutMutation();
  const navigate = useNavigate();
  const [isOpen, setIsOpen] = useState(false);

  const handleSignOut = () => {
    logout()
      .unwrap()
      .then((res) => {
        toast(res.message);
        // remove the all local storage items
        localStorage.clear();

        // reset all the api state so that there is no cached data present
        dispatch(apiSlice.util.resetApiState());

        // reset the user profile
        dispatch(resetProfile());
        navigate("/");
      })
      .catch((err) => {
        if (err.status === 400) {
          if (typeof err?.data?.message !== "undefined") {
            notify(err?.data?.message);
          }

          if (typeof err?.data?.data !== "undefined") {
            for (let key of Object.keys(err.data.data)) {
              notify(err.data.data[key][0]);
            }
          }
        }

        // remove the all local storage items
        localStorage.clear();

        // reset all the api state so that there is no cached data present
        dispatch(apiSlice.util.resetApiState());

        // reset the user profile
        dispatch(resetProfile());
        navigate("/");
      });
  };

  const toggleSidebar = () => {
    setIsOpen(!isOpen);
  };

  return (
    <>
      <nav className="navbar navbar-fixed-top navbar-toggleable-sm navbar-inverse custom_navbar">
        <div className="flex-row d-flex mobile_flex_row">
          <Link className="brand_logo brand_logo_mobile" to="/overview">
            OPTCS
          </Link>
          <div className="toggle_right_div">
            <ul className="navbar-nav ml-auto">
              <li className="nav-item">
                {/* <button
                  className="nav-link bell_a"
                >
                  <i className="fa-regular fa-bell"></i>
                  <span>0</span>
                </button> */}
              </li>
              <li className="nav-item dropdown_custom1">
                <Dropdown className="custom_dropdown seting_dropdownn">
                  <Dropdown.Toggle variant="success" id="dropdown-basic">
                    <img src={userDetails?.image || "./avtar.png"} alt="avtar" />
                    <div className="drop_img_right">
                      <span className="fullname_span">{userDetails?.name}</span>{" "}
                    </div>
                  </Dropdown.Toggle>
                  <Dropdown.Menu>
                    <Dropdown.Header className="signin_as">
                      SIGNED IN AS
                    </Dropdown.Header>
                    <Dropdown.Item className="dropdownitem_custom">
                      <img src={userDetails?.image || "avtar.png"} alt="avtar" />
                      <div className="dropdown_right_setting">
                        <p>
                          <b>{userDetails?.name}</b>
                        </p>
                        <p>{userDetails?.email}</p>
                      </div>
                    </Dropdown.Item>
                    <Dropdown.Item onClick={() => navigate("/settings")}>
                      <i className="fas fa-light fa-gear"></i>Settings
                    </Dropdown.Item>
                    <Dropdown.Item
                      className="signout_dropdown"
                      onClick={handleSignOut}
                    >
                      <i className="fas fa-arrow-right-from-bracket"></i>
                      Sign Out
                    </Dropdown.Item>
                  </Dropdown.Menu>
                </Dropdown>
              </li>
            </ul>
            <button
              type="button"
              className="hidden-md-up navbar-toggler offcavas_div"
              onClick={toggleSidebar}
            >
              <span className="navbar-toggler-icon">
                <i className="fa-solid fa-chevron-right"></i>
              </span>
            </button>
          </div>
        </div>
      </nav>
    </>
  );
}

export default DashboardNav;

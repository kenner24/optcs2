import { Link, useLocation, useNavigate } from "react-router-dom";
import { selectUserDetails } from "../../redux/slices/UserProfileSlice";
import { useSelector } from "react-redux";

function Sidebar() {
  const navigate = useNavigate();
  const userDetails = useSelector(selectUserDetails);
  const permissionArr = Array.isArray(userDetails?.assigned_permissions) ? userDetails?.assigned_permissions : [];
  const params = useLocation();

  return (
    <div
      className="col-md-3 col-lg-2 sidebar-offcanvas"
      id="sidebar"
      role="navigation"
    >
      <Link className="brand_logo" to="/">
        <img src="/logo.png" alt="logo" />
      </Link>
      <hr className="border_seprate" />
      <ul className="nav flex-column left_sidebar">
        <li className="nav-item">
          <button
            className={
              params.pathname === "/overview" ? "nav-link active" : "nav-link"
            }
            onClick={() => {
              navigate("/overview");
            }}
          >
            <span className="icon_span">
              <i className="fa-regular fa-file-lines"></i>
            </span>
            Overview
          </button>
        </li>
        {
          permissionArr?.includes("connectors")
          &&
          <li className="nav-item">
            <button
              className={
                params.pathname === "/connectors" ? "nav-link active" : "nav-link"
              }
              onClick={() => {
                navigate("/connectors");
              }}
            >
              <span className="icon_span">
                <i className="fas fa-network-wired"></i>
              </span>
              Connectors
            </button>
          </li>
        }
        {
          permissionArr?.includes("reports")
          &&
          <li className="nav-item">
            <button
              className={
                params.pathname === "/sales" || params.pathname === "/marketing" || params.pathname === "/financial" || params.pathname === "/operations" ? "nav-link active" : "nav-link"
              }
              data-toggle="collapse"
              data-target="#submenu2"
            >
              <span className="icon_span"><i className="fas fa-file-lines"></i></span> Reports{" "}
              <i className="fas fa-chevron-down custom-faicon"></i>
            </button>
            <ul
              className="list-unstyled flex-column inner_ul pl-3 collapse"
              id="submenu2"
            >
              <li className="nav-item">
                <button
                  className={
                    params.pathname === "/sales"
                      ? "nav-link active"
                      : "nav-link"
                  }
                  onClick={() => {
                    navigate("/sales");
                  }}
                >
                  Sales
                </button>
              </li>
              <li className="nav-item">
                <button
                  className={
                    params.pathname === "/marketing"
                      ? "nav-link active"
                      : "nav-link"
                  }
                  onClick={() => {
                    navigate("/marketing");
                  }}
                >
                  Marketing
                </button>
              </li>
              <li className="nav-item">
                <button
                  className={
                    params.pathname === "/financial"
                      ? "nav-link active"
                      : "nav-link"
                  }
                  onClick={() => {
                    navigate("/financial");
                  }}
                >
                  Financial
                </button>
              </li>
              <li className="nav-item">
                <button
                  className={
                    params.pathname === "/operations"
                      ? "nav-link active"
                      : "nav-link"
                  }
                  onClick={() => {
                    navigate("/operations");
                  }}
                >
                  Operations
                </button>
              </li>
            </ul>
          </li>
        }
        {
          permissionArr?.includes("sub-account")
          &&
          <li className="nav-item">
            <button
              className={
                params.pathname === "/staff" ? "nav-link active" : "nav-link"
              }
              onClick={() => {
                navigate("/staff");
              }}
            >
              <span className="icon_span"><i className="fas fa-user-circle"></i></span> Sub-Accounts
            </button>
          </li>
        }
      </ul>
    </div>
  );
}

export default Sidebar;

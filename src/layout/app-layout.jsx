import { Outlet } from "react-router-dom";
import DashboardNav from "./partials/DashboardNav";
import Sidebar from "./partials/Sidebar";
import { useGetUserProfileQuery } from "../redux/api/user/user-api";

export default function AppLayout() {
  const { data: userProfile } = useGetUserProfileQuery(undefined, {
    pollingInterval: 10000,
    refetchOnFocus: true
  });

  return (
    <div className="container-fluid" id="main">
      <div className="row row-offcanvas row-offcanvas-left">
        <Sidebar />
        <div className="col-md-9 col-lg-10 main">
          <DashboardNav />
          <div className="row overview_row">
            <div className="col-md-12">
              <div className="overview_div">
                <div className="financial_ddiv">
                  <Outlet />
                </div>
              </div>
            </div>
          </div>
          <footer className="footer">
            <p>Copyright © 2023</p>
          </footer>
        </div>
      </div>
    </div>
  );
}
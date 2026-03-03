import "../App.css";
import "../style.css";
import "bootstrap/dist/css/bootstrap.min.css";
import "swiper/css";
import "swiper/swiper.min.css";

import Footer from "./partials/Footer";
import Header from "./partials/Header";
import { Outlet } from "react-router-dom";


export default function WebsiteLayout() {
  return (
    <div className="Homepage_body">
      <Header />
      <Outlet/>
      <Footer />
    </div>
  );
}
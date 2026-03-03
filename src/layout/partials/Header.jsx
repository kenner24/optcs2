import { useEffect, useState } from "react";
import { Link, useLocation } from "react-router-dom"

export default function Header() {
  let location = useLocation();
  const token = localStorage.getItem("token");
  const [currentLocation, setCurrentLocation] = useState("/");

  useEffect(() => {
    setCurrentLocation(location.pathname)
  }, [location]);

  return (
    <header>
      <nav className="navbar navbar-expand-lg navbar-light bg-light navbar_custom">
        <div className="container">
          <Link className="navbar-brand" to="/" ><img src="./logo.png" alt="logo" /></Link>
          <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
            aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
            <span className="navbar-toggler-icon"></span>
          </button>
          <div className="collapse navbar-collapse navbar-collapse-custom" id="navbarText">
            <ul className="navbar-nav ml-auto mb-2 mb-lg-0">
              <li className="nav-item">
                <Link className={`nav-link ${currentLocation === "/" ? "active" : ""}`} aria-current="page" to={"/"} >Home</Link>
              </li>
              <li className="nav-item">
                <Link className={`nav-link ${currentLocation === "/about-us" ? "active" : ""}`} to={"/about-us"}>About us</Link>
              </li>
              <li className="nav-item">
                <Link className={`nav-link ${currentLocation === "/contact-us" ? "active" : ""}`} to={"/contact-us"}>Contact us</Link>
              </li>
              {
                (token?.length > 0)
                  ?
                  <li className="nav-item">
                    <Link className="nav-link signup" to={"/overview"}>Dashboard</Link>
                  </li>
                  :
                  <>
                    <li className="nav-item">
                      <Link className="nav-link signup" to={"/register"}>Register</Link>
                    </li>
                    <li className="nav-item">
                      <Link className="nav-link login" to={"/login"}>Login</Link>
                    </li>
                  </>              
              }

            </ul>
          </div>
        </div>
      </nav>
    </header>
  );
}
import { Outlet } from "react-router-dom";

export default function AuthLayout() {
  return (
    <div>
      <section className="login_outer">
        <img className="circle_pattern pulse" src="/pattern.png" alt="pattern" />
        <img className="wave1" src="/wave1.png" alt="wave1" />
        <img className="wave2" src="/wave2.png" alt="wave2" />
        <img className="pattern2 pulse" src="/pattern2.png" alt="pattern" />
        <div className="login_inner1">
          <div className="circle flash"></div>
          <Outlet />
        </div>
      </section>
    </div>
  );
}
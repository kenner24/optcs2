import React from "react";
import { useEmailLoginMutation } from "../../redux/api/auth/auth-api";
import { Link, useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import { useState } from "react";
import { useDispatch } from "react-redux";
import { setUserDetails } from "../../redux/slices/UserProfileSlice";
import LoginGoogle from "../../Components/authentication/LoginGoogle";
import Spinner from "react-bootstrap/Spinner";

const LoginPage = () => {
  const navigate = useNavigate();
  const dispatch = useDispatch();
  const notify = (text) => toast(text);

  const [EmailLogin, { isLoading }] = useEmailLoginMutation();
  const [email, setEmail] = useState();
  const [password, setPassword] = useState();
  const [rememberMe, setRememberMe] = useState(false);
  const [pwdType, setPwdType] = useState("password");


  const togglePassword = () => {
    if (pwdType === "password") {
      setPwdType("text");
      return;
    }
    setPwdType("password");
  }

  const formSubmitHandler = (event) => {
    event.preventDefault();
    const postData = {
      email: email,
      password: password,
      device_name: navigator.userAgent,
    };

    EmailLogin(postData)
      .unwrap()
      .then((res) => {
        toast(res.message);
        if (res.success == true) {
          navigate("/overview");
          window.history.pushState(null, window.location.href);
          window.history.pushState(null, window.location.href);
          window.history.pushState(null, window.location.href);
        }
        localStorage.setItem("token", res.data.token);
        localStorage.setItem("userProfile", JSON.stringify(res?.data?.user));
        dispatch(setUserDetails(res?.data?.user));
      })
      .catch((err) => {
        notify(err?.data?.message);
        if (err?.data?.message == "User not verified, please verify.") {
          navigate("/email-verification");
        }
      });
  };

  const handleRememberMeChange = (event) => {
    setRememberMe(event.target.checked);
  };

  return (
    <>
      <h1 className="welcome_title">Welcome!</h1>
      <p className="mb-3">Login to your account.</p>
      <form onSubmit={formSubmitHandler}>
        <label className="custom_label" htmlFor="email">Email</label>
        <input className="custom_input" id="email" type="email" placeholder="Enter Email Address" onChange={(e) => setEmail(e.target.value)} />
        <label className="custom_label" htmlFor="password">Password</label>
        <div className="password_div">
          <input className="custom_input" id="password" type={pwdType} placeholder="Enter Password" onChange={(e) => setPassword(e.target.value)} />
          <a onClick={() => togglePassword()} className="eye_icon"><i className="fa-regular fa-eye"></i></a>
        </div>
        <div className="remeber_forgot_div">
          <div className="remember_div">
            <label className="remebercontainer">Remember me
              <input
                type="checkbox"
                checked={rememberMe}
                onChange={handleRememberMeChange}
              />
              <span className="checkmark"></span>
            </label>
          </div>
          <Link className="forgot_password_a" to="/forgot-password">Forgot your password?</Link>
        </div>
        <button className="login_btn">
          Login
          {" "} {
            isLoading ? <Spinner animation="border" size="sm" /> : <i className="fa-solid fa-angles-right bounce"></i>
          }
        </button>
      </form>
      <div className="or_div"><span>OR</span></div>
      <LoginGoogle type="login" />
      <p className="dont_account">Don’t have an account? <Link to="/register">Create new Account</Link></p>
    </>
  );
}

export default LoginPage;
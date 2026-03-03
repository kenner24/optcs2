import React, { useState } from "react";
import Spinner from "react-bootstrap/Spinner";
import { useResetPasswordMutation } from "../../redux/api/auth/auth-api";
import { toast } from "react-toastify";
import { Link, useNavigate, useSearchParams } from "react-router-dom";

const ResetPasswordPage = () => {
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  const token = searchParams.get("token");
  const [ResetPassword, { isLoading }] = useResetPasswordMutation();
  const [password, setPassword] = useState(null);
  const [password_confirmation, setPasswordConfirmation] = useState(null);
  const [pwdErr, setPwdErr] = useState(null);

  const handleSubmit = async (event) => {
    event.preventDefault();
    await ResetPassword({
      password,
      password_confirmation,
      token
    })
      .unwrap()
      .then((res) => {
        toast(res?.message);
        navigate("/login");
      }).catch((error) => {
        if (error?.status === 422) {
          if (error?.data?.errors?.password) {
            setPwdErr(error?.data?.errors?.password[0]);
            return;
          }
        }
        toast(error?.data?.message);
      });
  }

  return (
    <>
      <h1 className="welcome_title">Reset password</h1>
      <p className="mb-3"></p>
      <form onSubmit={handleSubmit}>
        <label className="custom_label" htmlFor="password">Password</label>
        <input className="custom_input" type="password" id="password" placeholder="*************" onChange={(e) => setPassword(e.target.value)} />
        {
          pwdErr !== null &&
          <small id="emailHelp" className="validation-text">{pwdErr}</small>
        }
        <label className="custom_label" htmlFor="password_confirmation">Confirm Password</label>
        <input className="custom_input" type="password" id="password_confirmation" placeholder="*************" onChange={(e) => setPasswordConfirmation(e.target.value)} />
        <button className="login_btn">
          Reset Password
          {" "} {
            isLoading ? <Spinner animation="border" size="sm" /> : <i className="fa-solid fa-angles-right bounce"></i>
          }
        </button>
        <p className="dont_account">Don’t have an account? <Link to="/register">Create new Account</Link></p>
      </form>
    </>
  );
}

export default ResetPasswordPage;
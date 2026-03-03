import React, { useState } from "react";
import { useForgotPasswordMutation } from "../../redux/api/auth/auth-api";
import { toast } from "react-toastify";
import { Link } from "react-router-dom";
import Spinner from "react-bootstrap/Spinner";

const ForgotPasswordPage = () => {
  const [email, setEmail] = useState(null);
  const [ForgotPassword, {isLoading}] = useForgotPasswordMutation();
  
  const handleSubmit = async (event) => {
    event.preventDefault();
    await ForgotPassword({
      email
    })
      .unwrap()
      .then((res) => {
        toast(res?.message);
      }).catch((error) => {
        toast(error?.data?.message);
      });
  }
  
  return (
    <>
      <h1 className="welcome_title">Forgot password</h1>
      <p className="mb-3">Enter the email address associated with your account, and we&apos;ll send you a
          link to reset your password.</p>
      <form onSubmit={handleSubmit}>
        <label className="custom_label" htmlFor="email">Email</label>
        <input className="custom_input" type="text" id="email" placeholder="loreum@gmail.com" onChange={(e) => setEmail(e.target.value)} />
        <button className="login_btn">
          Send Reset Password Link 
          {" "} {
            isLoading ? <Spinner animation="border" size="sm" /> : <i className="fa-solid fa-angles-right bounce"></i>
          }
        </button>
        <p className="dont_account">Don’t have an account? <Link to="/register">Create new Account</Link></p>
      </form>
    </>
  );
}

export default ForgotPasswordPage;
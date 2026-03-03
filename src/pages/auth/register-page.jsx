import React, { useState } from "react";
import Spinner from "react-bootstrap/Spinner";
import { useRegisterMutation } from "../../redux/api/auth/auth-api";
import { toast } from "react-toastify";
import { Link, useNavigate } from "react-router-dom";
import LoginGoogle from "../../Components/authentication/LoginGoogle";

const RegisterPage = () => {
  const navigate = useNavigate();
  const [Register, { isLoading }] = useRegisterMutation();
  const [name, setName] = useState(null);
  const [email, setEmail] = useState(null);
  const [password, setPassword] = useState(null);
  const [confirmPassword, setConfirmPassword] = useState(null);
  const [username, setUsername] = useState(null);
  const [pwdType, setPwdType] = useState("password");
  const [confirmPwdType, setConfirmPwdType] = useState("password");
  const [pwdErr, setPwdErr] = useState(null);
  const [nameErr, setNameErr] = useState(null);
  const [usernameErr, setUserNameErr] = useState(null);
  const [emailErr, setEmailErr] = useState(null);

  const formSubmitHandler = async (event) => {
    event.preventDefault();
    setPwdErr(null);
    setNameErr(null);
    setUserNameErr(null);
    setEmailErr(null);
    const payload = {
      name: name,
      email: email,
      password: password,
      password_confirmation: confirmPassword,
      username: username
    }

    await Register(payload)
      .unwrap()
      .then((res) => {
        toast(res?.message);
        setName(null);
        setEmail(null);
        setPassword(null);
        setConfirmPassword(null);
        setUsername(null);
        setPwdType(null);
        setConfirmPwdType(null);
        setPwdErr(null);
        setNameErr(null);
        setUserNameErr(null);
        setEmailErr(null);
        navigate("/register-success");
      }).catch((error) => {
        if (error?.status === 422) {
          if (error?.data?.errors?.password) {
            if (error?.data?.errors?.password[0] == "The password confirmation does not match.") {
              setPwdErr("The password confirmation does not match.");
            } else {
              setPwdErr("The password must be atleast 8 characters long and must contain at least one uppercase letter, one lowercase letter, one symbol, and one number.");
            }
          }
          if (error?.data?.errors?.name) {
            setNameErr(error?.data?.errors?.name[0]);
          }
          if (error?.data?.errors?.username) {
            setUserNameErr(error?.data?.errors?.username[0]);
          }
          if (error?.data?.errors?.email) {
            setEmailErr(error?.data?.errors?.email[0]);
          }
          return;
        }
        toast(error?.data?.message);
      });
  }

  const togglePassword = (field) => {
    if (field === "pwd") {
      if (pwdType === "password") {
        setPwdType("text");
        return;
      }
      setPwdType("password");
    }

    if (field === "cfmPwd") {
      if (confirmPwdType === "password") {
        setConfirmPwdType("text");
        return;
      }
      setConfirmPwdType("password");
    }
  }

  return (
    <>
      <h1 className="welcome_title">Create an Account</h1>
      <p className="mb-3">Enter your details below.</p>
      <form onSubmit={formSubmitHandler}>
        <label className="custom_label" htmlFor="name">Name</label>
        <input className="custom_input" type="text" placeholder="Enter name" id="name" onChange={(e) => setName(e.target.value)} />
        {
          nameErr !== null &&
          <small id="emailHelp" className="validation-text">{nameErr}</small>
        }

        <label className="custom_label" htmlFor="email">Email</label>
        <input className="custom_input" type="email" placeholder="Enter email address" id="email" onChange={(e) => setEmail(e.target.value)} />
        {
          emailErr !== null &&
          <small id="emailHelp" className="validation-text">{emailErr}</small>
        }

        <label className="custom_label" htmlFor="password">Password</label>
        <div className="password_div">
          <input className="custom_input" type={pwdType} placeholder="**********" id="password" onChange={(e) => setPassword(e.target.value)} />
          {
            pwdErr !== null &&
            <small id="emailHelp" className="validation-text">{pwdErr}</small>
          }
          <a onClick={() => togglePassword("pwd")} className="eye_icon">
            <i className="fa-regular fa-eye"></i>
          </a>
        </div>

        <label className="custom_label" htmlFor="password_confirmation">Confirm Password</label>
        <div className="password_div">
          <input className="custom_input" type={confirmPwdType} placeholder="**********" id="password_confirmation" onChange={(e) => setConfirmPassword(e.target.value)} />
          <a onClick={() => togglePassword("cfmPwd")} className="eye_icon">
            <i className="fa-regular fa-eye"></i>
          </a>
        </div>

        <label className="custom_label" htmlFor="username">Username</label>
        <input className="custom_input" type="text" placeholder="Enter username" id="username" onChange={(e) => setUsername(e.target.value)} />
        {
          usernameErr !== null &&
          <small id="emailHelp" className="validation-text">{usernameErr}</small>
        }

        <button className="login_btn">Create Account {" "} {
          isLoading ? <Spinner animation="border" size="sm" /> : <i className="fa-solid fa-angles-right bounce"></i>
        }</button>
      </form>
      <div className="or_div"><span>OR</span></div>
      <LoginGoogle type="signup" />
      <p className="dont_account">Have an account? <Link to="/login">Login</Link></p>
    </>
  );
}

export default RegisterPage;
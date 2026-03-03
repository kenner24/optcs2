import React, { useState } from "react";
import Spinner from "react-bootstrap/Spinner";
import { toast } from "react-toastify";
import { useResendVerificationEmailMutation } from "../../redux/api/auth/auth-api";

function ResendVerificationMailPage() {
  const [email, setEmail] = useState(null);
  const [emailErr, setEmailErr] = useState(null);
  const [ResendVerificationEmail, { isLoading }] = useResendVerificationEmailMutation();

  const forSubmitHandler = (event) => {
    event.preventDefault();
    if (email === null) {
      setEmailErr("Email id is required");
      return;
    }
    ResendVerificationEmail({
      email
    }).unwrap()
      .then((res) => {
        toast(res?.message);
      }).catch((err) => {
        if (err?.status === 422) {
          if (err?.data?.errors?.email) {
            setEmailErr(err?.data?.errors?.email[0]);
          }
          return;
        }
        toast(err?.data?.message)
      });

  }
  return (
    <>
      <h1 className="welcome_title">Resend Confirmation Instructions</h1>
      <p className="mb-3">Enter the email address associated with your account and we&apos;ll send you the confirmation instruction.</p>
      <form onSubmit={forSubmitHandler}>
        <label className="custom_label" htmlFor="email">Email</label>
        <input className="custom_input" type="text" id="email" placeholder="loreum@gmail.com" onChange={(e) => setEmail(e.target.value)} />
        {
          emailErr !== null &&
          <small id="emailHelp" className="validation-text">{emailErr}</small>
        }
        <button className="login_btn">
          Resend Instruction
          {" "} {
            isLoading ? <Spinner animation="border" size="sm" /> : <i className="fa-solid fa-angles-right bounce"></i>
          }
        </button>
      </form>
    </>
  );
}

export default ResendVerificationMailPage;

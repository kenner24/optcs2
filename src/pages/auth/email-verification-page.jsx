import React, { useEffect } from "react";
import { useNavigate, useSearchParams } from "react-router-dom";
import { toast } from "react-toastify";
import { useVerifyEmailQuery } from "../../redux/api/auth/auth-api";


function EmailVerificationPage() {
  const navigate = useNavigate();
  const [searchParams] = useSearchParams();
  const token = searchParams.get("confirmation_token");
  const { data, error, isLoading } = useVerifyEmailQuery(token);

  useEffect(() => {
    if (error?.status === 400) {
      toast(error?.data?.message);
      navigate("/confirmation");
    }

    if (error?.status === 404) {
      toast(error?.data?.message);
      navigate("/login");
    }

    if (data?.success) {
      toast(data?.message);
      navigate("/login");
    }
  }, [isLoading]);

  if (token === null) {
    navigate("/confirmation");
    toast("Invalid verification url");
  } else {
    return (
      <>
        <h1 className="welcome_title">Verifying email....</h1>
      </>
    );
  }
}

export default EmailVerificationPage;

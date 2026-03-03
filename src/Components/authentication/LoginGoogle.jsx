import React from "react";
import { GoogleOAuthProvider } from "@react-oauth/google";
import GoogleLoginCustomButton from "../../Containers/GoogleLoginCustomButton";
import { googleConfig } from "../../config/google.config";

function LoginGoogle({ type }) {
  if (!googleConfig.clientId) {
    return null;
  }
  return (
    <GoogleOAuthProvider clientId={googleConfig.clientId}>
      <GoogleLoginCustomButton type={type} />
    </GoogleOAuthProvider>
  );
}

export default LoginGoogle;

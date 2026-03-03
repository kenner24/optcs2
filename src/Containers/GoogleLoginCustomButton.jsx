import { useGoogleLogin } from "@react-oauth/google";
import { useNavigate } from "react-router-dom";
import { toast } from "react-toastify";
import {
  useRegisterSocialSignupMutation,
  useSocialLoginMutation,
} from "../redux/api/auth/auth-api";

function GoogleLoginCustomButton({ type }) {
  const navigate = useNavigate();
  const notify = (text) => toast(text);

  const [RegisterSocialSignup] = useRegisterSocialSignupMutation();
  const [SocialLogin] = useSocialLoginMutation();

  const login = useGoogleLogin({
    onSuccess: (credentialResponse) => {
      let options = {
        method: "GET",
        headers: new Headers({
          Authorization: "Bearer " + credentialResponse?.access_token,
        }),
      };
      fetch(
        `https://www.googleapis.com/oauth2/v1/userinfo?access_token=${credentialResponse?.access_token}`,
        options,
      )
        .then(function (res) {
          return res.json();
        })
        .then(function (resJson) {
          type === "signup"
            ? responseGoogle(resJson)
            : responseLoginGoogle(resJson);
          return resJson;
        });
    },
  });

  const responseLoginGoogle = (response) => {
    try {
      let postData = {
        provider_name: "google",
        provider_id: response.id,
        email: response.email,
        device_name: navigator.userAgent,
      };
      SocialLogin(postData)
        .unwrap()
        .then((res) => {
          toast(res.message);
          localStorage.setItem("token", res.data.token);
          localStorage.setItem("userProfile", JSON.stringify(res?.data?.user));
          navigate("/overview");
        })
        .catch((err) => {
          if (err.status === 400) {
            if (typeof err?.data?.message !== "undefined") {
              notify(err?.data?.message);
            }

            if (typeof err?.data?.data !== "undefined") {
              for (let key of Object.keys(err.data.data)) {
                notify(err.data.data[key][0]);
              }
            }
          } else {
            notify(err?.data?.message || err?.message);
          }
        });
    } catch (error) {
      toast(error?.message || "Something went wrong");
    }
  };

  const responseGoogle = (response) => {
    let postData = {
      first_name: response.given_name,
      last_name: response.family_name,
      provider_name: "google",
      provider_id: response.id,
      image: response.picture,
      email: response.email,
      device_name: navigator.userAgent,
    };

    RegisterSocialSignup(postData)
      .unwrap()
      .then((res) => {
        toast(res.message);
        if (res?.success) {
          localStorage.setItem("token", res.data.token);
          localStorage.setItem("userProfile", JSON.stringify(res?.data?.user));
          navigate("/overview");
        }
      })
      .catch((err) => {
        if (err.status === 400) {
          for (let key of Object.keys(err.data.data)) {
            notify(err.data.data[key][0]);
          }
        } else {
          notify(err?.data?.message || err?.message);
        }
      });
  };

  return (
    <button onClick={() => login()} className="login_btn google_continue">
      <img src="/google.svg" alt="google" />Continue with Google
    </button>
  );
}

export default GoogleLoginCustomButton;

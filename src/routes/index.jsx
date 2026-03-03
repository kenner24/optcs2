import {
  createBrowserRouter,
} from "react-router-dom";
import WebsiteLayout from "../layout/website-layout";
import ErrorPage from "../pages/error-page";
import Homepage from "../pages/website/home-page";
import AboutUs from "../pages/website/about-us-page";
import ContactUsPage from "../pages/website/contact-us-page";
import FaqPage from "../pages/website/faq-page";
import TermsConditionPage from "../pages/website/terms-condition-page";
import PrivacyPolicyPage from "../pages/website/privacy-policy-page";
import AppLayout from "../layout/app-layout";
import StaffPage from "../pages/staff/staff-page";
import SettingsPage from "../pages/settings/settings-page";
import AuthGuard from "../guard/auth-guard";
import AuthLayout from "../layout/auth-layout";
import UnauthorizedPage from "../pages/unauthorized-page";
import ConnectorPage from "../pages/connector/connector-page";
import OperationPage from "../pages/reports/operation-page";
import FinancialPage from "../pages/reports/financial-page";
import MarketingPage from "../pages/reports/marketing-page";
import SalesPage from "../pages/reports/sales-page";
import OverviewPage from "../pages/dashboard/overview-page";
import LoginPage from "../pages/auth/login-page";
import RegisterPage from "../pages/auth/register-page";
import RegisterSuccessPage from "../pages/auth/register-success-page";
import ForgotPasswordPage from "../pages/auth/forgot-password-page";
import ResetPasswordPage from "../pages/auth/reset-password-page";
import EmailVerificationPage from "../pages/auth/email-verification-page";
import ResendVerificationMailPage from "../pages/auth/resend-verification-mail-page";


const router = createBrowserRouter([
  {
    path: "/",
    element: <WebsiteLayout />,
    errorElement: <ErrorPage />,
    children: [
      {
        path: "/",
        element: <Homepage />
      },
      {
        path: "/about-us",
        element: <AboutUs />
      },
      {
        path: "/contact-us",
        element: <ContactUsPage />
      },
      {
        path: "/faq",
        element: <FaqPage />
      },
      {
        path: "/terms-and-condition",
        element: <TermsConditionPage />
      },
      {
        path: "/privacy-policy",
        element: <PrivacyPolicyPage />
      },
    ],
  },
  // auth routes
  {
    path: "/",
    element: <AuthLayout />,
    errorElement: <ErrorPage />,
    children: [
      {
        path: "/register",
        element: <RegisterPage />,
      },
      {
        path: "/register-success",
        element: <RegisterSuccessPage />,
      },
      {
        path: "/login",
        element: <LoginPage />,
      },
      {
        path: "/forgot-password",
        element: <ForgotPasswordPage />,
      },
      {
        path: "/reset-password",
        element: <ResetPasswordPage />,
      },
      {
        path: "/confirm-email",
        element: <EmailVerificationPage />,
      },
      {
        path: "/confirmation",
        element: <ResendVerificationMailPage />,
      },
    ]
  },
  // app routes
  {
    path: "/",
    element: <AuthGuard />,
    errorElement: <ErrorPage />,
    children: [
      {
        path: "/",
        element: <AppLayout />,
        children: [
          {
            path: "/staff",
            element: <StaffPage />
          },
          {
            path: "/settings",
            element: <SettingsPage />
          },
          {
            path: "/connectors",
            element: <ConnectorPage />,
          },
          {
            path: "/overview",
            element: <OverviewPage />,
          },
          {
            path: "/sales",
            element: <SalesPage />,
          },
          {
            path: "/marketing",
            element: <MarketingPage />,
          },
          {
            path: "/financial",
            element: <FinancialPage />,
          },
          {
            path: "/operations",
            element: <OperationPage />,
          },
        ],
      },
    ],
  },
  {
    path: "/unauthorized",
    element: <UnauthorizedPage />
  }
]);

export default router;
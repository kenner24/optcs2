import { apiSlice } from "../api-slice"

const authApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    Register: builder.mutation({
      query: (data) => ({
        url: "/register",
        method: "POST",
        mode: "cors",
        body: data,
      }),
    }),
    VerifyEmail: builder.query({
      query: (data) => ({
        url: `/verify-email?code=${data}`,
        method: "GET",
      }),
    }),
    ResendVerificationEmail: builder.mutation({
      query: (data) => ({
        url: "/mail-verification-notification",
        method: "POST",
        mode: "cors",
        body: data,
      }),
    }),
    RegisterSocialSignup: builder.mutation({
      query: (data) => ({
        url: "/register-social-account",
        method: "POST",
        body: data,
      }),
    }),
    SocialLogin: builder.mutation({
      query: (data) => ({
        url: "/social-login",
        method: "POST",
        body: data,
      }),
    }),
    EmailLogin: builder.mutation({
      query: (data) => ({
        url: "/login",
        mode: "cors",
        method: "POST",
        body: data,
      }),
    }),
    ForgotPassword: builder.mutation({
      query: (data) => ({
        url: "/forgot-password",
        method: "POST",
        body: data,
      }),
    }),
    ResetPassword: builder.mutation({
      query: (data) => ({
        url: "/reset-password",
        method: "POST",
        body: data,
      }),
    }),
    signOut: builder.mutation({
      query: () => ({
        url: "/user-logout",
        method: "POST",
      }),
    }),
  }),
});

export const {
  useRegisterMutation,
  useRegisterSocialSignupMutation,
  useSocialLoginMutation,
  useEmailLoginMutation,
  useForgotPasswordMutation,
  useResetPasswordMutation,
  useVerifyEmailQuery,
  useResendVerificationEmailMutation,
  useSignOutMutation
} = authApi;
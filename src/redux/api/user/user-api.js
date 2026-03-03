import { apiSlice } from "../api-slice"

export const userApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    GetUserProfile: builder.query({
      query: () => ({
        url: "/user-profile",
        method: "GET",
      }),
      providesTags: ["userProfile"],
    }),
    ChangePassword: builder.mutation({
      query: (data) => ({
        url: "/user-change-password",
        method: "POST",
        mode: "cors",
        body: data,
      }),
    }),
    UpdateProfile: builder.mutation({
      query: (data) => ({
        url: "/user-profile-update",
        method: "PUT",
        mode: "cors",
        body: data,
      }),
    }),
    AssignPermission: builder.mutation({
      query: (data) => ({
        url: "/change-staff-permission",
        method: "PUT",
        mode: "cors",
        body: data,
      }),
    }),
  }),
});

export const {
  useGetUserProfileQuery,
  useChangePasswordMutation,
  useUpdateProfileMutation,
  useAssignPermissionMutation
} = userApi;
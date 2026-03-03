import { apiSlice } from "../api-slice";

export const staffApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    createStaff: builder.mutation({
      query: (data) => ({
        url: "/staff-add",
        method: "POST",
        body: data,
      }),
    }),
    editStaff: builder.mutation({
      query: (data) => ({
        url: "/staff-edit",
        method: "PUT",
        body: data,
      }),
    }),
    changeStaffStatus: builder.mutation({
      query: (data) => ({
        url: "/change-staff-status",
        method: "POST",
        body: data,
      }),
    }),
    deleteStaff: builder.mutation({
      query: (data) => ({
        url: "/staff-delete",
        method: "DELETE",
        body: data,
      }),
    }),
    getStaffList: builder.query({
      query: (payload) => ({
        url: `/staff-list?pageSize=${payload.pageSize}&pageIndex=${payload.pageIndex}`,
        method: "GET",
      }),
      providesTags: ["getStaffList"],
    }),
  }),
});

export const {
  useGetStaffListQuery,
  useDeleteStaffMutation,
  useCreateStaffMutation,
  useChangeStaffStatusMutation,
  useEditStaffMutation
} = staffApi;

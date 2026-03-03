import { createSlice } from "@reduxjs/toolkit";
import { userApi } from "../api/user/user-api";

const userProfile = JSON.parse(localStorage.getItem("userProfile"));
const initialState = {
  id: userProfile?.id ?? null,
  name: userProfile?.name ?? null,
  email: userProfile?.email ?? null,
  image: userProfile?.image ?? null,
  status: userProfile?.status ?? null,
  company_name: userProfile?.company_name ?? null,
  username: userProfile?.username ?? null,
  sales_force_access: userProfile?.sales_force_access ?? false,
  google_sheet_access: userProfile?.google_sheet_access ?? false,
  quick_books_access: userProfile?.quick_books_access ?? false,
  domain_sector: userProfile?.domain_sector ?? null,
  total_employees: userProfile?.total_employees ?? 0,
  work_email: userProfile?.work_email ?? null,
  assigned_permissions: userProfile?.assigned_permissions ?? [],
  company_details: userProfile?.company_details ?? []
};

export const UserProfileSlice = createSlice({
  name: "UserProfile",
  initialState,
  reducers: {
    setUserDetails(state, action) {
      state.id = action.payload?.id;
      state.name = action.payload?.name;
      state.email = action.payload?.email;
      state.image = action.payload?.image;
      state.status = action.payload?.status;
      state.company_name = action.payload?.company_name;
      state.username = action.payload?.username;
      state.sales_force_access = action.payload?.sales_force_access;
      state.google_sheet_access = action.payload?.google_sheet_access;
      state.quick_books_access = action.payload?.quick_books_access;
      state.domain_sector = action.payload?.domain_sector;
      state.total_employees = action.payload?.total_employees;
      state.work_email = action.payload?.work_email;
      state.assigned_permissions = action.payload?.assigned_permissions;
      state.company_details = action.payload?.company_details;
    },
    setQuickBookAccess(state, action) {
      state.quick_books_access = action.payload?.quick_books_access;
    },
    setSalesForceAccess(state, action) {
      state.sales_force_access = action.payload?.sales_force_access;
    },
    setGoogleSheetAccess(state, action) {
      state.google_sheet_access = action.payload?.google_sheet_access;
    },
    resetProfile: (state, action) => {
      state.id = null;
      state.name = null;
      state.email = null;
      state.image = null;
      state.status = null;
      state.company_name = null;
      state.username = null;
      state.sales_force_access = false;
      state.google_sheet_access = false;
      state.quick_books_access = false;
      state.domain_sector = null;
      state.total_employees = 0;
      state.work_email = null;
      state.assigned_permissions = [];
      state.company_details = [];
    },
  },
  extraReducers: (builder) => {
    builder.addMatcher(
      userApi.endpoints.GetUserProfile.matchFulfilled,
      (state, { payload }) => {
        if (payload?.data && Object.keys(payload?.data).length > 0) {
          localStorage.setItem("userProfile", JSON.stringify(payload?.data));
          state.id = payload?.data.id;
          state.name = payload?.data.name;
          state.email = payload?.data.email;
          state.image = payload?.data.image;
          state.status = payload?.data.status;
          state.company_name = payload?.data.company_name;
          state.username = payload?.data.username;
          state.sales_force_access = payload?.data.sales_force_access;
          state.google_sheet_access = payload?.data.google_sheet_access;
          state.quick_books_access = payload?.data.quick_books_access;
          state.domain_sector = payload?.data.domain_sector;
          state.total_employees = payload?.data.total_employees;
          state.work_email = payload?.data.work_email;
          state.assigned_permissions = payload?.data.assigned_permissions;
          state.company_details = payload?.data.company_details;
        }
      }
    )
  },
});

// Action creators are generated for each case reducer function
export const {
  setUserDetails,
  resetProfile,
  setQuickBookAccess,
  setSalesForceAccess,
  setGoogleSheetAccess
} = UserProfileSlice.actions;
export const selectUserDetails = (state) => state?.userProfile;
export default UserProfileSlice.reducer;

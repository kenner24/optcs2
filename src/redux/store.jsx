import { configureStore } from "@reduxjs/toolkit";
import { apiSlice } from "./api/api-slice";
import UserProfileSlice from "./slices/UserProfileSlice";

export const store = configureStore({
  reducer: {
    userProfile: UserProfileSlice,
    [apiSlice.reducerPath]: apiSlice.reducer,
  },
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware().concat(
      apiSlice.middleware
    ),
});

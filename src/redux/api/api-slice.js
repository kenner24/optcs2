import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react"
import { appConfig } from "../../config/app.config";

export const apiSlice = createApi({
  baseQuery: fetchBaseQuery({
    baseUrl: appConfig.API_URL,
    prepareHeaders: (headers) => {
      const accessToken = localStorage.getItem("token");
      headers.set("Authorization", `Bearer ${accessToken}`);
      headers.set("Content-Type", "application/json");
      headers.set("Accept", "application/json");
      return headers;
    },
  }),
  endpoints: () => ({}),
});
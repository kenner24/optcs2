import { apiSlice } from "../api-slice";
import qs from "qs";

const googleSheetApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    getGoogleSpreadSheetList: builder.query({
      query: () => ({
        url: "/google-spreadsheet-list",
        method: "GET",
      }),
      providesTags: ["spreadSheet"],
    }),
    getGoogleSheetList: builder.query({
      query: (id) => ({
        url: `/fetch-spreadsheet-sheets-list/${id}`,
        method: "GET",
      }),
      providesTags: ["sheetList"],
    }),
    getSheetHeadersList: builder.query({
      query: (data) => ({
        url: `/fetch-sheets-headers/${data.id}?${qs.stringify({ sheet_name: data.sheet_name })}`,
        method: "GET",
      }),
      providesTags: ["sheetHeaders"],
    }),
  }),
});

export const {
  useGetGoogleSpreadSheetListQuery,
  useGetGoogleSheetListQuery,
  useGetSheetHeadersListQuery,
} = googleSheetApi;

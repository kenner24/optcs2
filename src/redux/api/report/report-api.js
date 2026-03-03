import { apiSlice } from "../api-slice";
import qs from "qs";

export const reportApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    GetReportChartData: builder.query({
      query: (payload) => ({
        url: `/reports?${qs.stringify({
          report_type: payload.report_type,
          year: payload.year
        })}`,
        method: "GET",
      }),
      providesTags: ["reportChartsData"],
    }),
    GetReportChartGoalsData: builder.query({
      query: (payload) => ({
        url: `/fetch-report-goals?${qs.stringify({
          report_name: payload.report_name,
          year: payload.year
        })}`,
        method: "GET",
      }),
      providesTags: ["reportGoals"],
    }),
    SaveReportGoalSetting: builder.mutation({
      query: (payload) => ({
        url: "/save-report-goals",
        method: "POST",
        mode: "cors",
        body: payload,
      }),
      invalidatesTags: ["reportGoals", "reportChartsData"],
    }),
  }),
});

export const {
  useGetReportChartDataQuery,
  useGetReportChartGoalsDataQuery,
  useSaveReportGoalSettingMutation
} = reportApi;
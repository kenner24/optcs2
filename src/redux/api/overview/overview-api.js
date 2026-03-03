import { apiSlice } from "../api-slice"

const overview = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    getOverviewPageData: builder.query({
      query: (year) => ({
        url: `/overview?year=${year}`,
        method: "GET",
      }),
    }),
  }),
});

export const {
  useGetOverviewPageDataQuery,
} = overview;
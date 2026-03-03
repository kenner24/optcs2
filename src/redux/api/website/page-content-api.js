import { apiSlice } from "../api-slice";

export const pageContentApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    getPageContent: builder.query({
      query: (pageType) => ({
        url: `/page-content?page_type=${pageType}`,
        method: "GET",
      }),
    }),
  }),
});

export const {
  useGetPageContentQuery,
} = pageContentApi;
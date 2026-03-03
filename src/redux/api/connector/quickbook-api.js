import { apiSlice } from "../api-slice";

const quickBookApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    GetBudgetList: builder.query({
      query: () => ({
        url: "quickbook-budget-list",
        method: "GET",
      }),
    }),
  }),
});

export const {
  useGetBudgetListQuery,
} = quickBookApi;

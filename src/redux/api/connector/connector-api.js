import { apiSlice } from "../api-slice";

const connectorApi = apiSlice.injectEndpoints({
  endpoints: (builder) => ({
    GetOauthAuthorizationURL: builder.query({
      query: (data) => ({
        url: `/connectors/authorization-endpoint?connector_type=${data}`,
        method: "GET",
      }),
    }),
    AddConnector: builder.mutation({
      query: (data) => ({
        url: "/connectors/add",
        method: "POST",
        mode: "cors",
        body: data,
      }),
      invalidatesTags: ["userProfile"],
    }),
    SaveConnectorPreferences: builder.mutation({
      query: (data) => ({
        url: "/connectors/add-preference",
        method: "POST",
        body: data,
      }),
      invalidatesTags: ["fetchPreferences"],
    }),
    FetchPreference: builder.query({
      query: () => ({
        url: "/connectors/fetch-preferences",
        method: "GET",
      }),
      providesTags: ["fetchPreferences"],
    }),
  }),
});

export const {
  useAddConnectorMutation,
  useGetOauthAuthorizationURLQuery,
  useFetchPreferenceQuery,
  useSaveConnectorPreferencesMutation,
} = connectorApi;

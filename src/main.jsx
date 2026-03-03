import React from "react";
import ReactDOM from "react-dom/client";
import "./index.css";
import "./style.css";
import "react-toastify/dist/ReactToastify.min.css";
import { RouterProvider } from "react-router-dom";
import { Provider } from "react-redux";
import { ToastContainer } from "react-toastify";
import router from "./routes";
import { store } from "./redux/store";

ReactDOM.createRoot(document.getElementById("root")).render(
  <Provider store={store}>
    <ToastContainer />
    <RouterProvider router={router} />
  </Provider>,
);

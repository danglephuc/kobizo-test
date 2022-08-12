import React from "react";
import ReactDOM from "react-dom/client";
import "element-theme-default";
import "./index.css";
import { App } from "./App";
import { i18n } from "element-react";
import locale from "element-react/src/locale/lang/en";
import reportWebVitals from "./reportWebVitals";
import { BrowserRouter } from "react-router-dom";

i18n.use(locale);

const root = ReactDOM.createRoot(document.getElementById("root"));
root.render(
  <BrowserRouter>
    <App />
  </BrowserRouter>
);

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();

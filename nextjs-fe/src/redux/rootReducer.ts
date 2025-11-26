// src/redux/rootReducer.ts
import { combineReducers } from "@reduxjs/toolkit";
import authReducer from "./slices/authSlice";
import commonReducer from "./slices/commonSlice";

const rootReducer = combineReducers({
  auth: authReducer,
  common: commonReducer,
});

export default rootReducer;
export type RootState = ReturnType<typeof rootReducer>;

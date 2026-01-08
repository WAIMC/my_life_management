// src/redux/rootReducer.ts
import { combineReducers } from "@reduxjs/toolkit";
import commonReducer from "./slices/commonSlice";

const rootReducer = combineReducers({
  common: commonReducer,
});

export default rootReducer;
export type RootState = ReturnType<typeof rootReducer>;

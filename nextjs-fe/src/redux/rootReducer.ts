// src/redux/rootReducer.ts
import { combineReducers } from "@reduxjs/toolkit";
import exampleReducer from "./slices/exampleSlice";
import todoReducer from "./slices/todoSlice";
import authReducer from "./slices/authSlice";
import commonReducer from "./slices/commonSlice";

const rootReducer = combineReducers({
  example: exampleReducer,
  todo: todoReducer,
  auth: authReducer,
  common: commonReducer,
});

export default rootReducer;
export type RootState = ReturnType<typeof rootReducer>;

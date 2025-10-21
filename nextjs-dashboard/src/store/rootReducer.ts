import { combineReducers } from "@reduxjs/toolkit";
import counter from "./slices/counterSlice";
import account from "./slices/accountSlice";

const rootReducer = combineReducers({
  counter,
  account,
});

export type RootState = ReturnType<typeof rootReducer>;
export default rootReducer;

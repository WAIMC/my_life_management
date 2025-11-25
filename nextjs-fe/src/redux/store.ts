import { configureStore } from "@reduxjs/toolkit";
import rootReducer from "./rootReducer";

/**
 * Redux Store Configuration
 * 
 * Note: Redux Saga has been removed in favor of TanStack Query for data fetching.
 * Redux is now only used for client-side UI state (auth, common state).
 * Server state is managed by TanStack Query.
 */

export const makeStore = () => {
  const store = configureStore({
    reducer: rootReducer,
    middleware: (getDefaultMiddleware) =>
      getDefaultMiddleware({
        serializableCheck: false
      }),
    devTools: process.env.NODE_ENV !== 'production',
  });

  return store;
};

export type AppStore = ReturnType<typeof makeStore>;
export type RootState = ReturnType<AppStore['getState']>;
export type AppDispatch = AppStore['dispatch'];

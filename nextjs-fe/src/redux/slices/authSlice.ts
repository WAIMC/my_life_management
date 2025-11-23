import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { AuthState } from '@/types/authType';

const initialState: AuthState = {
  accessToken: null,
  isAuthenticated: false,
  redirectUrl: null,
  tabId: null,
  leaderId: null,
  refreshAtTime: null,
  authInitialized: false,
};

const authSlice = createSlice({
  name: 'auth',
  initialState,
  reducers: {
    setAuth: (state, action: PayloadAction<string>) => {
      state.accessToken = action.payload;
      state.isAuthenticated = true;
    },
    clearAuth: () => initialState,
    setRedirectUrl: (state, action: PayloadAction<string | null>) => {
      state.redirectUrl = action.payload;
    },
    setTabId: (state, action: PayloadAction<string>) => {
      state.tabId = action.payload;
    },
    setLeaderId: (state, action: PayloadAction<string>) => {
      state.leaderId = action.payload;
    },
    setRefreshAtTime: (state, action: PayloadAction<number>) => {
      state.refreshAtTime = action.payload;
    },
    setAuthInitialized: (state, action: PayloadAction<boolean>) => {
      state.authInitialized = action.payload;
    },
    loginRequest: () => {
      // Saga will handle this
    },
    logoutRequest: () => {
      // Saga will handle this
    },
  },
});

export const {
  setAuth,
  clearAuth,
  setRedirectUrl,
  setTabId,
  setLeaderId,
  setRefreshAtTime,
  setAuthInitialized,
  loginRequest,
  logoutRequest,
} = authSlice.actions;
export default authSlice.reducer;


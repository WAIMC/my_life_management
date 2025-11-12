import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { AuthState } from '@/types/authType';

const initialState: AuthState = {
  accessToken: null,
  isAuthenticated: false,
  redirectUrl: null,
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
  },
});

export const { setAuth, clearAuth, setRedirectUrl } = authSlice.actions;
export default authSlice.reducer;

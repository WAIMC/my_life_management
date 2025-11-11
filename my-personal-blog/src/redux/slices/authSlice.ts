import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { AuthState } from '@/types/authType';

const initialState: AuthState = {
  accessToken: null,
  isAuthenticated: false,
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
  },
});

export const { setAuth, clearAuth } = authSlice.actions;
export default authSlice.reducer;

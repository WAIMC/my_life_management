import { createSlice, PayloadAction } from '@reduxjs/toolkit';

interface CommonState {
  isLoading: boolean;
  message: string | null;
  error: string | null;
}

const initialState: CommonState = {
  isLoading: false,
  message: null,
  error: null,
};

const commonSlice = createSlice({
  name: 'common',
  initialState,
  reducers: {
    setLoading: (state, action: PayloadAction<boolean>) => {
      state.isLoading = action.payload;
    },
    setMessage: (state, action: PayloadAction<string | null>) => {
      state.message = action.payload;
    },
    setError: (state, action: PayloadAction<string | null>) => {
      state.error = action.payload;
    },
    resetCommon: () => initialState,
  },
});

export const { setLoading, setMessage, setError, resetCommon } = commonSlice.actions;
export default commonSlice.reducer;

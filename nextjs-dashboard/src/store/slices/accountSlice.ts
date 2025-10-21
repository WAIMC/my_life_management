import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import type {
  Account,
  AccountState,
  FetchAccountPayload,
  FetchAccountsPayload,
  CreateAccountPayload,
  UpdateAccountPayload,
  DeleteAccountPayload,
} from '../types/accountTypes';

const initialState: AccountState = {
  accounts: [],
  currentAccount: null,
  loading: false,
  error: null,
  success: false,
};

const accountSlice = createSlice({
  name: 'account',
  initialState,
  reducers: {
    // Fetch Account
    fetchAccountRequest: (state, action: PayloadAction<FetchAccountPayload>) => {
      state.loading = true;
      state.error = null;
    },
    fetchAccountSuccess: (state, action: PayloadAction<Account>) => {
      state.loading = false;
      state.currentAccount = action.payload;
      state.error = null;
    },
    fetchAccountFailure: (state, action: PayloadAction<string>) => {
      state.loading = false;
      state.error = action.payload;
    },

    // Fetch Accounts List
    fetchAccountsRequest: (state, action: PayloadAction<FetchAccountsPayload>) => {
      state.loading = true;
      state.error = null;
    },
    fetchAccountsSuccess: (state, action: PayloadAction<Account[]>) => {
      state.loading = false;
      state.accounts = action.payload;
      state.error = null;
    },
    fetchAccountsFailure: (state, action: PayloadAction<string>) => {
      state.loading = false;
      state.error = action.payload;
    },

    // Create Account
    createAccountRequest: (state, action: PayloadAction<CreateAccountPayload>) => {
      state.loading = true;
      state.error = null;
      state.success = false;
    },
    createAccountSuccess: (state, action: PayloadAction<Account>) => {
      state.loading = false;
      state.accounts.push(action.payload);
      state.success = true;
      state.error = null;
    },
    createAccountFailure: (state, action: PayloadAction<string>) => {
      state.loading = false;
      state.error = action.payload;
      state.success = false;
    },

    // Update Account
    updateAccountRequest: (state, action: PayloadAction<UpdateAccountPayload>) => {
      state.loading = true;
      state.error = null;
      state.success = false;
    },
    updateAccountSuccess: (state, action: PayloadAction<Account>) => {
      state.loading = false;
      const index = state.accounts.findIndex(acc => acc.id === action.payload.id);
      if (index !== -1) {
        state.accounts[index] = action.payload;
      }
      if (state.currentAccount?.id === action.payload.id) {
        state.currentAccount = action.payload;
      }
      state.success = true;
      state.error = null;
    },
    updateAccountFailure: (state, action: PayloadAction<string>) => {
      state.loading = false;
      state.error = action.payload;
      state.success = false;
    },

    // Delete Account
    deleteAccountRequest: (state, action: PayloadAction<DeleteAccountPayload>) => {
      state.loading = true;
      state.error = null;
      state.success = false;
    },
    deleteAccountSuccess: (state, action: PayloadAction<number>) => {
      state.loading = false;
      state.accounts = state.accounts.filter(acc => acc.id !== action.payload);
      if (state.currentAccount?.id === action.payload) {
        state.currentAccount = null;
      }
      state.success = true;
      state.error = null;
    },
    deleteAccountFailure: (state, action: PayloadAction<string>) => {
      state.loading = false;
      state.error = action.payload;
      state.success = false;
    },

    // Reset State
    resetAccountState: (state) => {
      state.error = null;
      state.success = false;
      state.loading = false;
    },
  },
});

export const {
  fetchAccountRequest,
  fetchAccountSuccess,
  fetchAccountFailure,
  fetchAccountsRequest,
  fetchAccountsSuccess,
  fetchAccountsFailure,
  createAccountRequest,
  createAccountSuccess,
  createAccountFailure,
  updateAccountRequest,
  updateAccountSuccess,
  updateAccountFailure,
  deleteAccountRequest,
  deleteAccountSuccess,
  deleteAccountFailure,
  resetAccountState,
} = accountSlice.actions;

export default accountSlice.reducer;

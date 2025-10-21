import { RootState } from '../store';

// Account Selectors
export const selectAccountState = (state: RootState) => state.account;

export const selectAccounts = (state: RootState) => state.account.accounts;

export const selectCurrentAccount = (state: RootState) => state.account.currentAccount;

export const selectAccountLoading = (state: RootState) => state.account.loading;

export const selectAccountError = (state: RootState) => state.account.error;

export const selectAccountSuccess = (state: RootState) => state.account.success;

// Get account by ID
export const selectAccountById = (id: number) => (state: RootState) =>
  state.account.accounts.find(account => account.id === id);

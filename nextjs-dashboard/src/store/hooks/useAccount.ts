/**
 * Custom Hook for Account Operations
 * Simplifies account management by combining dispatch and selectors
 */

import { useCallback } from 'react';
import { useAppDispatch, useAppSelector } from '../hooks';
import {
  fetchAccountRequest,
  fetchAccountsRequest,
  createAccountRequest,
  updateAccountRequest,
  deleteAccountRequest,
  resetAccountState,
} from '../slices/accountSlice';
import {
  selectAccounts,
  selectCurrentAccount,
  selectAccountLoading,
  selectAccountError,
  selectAccountSuccess,
  selectAccountById,
} from '../selectors/accountSelectors';
import type {
  FetchAccountPayload,
  FetchAccountsPayload,
  CreateAccountPayload,
  UpdateAccountPayload,
  DeleteAccountPayload,
  Account,
} from '../types/accountTypes';

/**
 * Custom hook for account management
 * @returns Object with account state and action dispatchers
 */
export const useAccount = () => {
  const dispatch = useAppDispatch();

  // Selectors
  const accounts = useAppSelector(selectAccounts);
  const currentAccount = useAppSelector(selectCurrentAccount);
  const loading = useAppSelector(selectAccountLoading);
  const error = useAppSelector(selectAccountError);
  const success = useAppSelector(selectAccountSuccess);

  // Actions
  const fetchAccount = useCallback(
    (payload: FetchAccountPayload) => {
      dispatch(fetchAccountRequest(payload));
    },
    [dispatch]
  );

  const fetchAccounts = useCallback(
    (payload: FetchAccountsPayload = {}) => {
      dispatch(fetchAccountsRequest(payload));
    },
    [dispatch]
  );

  const createAccount = useCallback(
    (payload: CreateAccountPayload) => {
      dispatch(createAccountRequest(payload));
    },
    [dispatch]
  );

  const updateAccount = useCallback(
    (payload: UpdateAccountPayload) => {
      dispatch(updateAccountRequest(payload));
    },
    [dispatch]
  );

  const deleteAccount = useCallback(
    (payload: DeleteAccountPayload) => {
      dispatch(deleteAccountRequest(payload));
    },
    [dispatch]
  );

  const resetState = useCallback(() => {
    dispatch(resetAccountState());
  }, [dispatch]);

  const getAccountById = useCallback(
    (id: number) => {
      return accounts.find((account: Account) => account.id === id);
    },
    [accounts]
  );

  return {
    // State
    accounts,
    currentAccount,
    loading,
    error,
    success,
    
    // Actions
    fetchAccount,
    fetchAccounts,
    createAccount,
    updateAccount,
    deleteAccount,
    resetState,
    
    // Helpers
    getAccountById,
  };
};

/**
 * Example usage:
 * 
 * const {
 *   accounts,
 *   loading,
 *   error,
 *   fetchAccounts,
 *   createAccount,
 *   updateAccount,
 *   deleteAccount,
 * } = useAccount();
 * 
 * // Fetch all accounts
 * useEffect(() => {
 *   fetchAccounts({ page: 1, limit: 10 });
 * }, []);
 * 
 * // Create account
 * const handleCreate = () => {
 *   createAccount({
 *     email: 'test@example.com',
 *     user_name: 'testuser',
 *     // ... other fields
 *   });
 * };
 */

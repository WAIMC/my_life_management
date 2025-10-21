/**
 * Account API Services - Using Common API Client
 */

import { apiGet, apiPost, apiPut, apiDelete } from '@/common/api/client';
import { apiPaths } from '@/common/api/paths';
import type { ApiResponse } from '@/common/types/api.types';
import type { Account } from '@/store/types/accountTypes';

/**
 * Fetch all accounts
 */
export const fetchAccountsApi = async (params?: {
  page?: number;
  limit?: number;
  search?: string;
}): Promise<ApiResponse<Account[]>> => {
  return apiGet<Account[]>(apiPaths.accounts.list(params));
};

/**
 * Fetch single account by ID
 */
export const fetchAccountApi = async (id: number): Promise<ApiResponse<Account>> => {
  return apiGet<Account>(apiPaths.accounts.detail(id));
};

/**
 * Create new account
 */
export const createAccountApi = async (data: Partial<Account>): Promise<ApiResponse<Account>> => {
  return apiPost<Account>(apiPaths.accounts.create(), data);
};

/**
 * Update account
 */
export const updateAccountApi = async (id: number, data: Partial<Account>): Promise<ApiResponse<Account>> => {
  return apiPut<Account>(apiPaths.accounts.update(id), data);
};

/**
 * Delete account
 */
export const deleteAccountApi = async (id: number): Promise<ApiResponse<null>> => {
  return apiDelete<null>(apiPaths.accounts.delete(id));
};

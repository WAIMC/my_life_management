/**
 * Account Types
 */

export interface Account {
  id?: number;
  email: string;
  user_name: string;
  password?: string;
  first_name: string;
  last_name: string;
  address: string;
  phone_number: string;
  birth: string;
  gender: string;
  status: string;
  is_active: boolean;
  avatar?: string;
  email_verified_at?: string | null;
  is_delete: boolean;
  limit_access?: number;
  created_at?: string;
  updated_at?: string;
}

export interface AccountState {
  accounts: Account[];
  currentAccount: Account | null;
  loading: boolean;
  error: string | null;
  success: boolean;
}

export interface FetchAccountPayload {
  id: number;
}

export interface FetchAccountsPayload {
  page?: number;
  limit?: number;
  search?: string;
}

export interface CreateAccountPayload {
  email: string;
  user_name: string;
  password: string;
  first_name: string;
  last_name: string;
  address: string;
  phone_number: string;
  birth: string;
  gender: string;
  status: string;
  is_active: boolean;
  avatar?: string;
  limit_access?: number;
}

export interface UpdateAccountPayload {
  id: number;
  data: Partial<Account>;
}

export interface DeleteAccountPayload {
  id: number;
}

'use client';

import { useEffect } from 'react';
import { useAppDispatch, useAppSelector } from '@/store/hooks';
import {
  fetchAccountsRequest,
  createAccountRequest,
  updateAccountRequest,
  deleteAccountRequest,
  resetAccountState,
} from '@/store/slices/accountSlice';
import {
  selectAccounts,
  selectAccountLoading,
  selectAccountError,
  selectAccountSuccess,
} from '@/store/selectors/accountSelectors';
import type { CreateAccountPayload, UpdateAccountPayload } from '@/store/types/accountTypes';

/**
 * Sample Component demonstrating Account Redux + Saga usage
 */
export default function AccountSamplePage() {
  const dispatch = useAppDispatch();
  
  // Selectors
  const accounts = useAppSelector(selectAccounts);
  const loading = useAppSelector(selectAccountLoading);
  const error = useAppSelector(selectAccountError);
  const success = useAppSelector(selectAccountSuccess);

  // Fetch accounts on component mount
  useEffect(() => {
    dispatch(fetchAccountsRequest({ page: 1, limit: 10 }));
  }, [dispatch]);

  // Reset state on unmount
  useEffect(() => {
    return () => {
      dispatch(resetAccountState());
    };
  }, [dispatch]);

  // Handle Create Account
  const handleCreateAccount = () => {
    const newAccount: CreateAccountPayload = {
      email: 'john.doe@example.com',
      user_name: 'johndoe',
      password: 'SecurePass123!',
      first_name: 'John',
      last_name: 'Doe',
      address: '123 Main Street, City',
      phone_number: '+1234567890',
      birth: '1990-01-15',
      gender: 'male',
      status: 'active',
      is_active: true,
      avatar: 'https://example.com/avatar.jpg',
      limit_access: 5,
    };

    dispatch(createAccountRequest(newAccount));
  };

  // Handle Update Account
  const handleUpdateAccount = (id: number) => {
    const updateData: UpdateAccountPayload = {
      id,
      data: {
        first_name: 'Jane',
        last_name: 'Smith',
        phone_number: '+9876543210',
      },
    };

    dispatch(updateAccountRequest(updateData));
  };

  // Handle Delete Account
  const handleDeleteAccount = (id: number) => {
    if (confirm('Are you sure you want to delete this account?')) {
      dispatch(deleteAccountRequest({ id }));
    }
  };

  return (
    <div className="container mx-auto p-6">
      <h1 className="text-3xl font-bold mb-6">Account Management - Redux Saga Sample</h1>

      {/* Action Buttons */}
      <div className="mb-6 space-x-4">
        <button
          onClick={handleCreateAccount}
          className="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
          disabled={loading}
        >
          Create Sample Account
        </button>
        <button
          onClick={() => dispatch(fetchAccountsRequest({}))}
          className="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600"
          disabled={loading}
        >
          Refresh Accounts
        </button>
      </div>

      {/* Status Messages */}
      {loading && (
        <div className="mb-4 p-4 bg-blue-100 text-blue-700 rounded">
          Loading...
        </div>
      )}

      {error && (
        <div className="mb-4 p-4 bg-red-100 text-red-700 rounded">
          Error: {error}
        </div>
      )}

      {success && (
        <div className="mb-4 p-4 bg-green-100 text-green-700 rounded">
          Operation completed successfully!
        </div>
      )}

      {/* Accounts Table */}
      <div className="overflow-x-auto">
        <table className="min-w-full bg-white border border-gray-300">
          <thead className="bg-gray-100">
            <tr>
              <th className="px-4 py-2 border">ID</th>
              <th className="px-4 py-2 border">Username</th>
              <th className="px-4 py-2 border">Email</th>
              <th className="px-4 py-2 border">Name</th>
              <th className="px-4 py-2 border">Phone</th>
              <th className="px-4 py-2 border">Status</th>
              <th className="px-4 py-2 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            {accounts.length === 0 ? (
              <tr>
                <td colSpan={7} className="px-4 py-8 text-center text-gray-500">
                  No accounts found. Click "Create Sample Account" to add one.
                </td>
              </tr>
            ) : (
              accounts.map((account) => (
                <tr key={account.id} className="hover:bg-gray-50">
                  <td className="px-4 py-2 border">{account.id}</td>
                  <td className="px-4 py-2 border">{account.user_name}</td>
                  <td className="px-4 py-2 border">{account.email}</td>
                  <td className="px-4 py-2 border">
                    {account.first_name} {account.last_name}
                  </td>
                  <td className="px-4 py-2 border">{account.phone_number}</td>
                  <td className="px-4 py-2 border">
                    <span
                      className={`px-2 py-1 rounded text-xs ${
                        account.is_active
                          ? 'bg-green-100 text-green-800'
                          : 'bg-red-100 text-red-800'
                      }`}
                    >
                      {account.is_active ? 'Active' : 'Inactive'}
                    </span>
                  </td>
                  <td className="px-4 py-2 border space-x-2">
                    <button
                      onClick={() => account.id && handleUpdateAccount(account.id)}
                      className="px-3 py-1 bg-yellow-500 text-white rounded text-sm hover:bg-yellow-600"
                      disabled={loading}
                    >
                      Edit
                    </button>
                    <button
                      onClick={() => account.id && handleDeleteAccount(account.id)}
                      className="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600"
                      disabled={loading}
                    >
                      Delete
                    </button>
                  </td>
                </tr>
              ))
            )}
          </tbody>
        </table>
      </div>

      {/* Account Details JSON View */}
      <div className="mt-8">
        <h2 className="text-xl font-semibold mb-4">State Debug (JSON)</h2>
        <pre className="bg-gray-100 p-4 rounded overflow-auto max-h-96">
          {JSON.stringify({ accounts, loading, error, success }, null, 2)}
        </pre>
      </div>
    </div>
  );
}

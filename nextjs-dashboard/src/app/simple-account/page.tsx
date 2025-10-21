'use client';

import { useEffect } from 'react';
import { useAccount } from '@/store/hooks/useAccount';

/**
 * Simple Account Component using the custom useAccount hook
 */
export default function SimpleAccountExample() {
  const {
    accounts,
    loading,
    error,
    success,
    fetchAccounts,
    createAccount,
    updateAccount,
    deleteAccount,
    resetState,
  } = useAccount();

  // Fetch accounts on mount
  useEffect(() => {
    fetchAccounts({ page: 1, limit: 10 });
  }, [fetchAccounts]);

  // Reset state on unmount
  useEffect(() => {
    return () => {
      resetState();
    };
  }, [resetState]);

  const handleCreateSample = () => {
    createAccount({
      email: 'sample@example.com',
      user_name: 'sampleuser',
      password: 'Sample123!',
      first_name: 'Sample',
      last_name: 'User',
      address: '456 Sample Ave',
      phone_number: '+1122334455',
      birth: '1995-05-20',
      gender: 'female',
      status: 'active',
      is_active: true,
    });
  };

  return (
    <div className="p-6">
      <h1 className="text-2xl font-bold mb-4">Simple Account Example (useAccount Hook)</h1>
      
      <div className="mb-4">
        <button
          onClick={handleCreateSample}
          className="px-4 py-2 bg-blue-500 text-white rounded mr-2"
          disabled={loading}
        >
          Create Sample
        </button>
        <button
          onClick={() => fetchAccounts({})}
          className="px-4 py-2 bg-green-500 text-white rounded"
          disabled={loading}
        >
          Refresh
        </button>
      </div>

      {loading && <p className="text-blue-600">Loading...</p>}
      {error && <p className="text-red-600">Error: {error}</p>}
      {success && <p className="text-green-600">Success!</p>}

      <div className="mt-4">
        <h2 className="text-xl font-semibold mb-2">Accounts ({accounts.length})</h2>
        <ul className="space-y-2">
          {accounts.map((account) => (
            <li key={account.id} className="p-3 border rounded">
              <div className="font-semibold">{account.user_name}</div>
              <div className="text-sm text-gray-600">{account.email}</div>
              <div className="text-sm">
                {account.first_name} {account.last_name}
              </div>
            </li>
          ))}
        </ul>
      </div>
    </div>
  );
}

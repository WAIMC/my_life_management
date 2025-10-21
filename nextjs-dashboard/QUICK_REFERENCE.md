# Account Module - Quick Reference Cheat Sheet

## 🚀 Quick Start

```typescript
import { useAccount } from '@/store/hooks/useAccount';

const { accounts, loading, error, fetchAccounts, createAccount } = useAccount();
```

## 📋 Actions Quick Reference

### Fetch All Accounts
```typescript
fetchAccounts({ page: 1, limit: 10, search: 'john' })
```

### Fetch Single Account
```typescript
fetchAccount({ id: 1 })
```

### Create Account
```typescript
createAccount({
  email: 'user@example.com',
  user_name: 'username',
  password: 'password123',
  first_name: 'John',
  last_name: 'Doe',
  address: '123 Main St',
  phone_number: '+1234567890',
  birth: '1990-01-15',
  gender: 'male',
  status: 'active',
  is_active: true,
})
```

### Update Account
```typescript
updateAccount({
  id: 1,
  data: {
    first_name: 'Jane',
    phone_number: '+9876543210',
  }
})
```

### Delete Account
```typescript
deleteAccount({ id: 1 })
```

### Reset State
```typescript
resetState()
```

## 🎯 State Properties

```typescript
{
  accounts: Account[]        // Array of accounts
  currentAccount: Account    // Current account (from fetchAccount)
  loading: boolean           // Loading state
  error: string | null       // Error message
  success: boolean           // Success flag
}
```

## 📦 Account Model

```typescript
{
  id?: number
  email: string              // Required
  user_name: string          // Required
  password?: string          // Required for create, optional in response
  first_name: string         // Required
  last_name: string          // Required
  address: string            // Required
  phone_number: string       // Required
  birth: string              // Required (YYYY-MM-DD)
  gender: string             // Required
  status: string             // Required
  is_active: boolean         // Required
  avatar?: string            // Optional
  email_verified_at?: string // Optional
  is_delete: boolean         // Required
  limit_access?: number      // Optional
  created_at?: string        // Auto-generated
  updated_at?: string        // Auto-generated
}
```

## 🔧 Import Patterns

### Pattern 1: Custom Hook (Recommended ✅)
```typescript
import { useAccount } from '@/store/hooks/useAccount';

const {
  accounts,
  loading,
  error,
  fetchAccounts,
  createAccount,
  updateAccount,
  deleteAccount,
} = useAccount();
```

### Pattern 2: Direct Imports
```typescript
import { useAppDispatch, useAppSelector } from '@/store/hooks';
import {
  fetchAccountsRequest,
  createAccountRequest,
} from '@/store/slices/accountSlice';
import {
  selectAccounts,
  selectAccountLoading,
} from '@/store/selectors/accountSelectors';

const dispatch = useAppDispatch();
const accounts = useAppSelector(selectAccounts);
```

### Pattern 3: Module Exports
```typescript
import {
  fetchAccountsRequest,
  selectAccounts,
  type Account,
  type CreateAccountPayload,
} from '@/store/account.module';
```

## 🎨 Component Examples

### Basic List
```typescript
'use client';

import { useEffect } from 'react';
import { useAccount } from '@/store/hooks/useAccount';

export default function AccountList() {
  const { accounts, loading, fetchAccounts } = useAccount();

  useEffect(() => {
    fetchAccounts({});
  }, [fetchAccounts]);

  if (loading) return <div>Loading...</div>;

  return (
    <ul>
      {accounts.map(account => (
        <li key={account.id}>{account.user_name}</li>
      ))}
    </ul>
  );
}
```

### Create Form
```typescript
const { createAccount, loading, success } = useAccount();

const handleSubmit = (formData) => {
  createAccount({
    email: formData.email,
    user_name: formData.username,
    password: formData.password,
    first_name: formData.firstName,
    last_name: formData.lastName,
    address: formData.address,
    phone_number: formData.phone,
    birth: formData.birth,
    gender: formData.gender,
    status: 'active',
    is_active: true,
  });
};

useEffect(() => {
  if (success) {
    // Redirect or show success message
    router.push('/accounts');
  }
}, [success]);
```

### Edit Form
```typescript
const { currentAccount, updateAccount, fetchAccount } = useAccount();

useEffect(() => {
  fetchAccount({ id: accountId });
}, [accountId]);

const handleUpdate = (formData) => {
  updateAccount({
    id: accountId,
    data: {
      first_name: formData.firstName,
      last_name: formData.lastName,
      phone_number: formData.phone,
    }
  });
};
```

### Delete with Confirmation
```typescript
const { deleteAccount, loading } = useAccount();

const handleDelete = (id: number) => {
  if (confirm('Are you sure?')) {
    deleteAccount({ id });
  }
};
```

## 🔍 Selectors Reference

```typescript
selectAccounts(state)          // Get all accounts
selectCurrentAccount(state)    // Get current account
selectAccountLoading(state)    // Get loading state
selectAccountError(state)      // Get error message
selectAccountSuccess(state)    // Get success flag
selectAccountById(id)(state)   // Get account by ID
```

## ⚡ Common Patterns

### Fetch on Mount
```typescript
useEffect(() => {
  fetchAccounts({});
}, [fetchAccounts]);
```

### Reset on Unmount
```typescript
useEffect(() => {
  return () => resetState();
}, [resetState]);
```

### Handle Success
```typescript
useEffect(() => {
  if (success) {
    alert('Operation successful!');
    resetState();
  }
}, [success]);
```

### Handle Error
```typescript
useEffect(() => {
  if (error) {
    alert(`Error: ${error}`);
  }
}, [error]);
```

### Pagination
```typescript
const [page, setPage] = useState(1);

useEffect(() => {
  fetchAccounts({ page, limit: 10 });
}, [page, fetchAccounts]);
```

### Search
```typescript
const [search, setSearch] = useState('');

useEffect(() => {
  const timer = setTimeout(() => {
    fetchAccounts({ search });
  }, 500);
  return () => clearTimeout(timer);
}, [search, fetchAccounts]);
```

## 🌐 API Configuration

### .env.local
```bash
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

### Custom API URL
```typescript
// In accountSaga.ts
const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
```

## 🐛 Troubleshooting

### Issue: Action not triggering
```typescript
// ❌ Wrong
dispatch(fetchAccountsRequest)

// ✅ Correct
dispatch(fetchAccountsRequest({}))
```

### Issue: State not updating
```typescript
// Make sure Provider wraps your app
// In layout.tsx:
<Providers>
  {children}
</Providers>
```

### Issue: TypeScript errors
```typescript
// Make sure to import types
import type { Account } from '@/store/types/accountTypes';
```

## 📱 Test URLs

- Full Example: `http://localhost:3000/sample-account`
- Simple Example: `http://localhost:3000/simple-account`

## 🎯 Best Practices

1. ✅ Use `useAccount()` hook for simplicity
2. ✅ Reset state on component unmount
3. ✅ Handle loading, error, and success states
4. ✅ Use TypeScript types for type safety
5. ✅ Debounce search inputs
6. ✅ Show confirmation for delete operations
7. ✅ Clear sensitive data (password) after operations

## 📚 Files to Know

- **Hook**: `src/store/hooks/useAccount.ts`
- **Types**: `src/store/types/accountTypes.ts`
- **Actions**: `src/store/slices/accountSlice.ts`
- **Saga**: `src/store/sagas/accountSaga.ts`
- **Selectors**: `src/store/selectors/accountSelectors.ts`

---

**Need more help?** Check `ACCOUNT_MODULE_GUIDE.md` for detailed documentation.

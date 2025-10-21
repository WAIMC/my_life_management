# Account Module - Redux + Saga Documentation

## Overview
This module provides a complete Redux + Saga implementation for account management with full CRUD operations.

## Structure

```
src/store/
├── constants/
│   └── accountConstants.ts    # Action type constants
├── types/
│   └── accountTypes.ts        # TypeScript interfaces and types
├── slices/
│   └── accountSlice.ts        # Redux slice with reducers
├── sagas/
│   └── accountSaga.ts         # Redux Saga for async operations
├── selectors/
│   └── accountSelectors.ts    # Reusable selectors
└── hooks.ts                   # Typed hooks (useAppDispatch, useAppSelector)
```

## Account Model

```typescript
interface Account {
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
```

## Usage Examples

### 1. Import Required Items

```typescript
import { useAppDispatch, useAppSelector } from '@/store/hooks';
import {
  fetchAccountsRequest,
  fetchAccountRequest,
  createAccountRequest,
  updateAccountRequest,
  deleteAccountRequest,
  resetAccountState,
} from '@/store/slices/accountSlice';
import {
  selectAccounts,
  selectCurrentAccount,
  selectAccountLoading,
  selectAccountError,
} from '@/store/selectors/accountSelectors';
```

### 2. Fetch All Accounts

```typescript
const dispatch = useAppDispatch();
const accounts = useAppSelector(selectAccounts);
const loading = useAppSelector(selectAccountLoading);

// Fetch all accounts with pagination
dispatch(fetchAccountsRequest({ page: 1, limit: 10 }));

// Fetch with search
dispatch(fetchAccountsRequest({ search: 'john' }));
```

### 3. Fetch Single Account

```typescript
const currentAccount = useAppSelector(selectCurrentAccount);

dispatch(fetchAccountRequest({ id: 1 }));
```

### 4. Create Account

```typescript
const newAccount = {
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
```

### 5. Update Account

```typescript
dispatch(updateAccountRequest({
  id: 1,
  data: {
    first_name: 'Jane',
    last_name: 'Smith',
    phone_number: '+9876543210',
  }
}));
```

### 6. Delete Account

```typescript
dispatch(deleteAccountRequest({ id: 1 }));
```

### 7. Reset State

```typescript
// Clear error and success messages
dispatch(resetAccountState());
```

## Available Actions

| Action | Payload | Description |
|--------|---------|-------------|
| `fetchAccountRequest` | `{ id: number }` | Fetch single account by ID |
| `fetchAccountsRequest` | `{ page?, limit?, search? }` | Fetch list of accounts |
| `createAccountRequest` | `CreateAccountPayload` | Create new account |
| `updateAccountRequest` | `{ id, data }` | Update existing account |
| `deleteAccountRequest` | `{ id: number }` | Delete account |
| `resetAccountState` | - | Reset error/success states |

## Available Selectors

| Selector | Returns | Description |
|----------|---------|-------------|
| `selectAccounts` | `Account[]` | All accounts |
| `selectCurrentAccount` | `Account \| null` | Currently selected account |
| `selectAccountLoading` | `boolean` | Loading state |
| `selectAccountError` | `string \| null` | Error message |
| `selectAccountSuccess` | `boolean` | Success state |
| `selectAccountById(id)` | `Account \| undefined` | Find account by ID |

## Complete Component Example

```typescript
'use client';

import { useEffect } from 'react';
import { useAppDispatch, useAppSelector } from '@/store/hooks';
import {
  fetchAccountsRequest,
  createAccountRequest,
  updateAccountRequest,
  deleteAccountRequest,
} from '@/store/slices/accountSlice';
import {
  selectAccounts,
  selectAccountLoading,
  selectAccountError,
} from '@/store/selectors/accountSelectors';

export default function AccountPage() {
  const dispatch = useAppDispatch();
  const accounts = useAppSelector(selectAccounts);
  const loading = useAppSelector(selectAccountLoading);
  const error = useAppSelector(selectAccountError);

  useEffect(() => {
    dispatch(fetchAccountsRequest({}));
  }, [dispatch]);

  const handleCreate = () => {
    dispatch(createAccountRequest({
      email: 'test@example.com',
      user_name: 'testuser',
      password: 'password123',
      first_name: 'Test',
      last_name: 'User',
      address: '123 Test St',
      phone_number: '1234567890',
      birth: '1990-01-01',
      gender: 'male',
      status: 'active',
      is_active: true,
    }));
  };

  if (loading) return <div>Loading...</div>;
  if (error) return <div>Error: {error}</div>;

  return (
    <div>
      <button onClick={handleCreate}>Create Account</button>
      <ul>
        {accounts.map(account => (
          <li key={account.id}>{account.user_name} - {account.email}</li>
        ))}
      </ul>
    </div>
  );
}
```

## API Configuration

Update the API base URL in `src/store/sagas/accountSaga.ts`:

```typescript
const API_BASE_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
```

Set the environment variable in `.env.local`:

```
NEXT_PUBLIC_API_URL=http://your-api-domain.com/api
```

## Expected API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/accounts` | Fetch all accounts |
| GET | `/api/accounts/:id` | Fetch single account |
| POST | `/api/accounts` | Create account |
| PUT | `/api/accounts/:id` | Update account |
| DELETE | `/api/accounts/:id` | Delete account |

## Sample Page

A complete sample implementation is available at:
- File: `src/app/sample-account/page.tsx`
- URL: `http://localhost:3000/sample-account`

## Error Handling

The saga automatically catches errors and dispatches failure actions with error messages:

```typescript
try {
  // API call
  const response = yield call(accountApi.fetchAccounts);
  yield put(fetchAccountsSuccess(response.data));
} catch (error) {
  const errorMessage = error.response?.data?.message || 'Failed to fetch accounts';
  yield put(fetchAccountsFailure(errorMessage));
}
```

## State Structure

```typescript
{
  account: {
    accounts: Account[],      // List of all accounts
    currentAccount: Account | null,  // Currently selected account
    loading: boolean,         // Loading state
    error: string | null,     // Error message
    success: boolean,         // Success state for operations
  }
}
```

## Testing

Access the sample page to test the implementation:

```bash
npm run dev
# Visit http://localhost:3000/sample-account
```

## Notes

- All saga operations use `takeLatest` to cancel previous pending requests
- The state automatically updates on successful operations
- Errors are captured and stored in the state
- Use `resetAccountState()` to clear error/success messages
- Password field is optional in responses for security

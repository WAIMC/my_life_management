# Redux + Saga Account Module - File Summary

## ✅ Files Created

### 📁 Core Files

1. **Types & Interfaces**
   - `src/store/types/accountTypes.ts` - TypeScript interfaces for Account model and payloads

2. **Constants**
   - `src/store/constants/accountConstants.ts` - Action type constants

3. **Redux Slice (Reducer)**
   - `src/store/slices/accountSlice.ts` - Redux Toolkit slice with all reducers and actions

4. **Redux Saga**
   - `src/store/sagas/accountSaga.ts` - Saga for handling async API calls

5. **Selectors**
   - `src/store/selectors/accountSelectors.ts` - Reusable selectors for state access

6. **Custom Hook**
   - `src/store/hooks/useAccount.ts` - Custom hook combining dispatch and selectors

7. **Module Exports**
   - `src/store/account.module.ts` - Centralized exports for easy importing

### 📁 Updated Files

8. **Root Reducer**
   - `src/store/rootReducer.ts` - Added account reducer

9. **Root Saga**
   - `src/store/rootSaga.ts` - Added account saga

### 📁 Example Pages

10. **Full Example**
    - `src/app/sample-account/page.tsx` - Complete implementation with table and CRUD

11. **Simple Example**
    - `src/app/simple-account/page.tsx` - Simple example using custom hook

### 📁 Documentation

12. **Guide**
    - `ACCOUNT_MODULE_GUIDE.md` - Complete documentation and usage guide

## 🚀 Quick Start

### 1. Test the Implementation

```bash
# Start the dev server
npm run dev

# Visit the sample pages:
# http://localhost:3000/sample-account
# http://localhost:3000/simple-account
```

### 2. Configure API URL

Create or update `.env.local`:

```env
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

### 3. Use in Your Components

#### Option 1: Using Custom Hook (Recommended)

```typescript
import { useAccount } from '@/store/hooks/useAccount';

export default function MyComponent() {
  const {
    accounts,
    loading,
    error,
    fetchAccounts,
    createAccount,
  } = useAccount();

  useEffect(() => {
    fetchAccounts({});
  }, [fetchAccounts]);

  return <div>...</div>;
}
```

#### Option 2: Using Direct Imports

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

export default function MyComponent() {
  const dispatch = useAppDispatch();
  const accounts = useAppSelector(selectAccounts);
  const loading = useAppSelector(selectAccountLoading);

  useEffect(() => {
    dispatch(fetchAccountsRequest({}));
  }, [dispatch]);

  return <div>...</div>;
}
```

#### Option 3: Using Module Exports

```typescript
import {
  fetchAccountsRequest,
  selectAccounts,
  type Account,
} from '@/store/account.module';
```

## 📋 Account Parameters

```typescript
{
  email: string;
  user_name: string;
  password: string;
  first_name: string;
  last_name: string;
  address: string;
  phone_number: string;
  birth: string;              // Format: YYYY-MM-DD
  gender: string;
  status: string;
  is_active: boolean;
  avatar?: string;
  email_verified_at?: string | null;
  is_delete: boolean;
  limit_access?: number;
}
```

## 🔧 Available Actions

- `fetchAccountRequest(payload)` - Fetch single account
- `fetchAccountsRequest(payload)` - Fetch multiple accounts
- `createAccountRequest(payload)` - Create new account
- `updateAccountRequest(payload)` - Update existing account
- `deleteAccountRequest(payload)` - Delete account
- `resetAccountState()` - Reset error/success states

## 📊 Available Selectors

- `selectAccounts` - Get all accounts
- `selectCurrentAccount` - Get current account
- `selectAccountLoading` - Get loading state
- `selectAccountError` - Get error message
- `selectAccountSuccess` - Get success state
- `selectAccountById(id)` - Find account by ID

## 🎯 Features

✅ Full CRUD operations (Create, Read, Update, Delete)
✅ TypeScript support with complete type definitions
✅ Error handling with error messages
✅ Loading states
✅ Success states
✅ Pagination support
✅ Search functionality
✅ Custom hook for easy usage
✅ Centralized exports
✅ Sample pages for testing
✅ Complete documentation

## 📖 Documentation

See `ACCOUNT_MODULE_GUIDE.md` for detailed documentation and examples.

## 🔗 API Integration

The saga expects the following API endpoints:

- `GET /api/accounts` - List accounts
- `GET /api/accounts/:id` - Get single account
- `POST /api/accounts` - Create account
- `PUT /api/accounts/:id` - Update account
- `DELETE /api/accounts/:id` - Delete account

Update the API base URL in `src/store/sagas/accountSaga.ts` if needed.

## ✨ Next Steps

1. Configure your API URL in `.env.local`
2. Test the sample pages
3. Integrate into your components
4. Customize as needed
5. Add authentication tokens if required
6. Add more actions/reducers as needed

Happy coding! 🎉

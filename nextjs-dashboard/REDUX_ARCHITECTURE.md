# Redux + Saga Architecture - Account Module

## 🏗️ Data Flow Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                         REACT COMPONENT                              │
│  ┌────────────────────────────────────────────────────────────┐    │
│  │  useAccount() or useAppDispatch() + useAppSelector()       │    │
│  └────────────────────────────────────────────────────────────┘    │
└───────────────────────┬─────────────────────────┬───────────────────┘
                        │                         │
                        │ Dispatch Action         │ Select State
                        ▼                         ▼
┌───────────────────────────────────┐  ┌──────────────────────────────┐
│         REDUX STORE               │  │      SELECTORS               │
│  ┌─────────────────────────────┐  │  │  selectAccounts()            │
│  │   Account State             │  │  │  selectAccountLoading()      │
│  │  {                          │  │  │  selectAccountError()        │
│  │    accounts: [],            │◄─┼──┤  selectCurrentAccount()      │
│  │    currentAccount: null,    │  │  │  selectAccountSuccess()      │
│  │    loading: false,          │  │  └──────────────────────────────┘
│  │    error: null,             │  │
│  │    success: false           │  │
│  │  }                          │  │
│  └─────────────────────────────┘  │
└───────────────────────────────────┘
         │                    ▲
         │ Action             │ Action
         ▼                    │
┌───────────────────────────────────────────────────────────────────┐
│                        REDUX SAGA MIDDLEWARE                       │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │  accountSaga()                                            │    │
│  │   ├─ watchFetchAccount()   → handleFetchAccount()        │    │
│  │   ├─ watchFetchAccounts()  → handleFetchAccounts()       │    │
│  │   ├─ watchCreateAccount()  → handleCreateAccount()       │    │
│  │   ├─ watchUpdateAccount()  → handleUpdateAccount()       │    │
│  │   └─ watchDeleteAccount()  → handleDeleteAccount()       │    │
│  └──────────────────────────────────────────────────────────┘    │
└───────────────────────────┬───────────────────────────────────────┘
                            │
                            │ API Call
                            ▼
┌───────────────────────────────────────────────────────────────────┐
│                         API BACKEND                                │
│  ┌──────────────────────────────────────────────────────────┐    │
│  │  GET    /api/accounts         - List accounts            │    │
│  │  GET    /api/accounts/:id     - Get account              │    │
│  │  POST   /api/accounts         - Create account           │    │
│  │  PUT    /api/accounts/:id     - Update account           │    │
│  │  DELETE /api/accounts/:id     - Delete account           │    │
│  └──────────────────────────────────────────────────────────┘    │
└───────────────────────────────────────────────────────────────────┘
```

## 🔄 Action Flow Example

### Creating an Account

```
1. Component calls:
   dispatch(createAccountRequest(newAccountData))

2. Saga watches for action:
   watchCreateAccount() → handleCreateAccount()

3. Saga calls API:
   axios.post('/api/accounts', newAccountData)

4. On Success:
   Saga dispatches: createAccountSuccess(responseData)
   Reducer updates state: accounts.push(newAccount)
   Component re-renders with new data

5. On Error:
   Saga dispatches: createAccountFailure(errorMessage)
   Reducer updates state: error = errorMessage
   Component shows error
```

## 📁 File Structure

```
src/store/
├── constants/
│   └── accountConstants.ts       # Action type constants
│
├── types/
│   └── accountTypes.ts           # TypeScript interfaces
│
├── slices/
│   ├── accountSlice.ts           # Reducer + Actions
│   └── counterSlice.ts           # (existing)
│
├── sagas/
│   └── accountSaga.ts            # Async logic
│
├── selectors/
│   └── accountSelectors.ts       # State selectors
│
├── hooks/
│   └── useAccount.ts             # Custom hook
│
├── account.module.ts             # Centralized exports
├── hooks.ts                      # Typed hooks
├── rootReducer.ts                # Combines all reducers
├── rootSaga.ts                   # Combines all sagas
├── Provider.tsx                  # Redux Provider
└── store.ts                      # Store configuration
```

## 🎯 State Management Flow

```
┌──────────────────┐
│  Initial State   │
│  {               │
│    accounts: []  │
│    loading: false│
│    error: null   │
│  }               │
└────────┬─────────┘
         │
         │ fetchAccountsRequest()
         ▼
┌──────────────────┐
│  Loading State   │
│  {               │
│    accounts: []  │
│    loading: true │ ◄── Reducer sets loading=true
│    error: null   │
│  }               │
└────────┬─────────┘
         │
         │ Saga calls API
         │
    ┌────┴────┐
    │         │
Success    Failure
    │         │
    ▼         ▼
┌─────────┐ ┌─────────┐
│ Success │ │ Failure │
│ State   │ │ State   │
│ {       │ │ {       │
│  accts  │ │  accts  │
│  loading│ │  loading│
│  =false │ │  =false │
│  data=[]│ │  error  │
│ }       │ │  ="..." │
└─────────┘ └─────────┘
```

## 🔑 Key Concepts

### 1. **Actions** (accountSlice.ts)
- Plain objects describing what happened
- Created by action creators (auto-generated by createSlice)
- Example: `fetchAccountsRequest({ page: 1 })`

### 2. **Reducers** (accountSlice.ts)
- Pure functions that update state
- Receive current state + action
- Return new state
- Example: `fetchAccountsSuccess` adds accounts to state

### 3. **Sagas** (accountSaga.ts)
- Handle side effects (API calls)
- Listen for specific actions
- Call APIs and dispatch success/failure actions
- Example: `handleFetchAccounts` calls API and dispatches result

### 4. **Selectors** (accountSelectors.ts)
- Extract specific data from state
- Memoized for performance
- Example: `selectAccounts` returns accounts array

### 5. **Custom Hook** (useAccount.ts)
- Combines dispatch + selectors
- Simplifies component code
- Returns state + action functions

## 📊 Comparison Table

| Approach | Code Lines | Complexity | Recommended |
|----------|-----------|------------|-------------|
| Direct Dispatch + Selectors | ~10 | Medium | ❌ |
| Custom Hook (useAccount) | ~5 | Low | ✅ |
| Module Exports | ~8 | Medium | ✅ |

## 🚀 Usage Comparison

### ❌ Without Custom Hook (More Code)

```typescript
import { useAppDispatch, useAppSelector } from '@/store/hooks';
import { fetchAccountsRequest } from '@/store/slices/accountSlice';
import { selectAccounts, selectAccountLoading } from '@/store/selectors/accountSelectors';

const dispatch = useAppDispatch();
const accounts = useAppSelector(selectAccounts);
const loading = useAppSelector(selectAccountLoading);

useEffect(() => {
  dispatch(fetchAccountsRequest({}));
}, [dispatch]);
```

### ✅ With Custom Hook (Less Code)

```typescript
import { useAccount } from '@/store/hooks/useAccount';

const { accounts, loading, fetchAccounts } = useAccount();

useEffect(() => {
  fetchAccounts({});
}, [fetchAccounts]);
```

## 🎨 Benefits

1. **Type Safety** - Full TypeScript support
2. **Separation of Concerns** - Logic separated from UI
3. **Testability** - Easy to test reducers and sagas
4. **Scalability** - Easy to add new features
5. **Predictability** - State changes are traceable
6. **Developer Experience** - Redux DevTools support
7. **Maintainability** - Clear structure and patterns

## 📚 Learning Resources

- Redux Toolkit: https://redux-toolkit.js.org/
- Redux Saga: https://redux-saga.js.org/
- React Redux: https://react-redux.js.org/

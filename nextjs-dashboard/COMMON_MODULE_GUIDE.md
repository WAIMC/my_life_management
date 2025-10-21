# 📦 Common Module - Complete Setup

## ✅ Đã Setup Thành Công

Đã tạo **Common Module** với đầy đủ các chức năng:

### 📁 Cấu Trúc

```
src/common/
├── api/
│   ├── client.ts          # API client với axios, interceptors
│   ├── paths.ts           # API path helpers
│   ├── auth.api.ts        # Auth API services
│   └── account.api.ts     # Account API services
├── constants/
│   ├── index.ts           # Common constants
│   └── messages.ts        # UI messages
├── types/
│   └── api.types.ts       # API response types
├── utils/
│   ├── token.ts           # Token management
│   └── encode.ts          # Encode/decode utilities
├── validation/
│   └── index.ts           # Validation schemas (Zod)
└── index.ts               # Centralized exports
```

---

## 🎯 Features

### 1. API Client (`api/client.ts`)

✅ **Axios Configuration**
- Base URL configuration
- Request/Response interceptors
- Auto token refresh
- Error handling
- Loading states

✅ **HTTP Methods**
- `apiGet()` - GET requests
- `apiPost()` - POST requests
- `apiPut()` - PUT requests
- `apiPatch()` - PATCH requests
- `apiDelete()` - DELETE requests

✅ **Auth Bearer**
- Auto add Authorization header
- Token refresh on 401
- Clear tokens on logout

✅ **Handle Success/Error**
- `isApiSuccess()` - Check if response is successful
- `getErrorMessages()` - Extract error messages
- `handleApiError()` - Parse and format errors

### 2. Token Management (`utils/token.ts`)

✅ **Token Storage**
- `getAccessToken()` - Get token from localStorage
- `setAccessToken()` - Save token
- `removeAccessToken()` - Remove token
- `clearTokens()` - Clear all tokens

✅ **Token Validation**
- `isAuthenticated()` - Check if user is logged in
- `isTokenExpired()` - Check token expiration
- `decodeToken()` - Decode JWT without verification

### 3. Encode/Decode (`utils/encode.ts`)

✅ **Encoding Functions**
- `encodeBase64()` - Encode to Base64
- `decodeBase64()` - Decode from Base64
- `encodeQueryString()` - Object to query string
- `decodeQueryString()` - Query string to object

✅ **Utilities**
- `sanitizeUrl()` - Clean URL strings
- `deepClone()` - Deep clone objects
- `cleanObject()` - Remove null/undefined values

### 4. Constants (`constants/`)

✅ **API Configuration**
- `API_CONFIG` - Base URL, timeout, retry
- `HTTP_STATUS` - Status codes
- `API_ENDPOINTS` - All API paths
- `STORAGE_KEYS` - LocalStorage keys

✅ **Messages** (`messages.ts`)
- `MESSAGES.SUCCESS` - Success messages
- `MESSAGES.ERROR` - Error messages
- `MESSAGES.VALIDATION` - Validation messages
- `MESSAGES.CONFIRM` - Confirmation messages
- `MESSAGES.LOADING` - Loading messages

### 5. Validation (`validation/`)

✅ **Zod Schemas**
- `AuthSchemas` - Login, register, reset password
- `AccountSchema` - Account validation
- `UserSchema` - User validation

✅ **Validation Functions**
- `validate()` - Validate data against schema
- `safeParse()` - Safe parse without throwing
- `getFieldErrors()` - Extract field-level errors

✅ **Custom Validators**
- `isStrongPassword()`
- `isValidPhone()`
- `isValidEmail()`
- `isValidUrl()`

---

## 🚀 Usage Examples

### Example 1: API Call

```typescript
import { apiPost, isApiSuccess, getErrorMessages } from '@/common';
import { MESSAGES } from '@/common/constants/messages';

const handleSubmit = async (data) => {
  try {
    const response = await apiPost('/accounts', data);
    
    if (isApiSuccess(response)) {
      alert(MESSAGES.SUCCESS.CREATED);
      return response.data;
    } else {
      const errors = getErrorMessages(response);
      alert(errors.join(', '));
    }
  } catch (error) {
    alert(error.message);
  }
};
```

### Example 2: Login with Validation

```typescript
import { loginApi } from '@/common/api/auth.api';
import { AuthSchemas, validate } from '@/common/validation';
import { isApiSuccess } from '@/common';

const handleLogin = async (formData) => {
  // Validate
  const validation = validate(AuthSchemas.login, formData);
  if (!validation.success) {
    alert(validation.errors?.join(', '));
    return;
  }

  // Call API
  try {
    const response = await loginApi(formData);
    
    if (isApiSuccess(response)) {
      // Token is automatically saved
      window.location.href = '/dashboard';
    }
  } catch (error) {
    alert(error.message);
  }
};
```

### Example 3: Using API Paths

```typescript
import { apiGet } from '@/common/api/client';
import { apiPaths, queryParams } from '@/common/api/paths';

// Fetch accounts with pagination
const fetchAccounts = async () => {
  const params = {
    ...queryParams.pagination(1, 10),
    ...queryParams.search('john'),
  };
  
  const response = await apiGet(
    apiPaths.accounts.list(params)
  );
  
  return response.data;
};
```

### Example 4: Token Management

```typescript
import { getAccessToken, isTokenExpired, clearTokens } from '@/common/utils/token';

// Check auth
if (!getAccessToken()) {
  // Redirect to login
}

// Check expiration
const token = getAccessToken();
if (token && isTokenExpired(token)) {
  clearTokens();
  // Redirect to login
}
```

### Example 5: Form Validation

```typescript
import { AccountSchema, validate } from '@/common/validation';

const handleSubmit = (formData) => {
  const result = validate(AccountSchema, formData);
  
  if (!result.success) {
    // Show errors
    result.errors?.forEach(error => console.error(error));
    return;
  }
  
  // Submit valid data
  submitForm(result.data);
};
```

---

## 📋 API Response Format

### Success Response

```typescript
{
  "data": {
    // Your data here
  },
  "error": {
    "status": false,    // false = success
    "code": 200,
    "messages": null
  }
}
```

### Error Response

```typescript
{
  "data": null,
  "error": {
    "status": true,     // true = error
    "code": 400,
    "messages": "Error message" // or ["Error 1", "Error 2"]
  }
}
```

### Auth Response (Login/Refresh)

```typescript
{
  "data": {
    "auth_type": "bearer",
    "ttl": 300,
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
  },
  "error": {
    "status": false,
    "code": 200,
    "messages": null
  }
}
```

---

## 🔧 Configuration

### Environment Variables

Create `.env.local`:

```bash
NEXT_PUBLIC_API_URL=http://localhost:8000/api
```

### Update API Endpoints

Edit `src/common/constants/index.ts`:

```typescript
export const API_ENDPOINTS = {
  AUTH: {
    LOGIN: '/auth/login',
    LOGOUT: '/auth/logout',
    // Add more...
  },
  // Add your endpoints...
};
```

---

## 📚 Integration with Redux Saga

Updated `accountSaga.ts` to use common API:

```typescript
import {
  fetchAccountApi,
  createAccountApi,
  updateAccountApi,
  deleteAccountApi,
} from '@/common/api/account.api';
import { isApiSuccess, getErrorMessages } from '@/common/api/client';

function* handleFetchAccounts(action) {
  try {
    const response = yield call(fetchAccountsApi, action.payload);
    
    if (isApiSuccess(response)) {
      yield put(fetchAccountsSuccess(response.data));
    } else {
      const errors = getErrorMessages(response);
      yield put(fetchAccountsFailure(errors.join(', ')));
    }
  } catch (error) {
    yield put(fetchAccountsFailure(error.message));
  }
}
```

---

## 🎨 Available Exports

### From `@/common`

```typescript
// API
import { apiGet, apiPost, apiPut, apiDelete } from '@/common';
import { isApiSuccess, getErrorMessages } from '@/common';
import { loginApi, logoutApi, refreshTokenApi } from '@/common';

// Constants
import { API_ENDPOINTS, HTTP_STATUS, STORAGE_KEYS } from '@/common';
import { MESSAGES } from '@/common';

// Utils
import { getAccessToken, setAccessToken, clearTokens } from '@/common';
import { encodeBase64, decodeBase64, encodeQueryString } from '@/common';

// Validation
import { AuthSchemas, AccountSchema, validate } from '@/common';

// Types
import type { ApiResponse, AuthTokenResponse } from '@/common';
```

---

## 📱 Example Pages

### Login Example

- **File**: `src/app/login-example/page.tsx`
- **URL**: `http://localhost:3000/login-example`
- **Features**: Full login with validation and error handling

---

## ✨ Benefits

✅ **Centralized API Logic** - All API calls in one place
✅ **Auto Token Management** - Tokens handled automatically
✅ **Type Safety** - Full TypeScript support
✅ **Error Handling** - Consistent error handling
✅ **Validation** - Zod schemas for forms
✅ **Reusable** - Easy to use across app
✅ **Maintainable** - Clear structure

---

## 🐛 Troubleshooting

### Issue: CORS errors
**Solution**: Configure CORS on your Laravel API

### Issue: Token not saving
**Solution**: Check localStorage in DevTools

### Issue: 401 Unauthorized
**Solution**: Check token expiration and refresh logic

### Issue: Validation errors
**Solution**: Check schema definitions match your data

---

## 📖 Files Created

- ✅ `src/common/api/client.ts` - API client
- ✅ `src/common/api/paths.ts` - API paths
- ✅ `src/common/api/auth.api.ts` - Auth API
- ✅ `src/common/api/account.api.ts` - Account API
- ✅ `src/common/constants/index.ts` - Constants
- ✅ `src/common/constants/messages.ts` - Messages
- ✅ `src/common/types/api.types.ts` - Types
- ✅ `src/common/utils/token.ts` - Token utils
- ✅ `src/common/utils/encode.ts` - Encode utils
- ✅ `src/common/validation/index.ts` - Validation
- ✅ `src/common/index.ts` - Exports
- ✅ `src/app/login-example/page.tsx` - Example
- ✅ Updated `src/store/sagas/accountSaga.ts`

---

**Ready to use! 🎉**

Test the login example at: `http://localhost:3000/login-example`

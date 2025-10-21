# Common Module - Quick Reference

## 🚀 Quick Import

```typescript
// All in one
import {
  // API
  apiGet, apiPost, apiPut, apiDelete,
  isApiSuccess, getErrorMessages,
  loginApi, logoutApi,
  
  // Constants
  API_ENDPOINTS, HTTP_STATUS, MESSAGES,
  
  // Utils
  getAccessToken, setAccessToken, clearTokens,
  encodeBase64, decodeBase64,
  
  // Validation
  AuthSchemas, AccountSchema, validate,
  
  // Types
  type ApiResponse, type AuthTokenResponse,
} from '@/common';
```

---

## 📡 API Calls

### GET Request
```typescript
import { apiGet } from '@/common';

const data = await apiGet('/accounts', { page: 1, limit: 10 });
```

### POST Request
```typescript
import { apiPost } from '@/common';

const result = await apiPost('/accounts', {
  email: 'test@example.com',
  name: 'Test User'
});
```

### PUT Request
```typescript
import { apiPut } from '@/common';

const updated = await apiPut('/accounts/1', { name: 'New Name' });
```

### DELETE Request
```typescript
import { apiDelete } from '@/common';

await apiDelete('/accounts/1');
```

---

## ✅ Check Response

```typescript
import { isApiSuccess, getErrorMessages } from '@/common';

const response = await apiPost('/accounts', data);

if (isApiSuccess(response)) {
  console.log('Success:', response.data);
} else {
  const errors = getErrorMessages(response);
  console.error('Errors:', errors);
}
```

---

## 🔐 Auth API

### Login
```typescript
import { loginApi } from '@/common';

const response = await loginApi({
  email: 'user@example.com',
  password: 'password123',
  remember: true
});
// Token is auto-saved
```

### Logout
```typescript
import { logoutApi } from '@/common';

await logoutApi();
// Tokens are auto-cleared
```

### Refresh Token
```typescript
import { refreshTokenApi } from '@/common';

const response = await refreshTokenApi();
// New token is auto-saved
```

---

## 🎫 Token Management

### Get Token
```typescript
import { getAccessToken } from '@/common';

const token = getAccessToken();
```

### Set Token
```typescript
import { setAccessToken } from '@/common';

setAccessToken('your-token-here');
```

### Check Auth
```typescript
import { isAuthenticated } from '@/common';

if (!isAuthenticated()) {
  router.push('/login');
}
```

### Check Expiration
```typescript
import { isTokenExpired, getAccessToken } from '@/common';

const token = getAccessToken();
if (token && isTokenExpired(token)) {
  // Token expired, refresh or logout
}
```

### Clear Tokens
```typescript
import { clearTokens } from '@/common';

clearTokens();
```

---

## 🔒 Validation

### Validate Login
```typescript
import { AuthSchemas, validate } from '@/common';

const result = validate(AuthSchemas.login, {
  email: 'test@example.com',
  password: 'password123'
});

if (!result.success) {
  console.error(result.errors);
} else {
  console.log('Valid:', result.data);
}
```

### Validate Account
```typescript
import { AccountSchema, validate } from '@/common';

const result = validate(AccountSchema, accountData);
```

### Custom Validation
```typescript
import { customValidators } from '@/common';

if (!customValidators.isValidEmail(email)) {
  alert('Invalid email');
}
```

---

## 🔤 Encode/Decode

### Base64
```typescript
import { encodeBase64, decodeBase64 } from '@/common';

const encoded = encodeBase64('Hello World');
const decoded = decodeBase64(encoded);
```

### Query String
```typescript
import { encodeQueryString } from '@/common';

const qs = encodeQueryString({
  page: 1,
  limit: 10,
  search: 'john'
});
// Result: "page=1&limit=10&search=john"
```

---

## 📝 Messages

### Success Messages
```typescript
import { MESSAGES } from '@/common';

alert(MESSAGES.SUCCESS.CREATED);
alert(MESSAGES.SUCCESS.UPDATED);
alert(MESSAGES.SUCCESS.DELETED);
```

### Error Messages
```typescript
alert(MESSAGES.ERROR.NETWORK);
alert(MESSAGES.ERROR.UNAUTHORIZED);
alert(MESSAGES.ERROR.NOT_FOUND);
```

### Validation Messages
```typescript
const msg = MESSAGES.VALIDATION.REQUIRED('Email');
// "Email is required"
```

---

## 🛣️ API Paths

### Using Path Helpers
```typescript
import { apiGet } from '@/common/api/client';
import { apiPaths } from '@/common/api/paths';

// Accounts
await apiGet(apiPaths.accounts.list({ page: 1 }));
await apiGet(apiPaths.accounts.detail(1));

// Auth
await apiPost(apiPaths.auth.login(), credentials);
```

### Query Params Helpers
```typescript
import { queryParams } from '@/common/api/paths';

const params = {
  ...queryParams.pagination(1, 10),
  ...queryParams.search('john'),
  ...queryParams.sort('created_at', 'desc')
};
```

---

## 🎯 Complete Example

```typescript
'use client';

import { useState } from 'react';
import {
  apiPost,
  isApiSuccess,
  getErrorMessages,
  AuthSchemas,
  validate,
  MESSAGES,
} from '@/common';

export default function LoginForm() {
  const [formData, setFormData] = useState({
    email: '',
    password: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);

    // Validate
    const validation = validate(AuthSchemas.login, formData);
    if (!validation.success) {
      setError(validation.errors?.join(', ') || 'Validation failed');
      return;
    }

    setLoading(true);

    try {
      // Call API
      const response = await apiPost('/auth/login', formData);

      // Check response
      if (isApiSuccess(response)) {
        alert(MESSAGES.SUCCESS.LOGIN);
        window.location.href = '/dashboard';
      } else {
        const errors = getErrorMessages(response);
        setError(errors.join(', '));
      }
    } catch (err: any) {
      setError(err.message || MESSAGES.ERROR.DEFAULT);
    } finally {
      setLoading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      {error && <div className="error">{error}</div>}
      
      <input
        type="email"
        value={formData.email}
        onChange={(e) => setFormData({...formData, email: e.target.value})}
        placeholder="Email"
      />
      
      <input
        type="password"
        value={formData.password}
        onChange={(e) => setFormData({...formData, password: e.target.value})}
        placeholder="Password"
      />
      
      <button type="submit" disabled={loading}>
        {loading ? MESSAGES.LOADING.DEFAULT : 'Login'}
      </button>
    </form>
  );
}
```

---

## 📚 Constants Reference

### HTTP Status
```typescript
import { HTTP_STATUS } from '@/common';

HTTP_STATUS.OK                    // 200
HTTP_STATUS.CREATED               // 201
HTTP_STATUS.BAD_REQUEST           // 400
HTTP_STATUS.UNAUTHORIZED          // 401
HTTP_STATUS.NOT_FOUND             // 404
HTTP_STATUS.INTERNAL_SERVER_ERROR // 500
```

### Storage Keys
```typescript
import { STORAGE_KEYS } from '@/common';

STORAGE_KEYS.ACCESS_TOKEN   // 'access_token'
STORAGE_KEYS.REFRESH_TOKEN  // 'refresh_token'
STORAGE_KEYS.USER_INFO      // 'user_info'
```

### API Endpoints
```typescript
import { API_ENDPOINTS } from '@/common';

API_ENDPOINTS.AUTH.LOGIN           // '/auth/login'
API_ENDPOINTS.ACCOUNTS.LIST        // '/accounts'
API_ENDPOINTS.ACCOUNTS.DETAIL(1)   // '/accounts/1'
```

---

## 🔧 TypeScript Types

```typescript
import type {
  ApiResponse,
  ApiErrorResponse,
  AuthTokenResponse,
  HttpMethod,
  ApiRequestConfig,
} from '@/common';

// Usage
const response: ApiResponse<Account[]> = await apiGet('/accounts');
```

---

## 📖 More Info

See **COMMON_MODULE_GUIDE.md** for complete documentation.

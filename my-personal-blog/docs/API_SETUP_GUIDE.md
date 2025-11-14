# API Common Setup Guide - Next.js + Axios + Redux Toolkit

## 📋 Tổng Quan

Setup này xây dựng trên best practices hiện tại cho Next.js với Axios, Redux Toolkit, Redux-Saga, và React Hot Toast.

---

## 🏗️ Kiến Trúc

```
src/
├── lib/
│   ├── apiInstance.ts      # Axios instance + interceptors
│   ├── apiMethod.ts        # GET, POST, PUT, PATCH, DELETE methods
│   ├── apiErrorHandle.ts   # Error handling logic
│   └── utils.ts
├── redux/
│   ├── slices/
│   │   └── authSlice.ts    # Auth state (accessToken, isAuthenticated)
│   ├── sagas/
│   │   └── authSaga.ts     # Login, Logout handlers
│   └── store.ts
├── constants/
│   ├── apiUrl.ts           # API endpoints
│   ├── clientUrl.ts        # Client routes
│   └── messages.ts         # Error messages
└── types/
    ├── apiType.ts          # API types
    └── authType.ts         # Auth types
```

---

## 🚀 Các Tính Năng Chính

### 1. **Headers Configuration**
```typescript
// Tự động thêm vào mọi request
- Content-Type: application/json
- Accept: application/json
- Authorization: Bearer {accessToken}  // Từ Redux state
- Cache-Control: no-cache
- Pragma: no-cache

// Với credentials (cookie) cho refresh-token & logout:
- withCredentials: true
```

### 2. **Token Management**
- ✅ Access token lưu trong Redux state
- ✅ Refresh token trong HTTP-only cookie (server-side)
- ✅ Auto-refresh khi 401 (token expired)
- ✅ Queue pending requests khi refresh
- ✅ Prevent duplicate refresh requests

### 3. **Error Handling**
```
401 → Auto refresh token + retry request
403 → Toast notification
404 → Redirect to /404 page
500, 502, 503, 504 → Redirect to /500 page
Network error → Show "Cannot connect to server"
```

### 4. **Retry Logic**
- GET: 2 retries (idempotent)
- POST/PUT/PATCH: 1 retry (mutation)
- DELETE: 2 retries (idempotent)
- Exponential backoff: 1s → 2s → 4s
- Chỉ retry network errors, không retry HTTP errors

### 5. **Request Queuing**
Khi token refresh đang diễn ra:
- Tất cả 401 requests chờ cho đến khi refresh xong
- Áp dụng token mới tự động
- Retry original request với token mới

---

## 📝 Cách Sử Dụng

### Login
```typescript
import { useDispatch } from 'react-redux';

const dispatch = useDispatch();

const handleLogin = (payload: LoginPayload) => {
  dispatch({ type: 'auth/loginSaga', payload });
  // Tự động cập nhật token trong Redux + localStorage
};
```

### API Call
```typescript
import * as API from '@/lib/apiMethod';
import { USERS_LIST } from '@/constants/apiUrl';

// GET with params
const data = await API.apiGet('/users', { page: 1, limit: 10 });

// POST with body
const result = await API.apiPost('/users', { name: 'John' });

// PUT
await API.apiPut(`/users/${id}`, { name: 'Jane' });

// PATCH
await API.apiPatch(`/users/${id}`, { status: 'active' });

// DELETE
await API.apiDelete(`/users/${id}`);

// Custom retry count
const data = await API.apiGet('/slow-endpoint', undefined, { retries: 5 });
```

### Error Handling
```typescript
try {
  const data = await API.apiGet('/users');
  // Toast & redirect tự động xảy ra trong interceptor
} catch (error) {
  // Chỉ xử lý các exception không được handle
  if (error.response?.status === 422) {
    // Validation error
  }
}
```

---

## 🔐 Security Features

| Feature | Description |
|---------|-------------|
| **CORS** | withCredentials: true cho refresh-token endpoint |
| **HTTP-only Cookie** | Refresh token không accessible từ JavaScript |
| **XSS Protection** | Access token chỉ trong Redux state (memory) |
| **CSRF Token** | Server validate via SameSite cookie |
| **Token Expiry** | Auto refresh khi 401 |
| **HTTPS** | Recommended cho production |

---

## 📊 Performance Optimizations

| Optimization | Benefit |
|--------------|---------|
| **Lazy store init** | Gọi `makeStore()` một lần, không lặp |
| **Promise-based queue** | Tránh race condition vs array-based |
| **Exponential backoff** | Giảm server load khi network error |
| **Timeout: 30s** | Balanced giữa UX và server stability |
| **Request deduplication** | Prevent duplicate API calls |

---

## ⚙️ Configuration

### Timeout
```typescript
// apiInstance.ts
timeout: 30000  // 30 seconds (từ 10s)
```

### Base URL
```typescript
baseURL: process.env.NEXT_PUBLIC_API_URL || 'https://api.example.com'
```

### Retry Strategy
```typescript
// apiMethod.ts
apiGet(..., { retries: 3 })    // Custom retry count
```

---

## 🐛 Troubleshooting

### Token không update sau login
**Solution:** Kiểm tra `authSlice.ts` - `setAuth()` dispatch đúng không

### 401 infinite loop
**Solution:** Kiểm tra refresh token endpoint response có `accessToken` field không

### Toast không hiển thị
**Solution:** Kiểm tra `Toaster` component có được render ở root layout không

### Network error nhiều lần
**Solution:** Kiểm tra `retries` config, tăng timeout nếu API slow

---

## 🔄 Token Refresh Flow

```
Request (expired token)
    ↓
401 Response
    ↓
Check: isRefreshing?
    ├─ YES → Wait for refreshPromise
    │        ├─ Success → Retry original request
    │        └─ Fail → Redirect to login
    └─ NO  → Start refresh process
            ├─ POST /refresh-token
            │   ├─ Success → Update Redux + Queue retry
            │   └─ Fail → Redirect to login
```

---

## 📚 Best Practices Applied

✅ **Separation of Concerns** - Riêng file cho instance, methods, error handling
✅ **DRY** - Retry logic + error handling tái sử dụng
✅ **Type Safety** - Full TypeScript typing
✅ **Error Recovery** - Auto-retry + auto-refresh
✅ **User Experience** - Toast notifications + redirects
✅ **Production Ready** - Exponential backoff + queue management
✅ **Performance** - Lazy initialization + memoization
✅ **Security** - HTTP-only cookies + CORS configuration

---

## 🚨 HTTP Status Codes Handled

| Code | Action |
|------|--------|
| 200-299 | Success ✓ |
| 400 | Bad Request (toast) |
| 401 | Auto-refresh + retry |
| 403 | Forbidden (toast) |
| 404 | Redirect /404 |
| 500 | Redirect /500 |
| 502-504 | Redirect /500 |
| No response | Network error (toast) |

---

## 📦 Dependencies

```json
{
  "axios": "^1.x",
  "react-redux": "^8.x",
  "@reduxjs/toolkit": "^1.x",
  "redux-saga": "^1.x",
  "react-hot-toast": "^2.x"
}
```

---

**Last Updated:** November 2025  
**Status:** Production Ready ✅

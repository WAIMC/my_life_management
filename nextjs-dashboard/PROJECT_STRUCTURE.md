# 📁 Project Structure - Redux Store

## ✅ Cấu Trúc Cuối Cùng

Đã dọn dẹp và giữ lại cấu trúc chuẩn như sau:

### 📂 `src/store/` - Redux + Saga (MAIN)

```
src/store/
├── constants/          # Action constants
│   └── accountConstants.ts
├── types/             # TypeScript types
│   └── accountTypes.ts
├── slices/            # Redux slices (reducers + actions)
│   ├── accountSlice.ts
│   └── counterSlice.ts
├── sagas/             # Redux Sagas (async logic)
│   └── accountSaga.ts
├── selectors/         # State selectors
│   └── accountSelectors.ts
├── hooks/             # Custom hooks
│   └── useAccount.ts
├── account.module.ts  # Module exports
├── hooks.ts           # Typed Redux hooks
├── rootReducer.ts     # Root reducer
├── rootSaga.ts        # Root saga
├── Provider.tsx       # Redux Provider
└── store.ts           # Store configuration
```

**✅ Đây là cấu trúc CHÍNH và đang được sử dụng**

### 📂 `src/lib/` - Utilities Only

```
src/lib/
└── utils.ts           # Utility functions (cn, etc.)
```

**⚠️ Chỉ chứa utility functions, KHÔNG có Redux**

---

## 🗑️ Đã Xóa

- ❌ `src/lib/store.ts` - File rỗng, không dùng
- ❌ `src/lib/reducers.ts` - File rỗng, không dùng
- ❌ `src/lib/sagas.ts` - Chỉ re-export, không cần
- ❌ `src/lib/slices/` - Toàn bộ thư mục (files rỗng)
  - `authSlice.ts`
  - `authSaga.ts`
  - `userSlice.ts`
  - `userSaga.ts`

---

## 📋 Import Paths

### ✅ Redux (từ `@/store`)

```typescript
// Hooks
import { useAppDispatch, useAppSelector } from '@/store/hooks';
import { useAccount } from '@/store/hooks/useAccount';

// Actions & Slices
import { fetchAccountsRequest } from '@/store/slices/accountSlice';

// Selectors
import { selectAccounts } from '@/store/selectors/accountSelectors';

// Types
import type { Account } from '@/store/types/accountTypes';

// Module (all exports)
import { fetchAccountsRequest, selectAccounts } from '@/store/account.module';
```

### ✅ Utils (từ `@/lib`)

```typescript
// Utilities
import { cn } from '@/lib/utils';
```

---

## 🎯 Convention

1. **Redux/State Management** → `src/store/`
2. **Utility Functions** → `src/lib/`
3. **UI Components** → `src/components/`
4. **Pages** → `src/app/`

---

## ✨ Benefits

✅ **Separation of Concerns** - Redux riêng, utils riêng
✅ **Clear Structure** - Dễ tìm, dễ maintain
✅ **No Duplication** - Không có code trùng lặp
✅ **Type Safe** - Full TypeScript support
✅ **Scalable** - Dễ mở rộng thêm modules

---

## 🚀 Next Steps

1. ✅ Cấu trúc đã được dọn dẹp
2. ✅ Redux store ở `src/store/`
3. ✅ Utils ở `src/lib/`
4. ✅ Không còn duplicate
5. ✅ Sẵn sàng sử dụng!

---

**Last Updated**: 2025-10-21
**Status**: ✅ Clean & Ready

# Quick Start Guide - Laravel API Integration

## 🚀 Getting Started

### Prerequisites
- Node.js 18+ installed
- pnpm installed

## 📁 Project Structure

```
nextjs-fe/
├── src/
│   ├── app/                    # Next.js pages
│   │   ├── login/             # Login page
│   │   └── admin/             # Admin pages (to be created)
│   ├── components/            # React components
│   │   ├── auth/              # Auth components
│   │   ├── layout/            # Layout components
│   │   └── ui/                # shadcn/ui components
│   ├── hooks/                 # Custom hooks
│   │   ├── useAuth.ts         # ✅ Authentication
│   │   ├── useApiData.ts      # ✅ Data fetching
│   │   ├── useCrud.ts         # ✅ CRUD operations
│   │   └── useJunctionTable.ts # ✅ Junction tables
│   ├── lib/                   # Utilities
│   │   ├── api-client.ts      # ✅ API client
│   │   └── types/             # ✅ TypeScript types
│   ├── services/              # API services
│   │   └── admin.service.ts   # ✅ Example service
│   └── constants/             # Constants
│       └── api-endpoints.ts   # ✅ API endpoints
└── .env.local                 # Environment config
```

---

## 🎯 Common Tasks

### 1. Fetch Data with Pagination

```typescript
import { useApiData } from '@/hooks/useApiData';
import type { AdminMst } from '@/lib/types/api';

function MyComponent() {
  const { data, loading, pagination, refetch } = useApiData<AdminMst>(
    '/admin-mst',
    {
      page: 1,
      per_page: 20,
      filters: { status: 1 },
      sort_by: 'created_at',
      sort_order: 'desc'
    }
  );

  if (loading) return <div>Loading...</div>;

  return (
    <div>
      {data.map(admin => (
        <div key={admin.id}>{admin.email}</div>
      ))}
    </div>
  );
}
```

### 2. Create/Update/Delete

```typescript
import { useCrud } from '@/hooks/useCrud';
import type { AdminMst } from '@/lib/types/api';

function MyForm() {
  const { create, update, remove, loading } = useCrud<AdminMst>('/admin-mst');

  const handleCreate = async () => {
    const id = await create({
      email: 'new@example.com',
      user_name: 'newuser',
      password: 'password123',
      first_name: 'John',
      last_name: 'Doe',
      gender: 1,
      status: 1,
      is_active: true,
      is_delete: false
    });
    console.log('Created with ID:', id);
  };

  const handleUpdate = async (id: number) => {
    await update(id, { first_name: 'Jane' });
  };

  const handleDelete = async (ids: number[]) => {
    await remove(ids);
  };

  return (
    <div>
      <button onClick={handleCreate} disabled={loading}>
        Create
      </button>
    </div>
  );
}
```

### 3. Manage Junction Tables

```typescript
import { useJunctionTable } from '@/hooks/useJunctionTable';

function AdminRoles({ adminId }: { adminId: number }) {
  const {
    allItems,      // All available roles
    selectedIds,   // Currently assigned role IDs
    toggleSelection,
    save,
    loading
  } = useJunctionTable(
    '/admin-role-mst',
    '/role-mst',
    'admin_mst_id',
    'role_mst_id',
    adminId
  );

  return (
    <div>
      {allItems.map(role => (
        <label key={role.id}>
          <input
            type="checkbox"
            checked={selectedIds.includes(role.id)}
            onChange={() => toggleSelection(role.id)}
          />
          {role.name}
        </label>
      ))}
      <button onClick={save} disabled={loading}>
        Save Changes
      </button>
    </div>
  );
}
```

### 4. Protect Routes

```typescript
import { ProtectedRoute } from '@/components/auth/protected-route';

export default function AdminPage() {
  return (
    <ProtectedRoute>
      <div>Protected content</div>
    </ProtectedRoute>
  );
}
```

---

## 🔧 Available Hooks

### `useAuth()`
Manage authentication state and actions.

**Returns:**
- `user` - Current user object
- `isAuthenticated` - Boolean auth status
- `isLoading` - Loading state
- `login(email, password)` - Login function
- `logout()` - Logout function
- `checkAuth()` - Check auth status

### `useApiData<T>(endpoint, options)`
Fetch paginated data with filters.

**Options:**
- `page` - Page number (default: 1)
- `per_page` - Items per page (default: 15)
- `filters` - Filter object
- `sort_by` - Sort column
- `sort_order` - 'asc' | 'desc'
- `from_date` - Start date (d/m/Y)
- `to_date` - End date (d/m/Y)

**Returns:**
- `data` - Array of items
- `loading` - Loading state
- `error` - Error object
- `pagination` - Pagination info
- `refetch()` - Refetch function

### `useCrud<T>(endpoint)`
Perform CRUD operations.

**Returns:**
- `create(data)` - Create item
- `update(id, data)` - Update item
- `remove(ids)` - Delete items
- `loading` - Loading state
- `error` - Error object

### `useJunctionTable<T>(...)`
Manage many-to-many relationships.

**Parameters:**
- `junctionEndpoint` - Junction table endpoint
- `allItemsEndpoint` - All items endpoint
- `parentIdKey` - Parent ID field name
- `childIdKey` - Child ID field name
- `parentId` - Parent ID value

**Returns:**
- `allItems` - All available items
- `assignedIds` - Currently assigned IDs
- `selectedIds` - Selected IDs
- `setSelectedIds(ids)` - Set selections
- `toggleSelection(id)` - Toggle single item
- `save()` - Save changes
- `loading` - Loading state
- `refetch()` - Refetch data

---

## 📚 TypeScript Types

All types are available in `src/lib/types/api.ts`:

```typescript
import type {
  // Master Data
  AdminMst,
  RoleMst,
  DepartmentMst,
  FeatureMst,
  ApiMst,
  LanguageMst,
  TranslationMst,
  TokenMst,
  PolicyDepartmentMst,
  OriginalTranslatorMst,
  
  // Management Data
  BannerMgmt,
  CategoryMgmt,
  SkillMgmt,
  SkillDescriptionMgmt,
  SliderMgmt,
  SocialMgmt,
  UserMgmt,
  SettingLinkMgmt,
  
  // Junction Tables
  AdminRoleMst,
  AdminDepartmentMst,
  ApiRoleMst,
  DepartmentManagementMst,
  TranslationLanguageMst,
  CategorySkillMgmt,
  
  // Common
  ApiResponse,
  PaginatedResponse,
  ListQueryParams,
  AuthUser,
} from '@/lib/types/api';
```

---

## 🎨 UI Components

Using shadcn/ui components:

```typescript
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
// ... and more
```

---

## 🐛 Troubleshooting

### TypeScript Errors
If you see TypeScript errors about missing modules:
```bash
pnpm install
```

### API Connection Issues
1. Check Laravel API is running: `http://localhost:8000/api`
2. Verify `.env.local` has correct API URL
3. Check browser console for CORS errors

### Authentication Issues
1. Clear browser cookies
2. Check Laravel API credentials
3. Verify JWT token configuration in Laravel

---

## 📖 Next Steps

1. Read `LARAVEL_API_INTEGRATION_PLAN.md` for full implementation plan
2. Check `INTEGRATION_PROGRESS.md` for current status
3. Start implementing modules following the template
4. Refer to `src/services/admin.service.ts` as example

---

**Need Help?** Check the documentation files or review the example code in `src/hooks/` and `src/services/`.

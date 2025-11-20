# Laravel API Integration - Implementation Plan

## 📋 Overview

This document outlines the complete plan to integrate 24 Laravel API modules into the Next.js + shadcn/ui frontend.

**Current Status:**
- ✅ Phase 1: Core Infrastructure - COMPLETE
- ✅ Phase 2: Authentication System - COMPLETE
- ⏳ Phase 3: Shared Components - PENDING
- ⏳ Phase 4-5: Module Implementation - PENDING

---

## 🎯 Project Goals

1. Integrate all 24 API modules (15 Master Data + 9 Management Data)
2. Implement full CRUD operations with pagination and filtering
3. Build reusable components for consistent UI
4. Ensure type safety with TypeScript
5. Maintain high code quality and performance

---

## 📦 What's Already Done

### Phase 1: Core Infrastructure ✅

**Files Created:**
- `src/lib/api-client.ts` - API client with JWT auth and auto-refresh
- `src/lib/types/api.ts` - TypeScript types for all 24 modules
- `src/lib/types/enums.ts` - Enums matching Laravel backend
- `src/constants/api-endpoints.ts` - Centralized endpoint constants
- `src/hooks/useAuth.ts` - Authentication hook
- `src/hooks/useApiData.ts` - Data fetching hook with pagination
- `src/hooks/useCrud.ts` - CRUD operations hook
- `src/hooks/useJunctionTable.ts` - Junction table management hook
- `src/types/env.d.ts` - Environment variable types

**Key Features:**
- JWT token management (memory storage for security)
- Automatic token refresh on 401 errors
- Type-safe API calls
- Reusable hooks for common operations

### Phase 2: Authentication System ✅

**Files Created/Updated:**
- `src/app/login/page.tsx` - Login page (updated)
- `src/components/auth/protected-route.tsx` - Protected route wrapper
- `src/services/admin.service.ts` - Example service template
- `.env.local.example` - Environment configuration template

**Features:**
- Modern login UI with loading states
- Auto-redirect after login
- Protected routes with auth check
- Service layer pattern

---

## 🚀 Implementation Phases

### Phase 3: Shared Components

Build reusable components used across all modules:

#### 3.1 Data Table Component
**File:** `src/components/data-table/data-table.tsx`

Features:
- Column sorting (click header to sort)
- Row selection with checkboxes
- Bulk actions toolbar
- Loading skeleton
- Empty state
- Responsive design
- Type-safe column definitions

#### 3.2 Pagination Component
**File:** `src/components/data-table/pagination.tsx`

Features:
- Previous/Next buttons
- Page number display
- Items per page selector
- Total count display
- Keyboard navigation

#### 3.3 Filter Panel Component
**File:** `src/components/data-table/filter-panel.tsx`

Features:
- Text search (LIKE)
- Exact match filters
- Date range picker
- Status dropdown
- Clear all filters button
- Collapsible panel

#### 3.4 CRUD Form Component
**File:** `src/components/forms/crud-form.tsx`

Features:
- React Hook Form integration
- Zod validation
- Field error display
- Submit/cancel buttons
- Loading states
- Auto-focus first field

#### 3.5 File Upload Component
**File:** `src/components/upload/file-upload.tsx`

Features:
- Drag and drop support
- Image preview
- Upload progress bar
- File type validation
- Size validation
- Multiple file support

#### 3.6 Junction Manager Component
**File:** `src/components/junction/junction-manager.tsx`

Features:
- Display all available items
- Show assigned items (checked)
- Toggle assignments
- Save changes button
- Show pending changes count
- Search/filter items

---

### Phase 4: Master Data Modules (15 modules)

Each module follows this pattern:

#### Module Structure
```
src/app/admin/{module}/
├── page.tsx              # List page
├── create/page.tsx       # Create page
└── [id]/
    ├── edit/page.tsx     # Edit page
    └── {junction}/page.tsx  # Junction management (if applicable)
```

#### Implementation Order

**Week 1: Core Modules**
1. ✅ Admin Management (`/admin/admins`) - **START HERE AS TEMPLATE**
2. Role Management (`/admin/roles`)
3. Department Management (`/admin/departments`)

**Week 2: Configuration Modules**
4. Feature Management (`/admin/features`)
5. API Management (`/admin/apis`)
6. Language Management (`/admin/languages`)
7. Translation Management (`/admin/translations`)

**Week 3: Supporting Modules**
8. Token Management (`/admin/tokens`)
9. Policy Department Management (`/admin/policy-departments`)
10. Original Translator Management (`/admin/original-translators`)

**Junction Tables (integrated into parent modules):**
11. Admin-Role Junction (in Admin edit page)
12. Admin-Department Junction (in Admin edit page)
13. API-Role Junction (in API edit page)
14. Department-Management Junction (in Department edit page)
15. Translation-Language Junction (in Translation edit page)

---

### Phase 5: Management Data Modules (9 modules)

**Week 4: Content Modules**
1. Banner Management (`/admin/banners`) - with image upload
2. Category Management (`/admin/categories`)
3. Skill Management (`/admin/skills`)
4. Skill Description Management (`/admin/skill-descriptions`)

**Week 5: Additional Modules**
5. Slider Management (`/admin/sliders`) - with image upload
6. Social Management (`/admin/socials`)
7. User Management (`/admin/users`)
8. Setting Link Management (`/admin/setting-links`)
9. Category-Skill Junction (in Category edit page)

---

## 📝 Module Implementation Template

### 1. Create Service File

**File:** `src/services/{module}.service.ts`

```typescript
import { apiClient } from '@/lib/api-client';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { YourModel, PaginatedResponse, ListQueryParams } from '@/lib/types/api';

export interface YourModelListParams extends ListQueryParams {
  // Add specific filters
  name?: string;
  status?: number;
  is_active?: boolean;
}

export const yourModelService = {
  async list(params: YourModelListParams = {}) {
    return apiClient.get<PaginatedResponse<YourModel>>(ENDPOINTS.MASTER.YOUR_MODEL, params);
  },

  async getById(id: number) {
    const response = await this.list({ id, per_page: 1 });
    return response.data.data[0] || null;
  },

  async create(data: Omit<YourModel, 'id' | 'updated_at'>) {
    return apiClient.post<number>(ENDPOINTS.MASTER.YOUR_MODEL, data);
  },

  async update(id: number, data: Partial<YourModel>) {
    return apiClient.put<number>(`${ENDPOINTS.MASTER.YOUR_MODEL}/${id}`, { id, ...data });
  },

  async delete(ids: number[]) {
    await apiClient.delete(ENDPOINTS.MASTER.YOUR_MODEL, { ids });
  },
};
```

### 2. Create List Page

**File:** `src/app/admin/{module}/page.tsx`

```typescript
'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { useApiData } from '@/hooks/useApiData';
import { useCrud } from '@/hooks/useCrud';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { DataTable } from '@/components/data-table/data-table';
import { Pagination } from '@/components/data-table/pagination';
import { FilterPanel } from '@/components/data-table/filter-panel';
import { Button } from '@/components/ui/button';
import type { YourModel } from '@/lib/types/api';

export default function YourModelListPage() {
  const router = useRouter();
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(20);
  const [filters, setFilters] = useState({});
  const [sortBy, setSortBy] = useState('created_at');
  const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('desc');
  const [selectedIds, setSelectedIds] = useState<number[]>([]);

  const { data, loading, pagination, refetch } = useApiData<YourModel>(
    '/your-endpoint',
    { page, perPage, filters, sortBy, sortOrder }
  );

  const { remove } = useCrud<YourModel>('/your-endpoint');

  const handleDelete = async (ids: number[]) => {
    if (!confirm(`Delete ${ids.length} item(s)?`)) return;
    await remove(ids);
    setSelectedIds([]);
    refetch();
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Your Module"
        description="Manage your items"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Your Module', isActive: true }
        ]}
        action={
          <Button onClick={() => router.push('/admin/your-module/create')}>
            Create New
          </Button>
        }
      />

      <div className="mt-6">
        <FilterPanel
          filters={filters}
          onFilterChange={setFilters}
          onReset={() => setFilters({})}
        />

        {selectedIds.length > 0 && (
          <div className="mb-4 p-4 bg-blue-50 rounded-lg flex items-center justify-between">
            <span>{selectedIds.length} selected</span>
            <Button variant="destructive" onClick={() => handleDelete(selectedIds)}>
              Delete Selected
            </Button>
          </div>
        )}

        <DataTable
          data={data}
          loading={loading}
          selectedIds={selectedIds}
          onSelectionChange={setSelectedIds}
          onSort={(column) => {
            if (sortBy === column) {
              setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc');
            } else {
              setSortBy(column);
              setSortOrder('asc');
            }
          }}
          sortBy={sortBy}
          sortOrder={sortOrder}
          onEdit={(id) => router.push(`/admin/your-module/${id}/edit`)}
          onDelete={(id) => handleDelete([id])}
        />

        <Pagination
          pagination={pagination}
          page={page}
          onPageChange={setPage}
          perPage={perPage}
          onPerPageChange={setPerPage}
        />
      </div>
    </AdminLayout>
  );
}
```

### 3. Create Form Page (Create/Edit)

**File:** `src/app/admin/{module}/create/page.tsx`

```typescript
'use client';

import { useRouter } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { CrudForm } from '@/components/forms/crud-form';
import type { YourModel } from '@/lib/types/api';

const schema = z.object({
  name: z.string().min(1, 'Name is required'),
  description: z.string().optional(),
  status: z.number().min(1).max(2),
  is_active: z.boolean(),
  // Add other fields
});

type FormData = z.infer<typeof schema>;

export default function CreateYourModelPage() {
  const router = useRouter();
  const { create, loading } = useCrud<YourModel>('/your-endpoint');

  const form = useForm<FormData>({
    resolver: zodResolver(schema),
    defaultValues: {
      status: 1,
      is_active: true,
    },
  });

  const onSubmit = async (data: FormData) => {
    await create(data);
    router.push('/admin/your-module');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Create New Item"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Your Module', href: '/admin/your-module' },
          { label: 'Create', isActive: true }
        ]}
      />

      <div className="mt-6 max-w-2xl">
        <CrudForm
          form={form}
          onSubmit={onSubmit}
          onCancel={() => router.push('/admin/your-module')}
          loading={loading}
        />
      </div>
    </AdminLayout>
  );
}
```

### 4. Update Sidebar Navigation

**File:** `src/components/layout/sidebar.tsx`

Add menu item:
```typescript
{
  title: 'Master Data',
  items: [
    { label: 'Your Module', href: '/admin/your-module', icon: YourIcon },
    // ... other items
  ]
}
```

---

## 🧪 Testing Checklist

For each module, test:

- [ ] List page loads with pagination
- [ ] Filters work correctly
- [ ] Sorting works on all columns
- [ ] Create new item
- [ ] Edit existing item
- [ ] Delete single item
- [ ] Bulk delete multiple items
- [ ] Form validation works
- [ ] Error messages display correctly
- [ ] Loading states show properly
- [ ] Responsive on mobile/tablet/desktop

---

## 📚 Key Patterns to Follow

### 1. Always Use Hooks
```typescript
// ✅ Good
const { data, loading } = useApiData<AdminMst>('/admin-mst');

// ❌ Bad - don't call apiClient directly in components
const data = await apiClient.get('/admin-mst');
```

### 2. Type Everything
```typescript
// ✅ Good
const [admins, setAdmins] = useState<AdminMst[]>([]);

// ❌ Bad
const [admins, setAdmins] = useState([]);
```

### 3. Use Services for API Calls
```typescript
// ✅ Good
import { adminService } from '@/services/admin.service';
const admin = await adminService.getById(1);

// ❌ Bad
const response = await apiClient.get('/admin-mst', { id: 1 });
```

### 4. Handle Errors Gracefully
```typescript
// ✅ Good
try {
  await create(data);
  router.push('/admin/admins');
} catch (error) {
  // Error already shown by useCrud hook
  console.error('Failed to create:', error);
}

// ❌ Bad - no error handling
await create(data);
router.push('/admin/admins');
```

---

## 🎯 Success Criteria

- ✅ All 24 modules fully functional
- ✅ Consistent UI/UX across all modules
- ✅ No TypeScript errors
- ✅ No console errors
- ✅ Fast page loads (< 2s)
- ✅ Responsive on all devices
- ✅ Proper error handling
- ✅ Loading states for all async operations

---

## 📞 Need Help?

Refer to these files:
- `LARAVEL_API_DOCUMENTATION.md` - Complete API reference
- `NEXTJS_INTEGRATION_EXAMPLES.md` - Code examples
- `src/services/admin.service.ts` - Service template
- `src/hooks/` - Reusable hooks

---

**Last Updated:** November 20, 2025  
**Status:** Phase 1 & 2 Complete, Ready for Phase 3

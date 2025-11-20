# Laravel API - Real-World Next.js Integration Examples

## 📚 Complete Integration Guide

This document provides complete, production-ready examples for integrating the Laravel API with Next.js frontend.

---

## 🎯 Table of Contents

1. [Complete Admin Management Module](#complete-admin-management-module)
2. [Authentication Flow](#authentication-flow)
3. [File Upload with Preview](#file-upload-with-preview)
4. [Data Table with Pagination & Filtering](#data-table-with-pagination--filtering)
5. [Form Handling with Validation](#form-handling-with-validation)
6. [Junction Table Management](#junction-table-management)
7. [Real-Time Search](#real-time-search)
8. [Optimistic UI Updates](#optimistic-ui-updates)

---

## 1. Complete Admin Management Module

### Directory Structure

```
app/
├── admin/
│   ├── page.tsx                 # Admin list page
│   ├── create/page.tsx          # Create admin page
│   ├── [id]/edit/page.tsx       # Edit admin page
│   └── components/
│       ├── AdminTable.tsx       # Table component
│       ├── AdminForm.tsx        # Form component
│       └── AdminFilters.tsx     # Filter component
├── components/
│   ├── ui/                      # Shared UI components
│   └── layout/                  # Layout components
├── lib/
│   ├── api-client.ts            # API client
│   └── types.ts                 # TypeScript types
├── hooks/
│   ├── useAuth.ts
│   ├── useApiData.ts
│   └── useCrud.ts
└── services/
    └── admin.service.ts         # Admin API service
```

### Complete Admin List Page

```typescript
// app/admin/page.tsx
'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { AdminTable } from './components/AdminTable';
import { AdminFilters } from './components/AdminFilters';
import { useApiData } from '@/hooks/useApiData';
import { useCrud } from '@/hooks/useCrud';
import { AdminMst } from '@/lib/types';
import { Button } from '@/components/ui/button';
import { toast } from 'react-hot-toast';

export default function AdminPage() {
  const router = useRouter();
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(20);
  const [filters, setFilters] = useState({});
  const [sortBy, setSortBy] = useState('created_at');
  const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('desc');
  const [selectedIds, setSelectedIds] = useState<number[]>([]);

  // Fetch data
  const { data, loading, error, pagination, refetch } = useApiData<AdminMst>(
    '/admin-mst',
    { page, perPage, filters, sortBy, sortOrder }
  );

  // CRUD operations
  const { remove, loading: deleteLoading } = useCrud<AdminMst>('/admin-mst');

  const handleDelete = async (ids: number[]) => {
    if (!confirm(`Delete ${ids.length} admin(s)?`)) return;

    try {
      await remove(ids);
      setSelectedIds([]);
      refetch();
    } catch (error) {
      console.error('Delete failed:', error);
    }
  };

  const handleFilterChange = (newFilters: any) => {
    setFilters(newFilters);
    setPage(1); // Reset to first page
  };

  const handleSort = (column: string) => {
    if (sortBy === column) {
      setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc');
    } else {
      setSortBy(column);
      setSortOrder('asc');
    }
  };

  if (error) {
    return (
      <div className="p-8 text-center">
        <p className="text-red-500">Error loading admins: {error.message}</p>
        <Button onClick={() => refetch()}>Retry</Button>
      </div>
    );
  }

  return (
    <div className="container mx-auto p-6">
      {/* Header */}
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-3xl font-bold">Admin Management</h1>
        <Button onClick={() => router.push('/admin/create')}>
          Create Admin
        </Button>
      </div>

      {/* Filters */}
      <AdminFilters
        filters={filters}
        onFilterChange={handleFilterChange}
        onReset={() => {
          setFilters({});
          setPage(1);
        }}
      />

      {/* Bulk Actions */}
      {selectedIds.length > 0 && (
        <div className="mb-4 p-4 bg-blue-50 rounded-lg flex items-center justify-between">
          <span>{selectedIds.length} selected</span>
          <Button
            variant="destructive"
            onClick={() => handleDelete(selectedIds)}
            disabled={deleteLoading}
          >
            Delete Selected
          </Button>
        </div>
      )}

      {/* Table */}
      <AdminTable
        data={data}
        loading={loading}
        selectedIds={selectedIds}
        onSelectionChange={setSelectedIds}
        onSort={handleSort}
        sortBy={sortBy}
        sortOrder={sortOrder}
        onEdit={(id) => router.push(`/admin/${id}/edit`)}
        onDelete={(id) => handleDelete([id])}
      />

      {/* Pagination */}
      <div className="mt-6 flex items-center justify-between">
        <div className="text-sm text-gray-600">
          Showing {pagination.from} to {pagination.to} of {pagination.total} results
        </div>
        <div className="flex gap-2">
          <Button
            variant="outline"
            disabled={page === 1}
            onClick={() => setPage(page - 1)}
          >
            Previous
          </Button>
          <span className="px-4 py-2">
            Page {pagination.currentPage} of {pagination.lastPage}
          </span>
          <Button
            variant="outline"
            disabled={page === pagination.lastPage}
            onClick={() => setPage(page + 1)}
          >
            Next
          </Button>
        </div>
      </div>
    </div>
  );
}
```

### Admin Table Component

```typescript
// app/admin/components/AdminTable.tsx
'use client';

import { AdminMst } from '@/lib/types';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { ArrowUpDown, Edit, Trash2 } from 'lucide-react';

interface AdminTableProps {
  data: AdminMst[];
  loading: boolean;
  selectedIds: number[];
  onSelectionChange: (ids: number[]) => void;
  onSort: (column: string) => void;
  sortBy: string;
  sortOrder: 'asc' | 'desc';
  onEdit: (id: number) => void;
  onDelete: (id: number) => void;
}

export function AdminTable({
  data,
  loading,
  selectedIds,
  onSelectionChange,
  onSort,
  sortBy,
  sortOrder,
  onEdit,
  onDelete,
}: AdminTableProps) {
  const handleSelectAll = (checked: boolean) => {
    if (checked) {
      onSelectionChange(data.map((admin) => admin.id));
    } else {
      onSelectionChange([]);
    }
  };

  const handleSelectOne = (id: number, checked: boolean) => {
    if (checked) {
      onSelectionChange([...selectedIds, id]);
    } else {
      onSelectionChange(selectedIds.filter((selectedId) => selectedId !== id));
    }
  };

  const SortIcon = ({ column }: { column: string }) => (
    <ArrowUpDown
      className={`ml-2 h-4 w-4 inline ${
        sortBy === column ? 'text-blue-600' : 'text-gray-400'
      }`}
    />
  );

  if (loading) {
    return (
      <div className="text-center py-12">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto"></div>
        <p className="mt-4 text-gray-600">Loading...</p>
      </div>
    );
  }

  if (data.length === 0) {
    return (
      <div className="text-center py-12 bg-gray-50 rounded-lg">
        <p className="text-gray-600">No admins found</p>
      </div>
    );
  }

  return (
    <div className="overflow-x-auto rounded-lg border border-gray-200">
      <table className="min-w-full divide-y divide-gray-200">
        <thead className="bg-gray-50">
          <tr>
            <th className="px-6 py-3 text-left">
              <Checkbox
                checked={selectedIds.length === data.length}
                onCheckedChange={handleSelectAll}
              />
            </th>
            <th
              className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
              onClick={() => onSort('id')}
            >
              ID <SortIcon column="id" />
            </th>
            <th
              className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
              onClick={() => onSort('email')}
            >
              Email <SortIcon column="email" />
            </th>
            <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Name
            </th>
            <th
              className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
              onClick={() => onSort('status')}
            >
              Status <SortIcon column="status" />
            </th>
            <th
              className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100"
              onClick={() => onSort('updated_at')}
            >
              Updated <SortIcon column="updated_at" />
            </th>
            <th className="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody className="bg-white divide-y divide-gray-200">
          {data.map((admin) => (
            <tr key={admin.id} className="hover:bg-gray-50">
              <td className="px-6 py-4">
                <Checkbox
                  checked={selectedIds.includes(admin.id)}
                  onCheckedChange={(checked) =>
                    handleSelectOne(admin.id, checked as boolean)
                  }
                />
              </td>
              <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {admin.id}
              </td>
              <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {admin.email}
              </td>
              <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {admin.first_name} {admin.last_name}
              </td>
              <td className="px-6 py-4 whitespace-nowrap">
                <span
                  className={`px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                    admin.is_active
                      ? 'bg-green-100 text-green-800'
                      : 'bg-red-100 text-red-800'
                  }`}
                >
                  {admin.is_active ? 'Active' : 'Inactive'}
                </span>
              </td>
              <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {admin.updated_at}
              </td>
              <td className="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <Button
                  variant="ghost"
                  size="sm"
                  onClick={() => onEdit(admin.id)}
                >
                  <Edit className="h-4 w-4" />
                </Button>
                <Button
                  variant="ghost"
                  size="sm"
                  onClick={() => onDelete(admin.id)}
                  className="text-red-600 hover:text-red-900"
                >
                  <Trash2 className="h-4 w-4" />
                </Button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
```

### Admin Form Component

```typescript
// app/admin/components/AdminForm.tsx
'use client';

import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { AdminMst } from '@/lib/types';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select } from '@/components/ui/select';
import { uploadFile } from '@/utils/upload';
import { useState } from 'react';
import Image from 'next/image';

const adminSchema = z.object({
  email: z.string().email('Invalid email address'),
  user_name: z.string().min(3, 'Username must be at least 3 characters'),
  password: z.string().min(8, 'Password must be at least 8 characters').optional(),
  first_name: z.string().min(1, 'First name is required'),
  last_name: z.string().min(1, 'Last name is required'),
  address: z.string().optional(),
  phone_number: z.string().optional(),
  birth: z.string().optional(),
  gender: z.number().min(1).max(3),
  status: z.number().min(1).max(2),
  is_active: z.boolean(),
  avatar: z.string().optional(),
});

type AdminFormData = z.infer<typeof adminSchema>;

interface AdminFormProps {
  initialData?: Partial<AdminMst>;
  onSubmit: (data: AdminFormData) => Promise<void>;
  onCancel: () => void;
  loading?: boolean;
}

export function AdminForm({
  initialData,
  onSubmit,
  onCancel,
  loading = false,
}: AdminFormProps) {
  const [avatarUrl, setAvatarUrl] = useState(initialData?.avatar || '');
  const [uploading, setUploading] = useState(false);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
  } = useForm<AdminFormData>({
    resolver: zodResolver(adminSchema),
    defaultValues: {
      ...initialData,
      gender: initialData?.gender || 1,
      status: initialData?.status || 1,
      is_active: initialData?.is_active ?? true,
    },
  });

  const handleFileUpload = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    try {
      setUploading(true);
      const url = await uploadFile(file, 'avatar');
      setAvatarUrl(url);
      setValue('avatar', url);
    } catch (error) {
      console.error('Upload failed:', error);
    } finally {
      setUploading(false);
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
      {/* Avatar Upload */}
      <div>
        <Label>Avatar</Label>
        <div className="mt-2 flex items-center gap-4">
          {avatarUrl && (
            <Image
              src={avatarUrl}
              alt="Avatar"
              width={64}
              height={64}
              className="rounded-full"
            />
          )}
          <Input
            type="file"
            accept="image/*"
            onChange={handleFileUpload}
            disabled={uploading}
          />
        </div>
      </div>

      {/* Email */}
      <div>
        <Label htmlFor="email">Email *</Label>
        <Input
          id="email"
          type="email"
          {...register('email')}
          className={errors.email ? 'border-red-500' : ''}
        />
        {errors.email && (
          <p className="mt-1 text-sm text-red-500">{errors.email.message}</p>
        )}
      </div>

      {/* Username */}
      <div>
        <Label htmlFor="user_name">Username *</Label>
        <Input
          id="user_name"
          {...register('user_name')}
          className={errors.user_name ? 'border-red-500' : ''}
        />
        {errors.user_name && (
          <p className="mt-1 text-sm text-red-500">{errors.user_name.message}</p>
        )}
      </div>

      {/* Password */}
      {!initialData && (
        <div>
          <Label htmlFor="password">Password *</Label>
          <Input
            id="password"
            type="password"
            {...register('password')}
            className={errors.password ? 'border-red-500' : ''}
          />
          {errors.password && (
            <p className="mt-1 text-sm text-red-500">{errors.password.message}</p>
          )}
        </div>
      )}

      {/* Name Fields */}
      <div className="grid grid-cols-2 gap-4">
        <div>
          <Label htmlFor="first_name">First Name *</Label>
          <Input
            id="first_name"
            {...register('first_name')}
            className={errors.first_name ? 'border-red-500' : ''}
          />
          {errors.first_name && (
            <p className="mt-1 text-sm text-red-500">{errors.first_name.message}</p>
          )}
        </div>
        <div>
          <Label htmlFor="last_name">Last Name *</Label>
          <Input
            id="last_name"
            {...register('last_name')}
            className={errors.last_name ? 'border-red-500' : ''}
          />
          {errors.last_name && (
            <p className="mt-1 text-sm text-red-500">{errors.last_name.message}</p>
          )}
        </div>
      </div>

      {/* Gender & Status */}
      <div className="grid grid-cols-2 gap-4">
        <div>
          <Label htmlFor="gender">Gender *</Label>
          <Select {...register('gender', { valueAsNumber: true })}>
            <option value={1}>Male</option>
            <option value={2}>Female</option>
            <option value={3}>Other</option>
          </Select>
        </div>
        <div>
          <Label htmlFor="status">Status *</Label>
          <Select {...register('status', { valueAsNumber: true })}>
            <option value={1}>Active</option>
            <option value={2}>Inactive</option>
          </Select>
        </div>
      </div>

      {/* Active Checkbox */}
      <div className="flex items-center gap-2">
        <input
          type="checkbox"
          id="is_active"
          {...register('is_active')}
          className="rounded"
        />
        <Label htmlFor="is_active">Is Active</Label>
      </div>

      {/* Actions */}
      <div className="flex justify-end gap-4">
        <Button type="button" variant="outline" onClick={onCancel}>
          Cancel
        </Button>
        <Button type="submit" disabled={loading || uploading}>
          {loading ? 'Saving...' : initialData ? 'Update' : 'Create'}
        </Button>
      </div>
    </form>
  );
}
```

---

## 2. Authentication Flow

### Login Page

```typescript
// app/login/page.tsx
'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { useAuth } from '@/hooks/useAuth';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { toast } from 'react-hot-toast';

export default function LoginPage() {
  const router = useRouter();
  const { login } = useAuth();
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);

    try {
      await login(email, password);
      toast.success('Login successful');
      router.push('/dashboard');
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Login failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <div className="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 className="text-2xl font-bold mb-6 text-center">Login</h1>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <Label htmlFor="email">Email</Label>
            <Input
              id="email"
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />
          </div>
          <div>
            <Label htmlFor="password">Password</Label>
            <Input
              id="password"
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>
          <Button type="submit" className="w-full" disabled={loading}>
            {loading ? 'Logging in...' : 'Login'}
          </Button>
        </form>
      </div>
    </div>
  );
}
```

### Protected Route Component

```typescript
// components/ProtectedRoute.tsx
'use client';

import { useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useAuth } from '@/hooks/useAuth';

export function ProtectedRoute({ children }: { children: React.ReactNode }) {
  const router = useRouter();
  const { isAuthenticated, user } = useAuth();

  useEffect(() => {
    if (!isAuthenticated) {
      router.push('/login');
    }
  }, [isAuthenticated, router]);

  if (!isAuthenticated) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    );
  }

  return <>{children}</>;
}
```

---

## 3. Junction Table Management (Admin-Role Assignment)

```typescript
// app/admin/[id]/roles/page.tsx
'use client';

import { useState, useEffect } from 'react';
import { useParams } from 'next/navigation';
import { apiClient } from '@/lib/api-client';
import { RoleMst } from '@/lib/types';
import { Checkbox } from '@/components/ui/checkbox';
import { Button } from '@/components/ui/button';
import { toast } from 'react-hot-toast';

export default function AdminRolesPage() {
  const params = useParams();
  const adminId = Number(params.id);

  const [allRoles, setAllRoles] = useState<RoleMst[]>([]);
  const [assignedRoleIds, setAssignedRoleIds] = useState<number[]>([]);
  const [selectedRoleIds, setSelectedRoleIds] = useState<number[]>([]);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  useEffect(() => {
    fetchData();
  }, [adminId]);

  const fetchData = async () => {
    try {
      setLoading(true);
      
      // Fetch all roles
      const rolesResponse = await apiClient.get<{ data: RoleMst[] }>('/role-mst', {
        per_page: 100,
      });
      setAllRoles(rolesResponse.data.data);

      // Fetch assigned roles
      const assignedResponse = await apiClient.get('/admin-role-mst', {
        admin_mst_id: adminId,
      });
      const assignedIds = assignedResponse.data.data.map((item: any) => item.role_mst_id);
      setAssignedRoleIds(assignedIds);
      setSelectedRoleIds(assignedIds);
    } catch (error) {
      toast.error('Failed to load roles');
    } finally {
      setLoading(false);
    }
  };

  const handleSave = async () => {
    try {
      setSaving(true);

      // Calculate changes
      const toDelete = assignedRoleIds
        .filter((id) => !selectedRoleIds.includes(id))
        .map((role_mst_id) => ({ admin_mst_id: adminId, role_mst_id }));

      const toInsert = selectedRoleIds
        .filter((id) => !assignedRoleIds.includes(id))
        .map((role_mst_id) => ({ admin_mst_id: adminId, role_mst_id }));

      if (toDelete.length === 0 && toInsert.length === 0) {
        toast.success('No changes to save');
        return;
      }

      // Update assignments
      await apiClient.put('/admin-role-mst', {
        admin_mst_id: adminId,
        delete: toDelete.length > 0 ? toDelete : undefined,
        insert: toInsert.length > 0 ? toInsert : undefined,
      });

      setAssignedRoleIds(selectedRoleIds);
      toast.success('Roles updated successfully');
    } catch (error: any) {
      toast.error(error.response?.data?.message || 'Failed to update roles');
    } finally {
      setSaving(false);
    }
  };

  const handleToggle = (roleId: number) => {
    setSelectedRoleIds((prev) =>
      prev.includes(roleId)
        ? prev.filter((id) => id !== roleId)
        : [...prev, roleId]
    );
  };

  if (loading) {
    return <div className="p-8 text-center">Loading...</div>;
  }

  return (
    <div className="container mx-auto p-6">
      <h1 className="text-2xl font-bold mb-6">Assign Roles to Admin #{adminId}</h1>

      <div className="bg-white rounded-lg shadow p-6">
        <div className="space-y-4">
          {allRoles.map((role) => (
            <div key={role.id} className="flex items-center gap-3 p-3 hover:bg-gray-50 rounded">
              <Checkbox
                checked={selectedRoleIds.includes(role.id)}
                onCheckedChange={() => handleToggle(role.id)}
              />
              <div className="flex-1">
                <p className="font-medium">{role.name}</p>
                {role.description && (
                  <p className="text-sm text-gray-600">{role.description}</p>
                )}
              </div>
            </div>
          ))}
        </div>

        <div className="mt-6 flex justify-end gap-4">
          <Button variant="outline" onClick={fetchData}>
            Reset
          </Button>
          <Button onClick={handleSave} disabled={saving}>
            {saving ? 'Saving...' : 'Save Changes'}
          </Button>
        </div>
      </div>
    </div>
  );
}
```

---

## 📚 Additional Resources

For complete documentation, see:
- [Main API Documentation](./LARAVEL_API_DOCUMENTATION.md)
- [Refactoring Summary](./REFACTORING_SUMMARY.md)

---

**Last Updated**: November 20, 2025  
**Status**: Production Ready ✅

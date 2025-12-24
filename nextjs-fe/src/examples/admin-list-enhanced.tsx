/**
 * Integration Example: Admin List Page with All Features
 * This example shows how to integrate all components into an existing page
 */

'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-react';
import { useApiData } from '@/hooks/useApiData';
import {
  BulkActions,
  ImportExport,
  type BulkAction,
} from '@/components/crud';
import {
  AdvancedSearch,
  SavedFilters,
  Can,
  type SearchField,
} from '@/components/advanced';
import { adminService } from '@/services/modules';
import { ENDPOINTS } from '@/constants/api-endpoints';
import type { AdminMst } from '@/types/models';
import toast from 'react-hot-toast';

export default function AdminsPageEnhanced() {
  const router = useRouter();
  const [selectedIds, setSelectedIds] = useState<number[]>([]);

  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(20);
  const [filters, setFilters] = useState({});

  const {
    data: admins,
    loading: isLoading,
    pagination,
    refetch,
  } = useApiData<AdminMst>(`${ENDPOINTS.MASTER.ADMIN}/list`, {
    page,
    per_page: perPage,
    filters,
  });

  const setPagination = (newPagination: any) => {
    setPage(newPagination.page);
    if (newPagination.perPage) setPerPage(newPagination.perPage);
  };

  // Search fields configuration
  const searchFields: SearchField[] = [
    { key: 'email', label: 'Email', type: 'text' },
    { key: 'user_name', label: 'Username', type: 'text' },
    { key: 'first_name', label: 'First Name', type: 'text' },
    { key: 'last_name', label: 'Last Name', type: 'text' },
    {
      key: 'is_active',
      label: 'Status',
      type: 'select',
      options: [
        { value: '1', label: 'Active' },
        { value: '0', label: 'Inactive' },
      ],
    },
  ];

  // Bulk actions configuration
  const bulkActions: BulkAction[] = [
    {
      label: 'Delete Selected',
      variant: 'destructive',
      onClick: async (ids) => {
        await adminService.bulkDelete(ids);
        toast.success('Deleted successfully');
        refetch();
        setSelectedIds([]);
      },
      confirmMessage: `Are you sure you want to delete ${selectedIds.length} admin(s)?`,
      confirmTitle: 'Delete Admins',
    },
    {
      label: 'Activate Selected',
      variant: 'default',
      onClick: async (ids) => {
        // Implement bulk activate
        toast.success('Activated successfully');
        refetch();
      },
    },
    {
      label: 'Deactivate Selected',
      variant: 'outline',
      onClick: async (ids) => {
        // Implement bulk deactivate
        toast.success('Deactivated successfully');
        refetch();
      },
    },
  ];

  const handleExport = async (format: 'csv' | 'excel' | 'json') => {
    try {
      const blob = await adminService.export(filters);
      const url = window.URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `admins.${format}`;
      document.body.appendChild(a);
      a.click();
      window.URL.revokeObjectURL(url);
      document.body.removeChild(a);
      toast.success('Exported successfully');
    } catch (error) {
      toast.error('Export failed');
    }
  };

  const handleImport = async (file: File) => {
    try {
      await adminService.import(file);
      toast.success('Import successful');
      refetch();
    } catch (error) {
      toast.error('Import failed');
    }
  };

  const handleSearch = (criteria: any[]) => {
    // Convert search criteria to filters
    const newFilters = criteria.reduce((acc, c) => {
      acc[c.field] = c.value;
      return acc;
    }, {});
    setFilters({ ...filters, ...newFilters });
  };

  return (
    <div className="flex h-screen flex-col">
      {/* Header with Actions */}
      <div className="flex items-center justify-between border-b bg-background px-6 py-4">
        <h1 className="text-2xl font-bold">Admin Management</h1>
        
        <div className="flex items-center gap-2">
          {/* Advanced Search */}
          <AdvancedSearch fields={searchFields} onSearch={handleSearch} />
          
          {/* Saved Filters */}
          <SavedFilters
            currentFilters={filters}
            onApplyFilter={setFilters}
            storageKey="admin_filters"
          />
          
          {/* Import/Export */}
          <Can module="admins" action="export">
            <ImportExport
              onExport={handleExport}
              onImport={handleImport}
              moduleName="admins"
            />
          </Can>
          
          {/* Create Button */}
          <Can module="admins" action="create">
            <Button onClick={() => router.push('/admin/admins/create')} className="gap-2">
              <Plus className="h-4 w-4" />
              Add Admin
            </Button>
          </Can>
        </div>
      </div>

      {/* Content */}
      <div className="flex-1 overflow-auto p-6">
        <div className="space-y-4">
          {/* Bulk Actions */}
          {selectedIds.length > 0 && (
            <BulkActions
              selectedIds={selectedIds}
              onClearSelection={() => setSelectedIds([])}
              actions={bulkActions}
            />
          )}

          {/* Table */}
          <div className="rounded-md border">
            <table className="w-full">
              <thead className="bg-muted/50">
                <tr>
                  <th className="w-12 p-4">
                    <input
                      type="checkbox"
                      checked={selectedIds.length === admins.length && admins.length > 0}
                      onChange={() => {
                        if (selectedIds.length === admins.length) {
                          setSelectedIds([]);
                        } else {
                          setSelectedIds(admins.map((a) => a.id));
                        }
                      }}
                      className="rounded border-gray-300"
                    />
                  </th>
                  <th className="p-4 text-left font-medium">Email</th>
                  <th className="p-4 text-left font-medium">Username</th>
                  <th className="p-4 text-left font-medium">Name</th>
                  <th className="p-4 text-left font-medium">Status</th>
                  <th className="p-4 text-left font-medium">Actions</th>
                </tr>
              </thead>
              <tbody>
                {isLoading ? (
                  <tr>
                    <td colSpan={6} className="p-8 text-center text-muted-foreground">
                      Loading...
                    </td>
                  </tr>
                ) : admins.length === 0 ? (
                  <tr>
                    <td colSpan={6} className="p-8 text-center text-muted-foreground">
                      No admins found
                    </td>
                  </tr>
                ) : (
                  admins.map((admin) => (
                    <tr key={admin.id} className="border-t hover:bg-muted/50">
                      <td className="p-4">
                        <input
                          type="checkbox"
                          checked={selectedIds.includes(admin.id)}
                          onChange={() => {
                            setSelectedIds((prev) =>
                              prev.includes(admin.id)
                                ? prev.filter((id) => id !== admin.id)
                                : [...prev, admin.id]
                            );
                          }}
                          className="rounded border-gray-300"
                        />
                      </td>
                      <td className="p-4">{admin.email}</td>
                      <td className="p-4">{admin.user_name}</td>
                      <td className="p-4">
                        {admin.first_name} {admin.last_name}
                      </td>
                      <td className="p-4">
                        <span
                          className={`inline-flex rounded-full px-2 py-1 text-xs font-semibold ${
                            admin.is_active
                              ? 'bg-green-100 text-green-800'
                              : 'bg-red-100 text-red-800'
                          }`}
                        >
                          {admin.is_active ? 'Active' : 'Inactive'}
                        </span>
                      </td>
                      <td className="p-4">
                        <Can module="admins" action="update">
                          <Button
                            variant="outline"
                            size="sm"
                            onClick={() => router.push(`/admin/admins/${admin.id}/edit`)}
                          >
                            Edit
                          </Button>
                        </Can>
                      </td>
                    </tr>
                  ))
                )}
              </tbody>
            </table>
          </div>

          {/* Pagination */}
          {pagination.total > 0 && (
            <div className="flex items-center justify-between">
              <div className="text-sm text-muted-foreground">
                Showing {(pagination.currentPage - 1) * pagination.perPage + 1} to{' '}
                {Math.min(pagination.currentPage * pagination.perPage, pagination.total)} of{' '}
                {pagination.total} results
              </div>
              <div className="flex gap-2">
                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => setPagination({ page: pagination.currentPage - 1 })}
                  disabled={pagination.currentPage === 1}
                >
                  Previous
                </Button>
                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => setPagination({ page: pagination.currentPage + 1 })}
                  disabled={pagination.currentPage * pagination.perPage >= pagination.total}
                >
                  Next
                </Button>
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}

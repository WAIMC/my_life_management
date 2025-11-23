'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { useApiData } from '@/hooks/useApiData';
import { useCrud } from '@/hooks/useCrud';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { DataTable, type Column } from '@/components/data-table/data-table';
import { Pagination } from '@/components/data-table/pagination';
import { FilterPanel, type FilterField } from '@/components/data-table/filter-panel';
import { ConfirmDialog } from '@/components/ui/confirm-dialog';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { AdvancedSearch, type SearchField, type SearchCriteria } from '@/components/advanced/advanced-search';
import { SavedFilters } from '@/components/advanced/saved-filters';
import { BulkActions, type BulkAction } from '@/components/crud/bulk-actions';
import { ImportExport } from '@/components/crud/import-export';
import { Can } from '@/components/advanced/permission-control';
import { Trash2, CheckCircle, XCircle } from 'lucide-react';
import type { AdminMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, StatusLabels, Gender, GenderLabels } from '@/lib/types/enums';

export default function AdminListPage() {
  const router = useRouter();
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(20);
  const [filters, setFilters] = useState({});
  const [sortBy, setSortBy] = useState('created_at');
  const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('desc');
  const [selectedIds, setSelectedIds] = useState<number[]>([]);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [deleteIds, setDeleteIds] = useState<number[]>([]);
  const [advancedCriteria, setAdvancedCriteria] = useState<SearchCriteria[]>([]);

  const { data, loading, pagination, refetch } = useApiData<AdminMst>(
    ENDPOINTS.MASTER.ADMIN,
    { page, per_page: perPage, filters, sort_by: sortBy, sort_order: sortOrder }
  );

  const { remove, loading: deleteLoading } = useCrud<AdminMst>(ENDPOINTS.MASTER.ADMIN);

  const handleDelete = async (ids: number[]) => {
    setDeleteIds(ids);
    setDeleteDialogOpen(true);
  };

  const confirmDelete = async () => {
    await remove(deleteIds);
    setSelectedIds([]);
    setDeleteIds([]);
    refetch();
  };

  const handleSort = (column: string) => {
    if (sortBy === column) {
      setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc');
    } else {
      setSortBy(column);
      setSortOrder('asc');
    }
  };

  const columns: Column<AdminMst>[] = [
    {
      key: 'id',
      label: 'ID',
      sortable: true,
    },
    {
      key: 'email',
      label: 'Email',
      sortable: true,
    },
    {
      key: 'user_name',
      label: 'Username',
      sortable: true,
    },
    {
      key: 'name',
      label: 'Name',
      render: (admin) => `${admin.first_name} ${admin.last_name}`,
    },
    {
      key: 'gender',
      label: 'Gender',
      render: (admin) => GenderLabels[admin.gender as Gender] || 'Unknown',
    },
    {
      key: 'status',
      label: 'Status',
      sortable: true,
      render: (admin) => (
        <Badge variant={admin.is_active ? 'default' : 'secondary'}>
          {admin.is_active ? 'Active' : 'Inactive'}
        </Badge>
      ),
    },
    {
      key: 'updated_at',
      label: 'Updated',
      sortable: true,
    },
  ];

  const filterFields: FilterField[] = [
    {
      key: 'email',
      label: 'Email',
      type: 'text',
      placeholder: 'Search by email...',
    },
    {
      key: 'user_name',
      label: 'Username',
      type: 'text',
      placeholder: 'Search by username...',
    },
    {
      key: 'first_name',
      label: 'First Name',
      type: 'text',
      placeholder: 'Search by first name...',
    },
    {
      key: 'status',
      label: 'Status',
      type: 'select',
      options: [
        { value: Status.ACTIVE, label: StatusLabels[Status.ACTIVE] },
        { value: Status.INACTIVE, label: StatusLabels[Status.INACTIVE] },
      ],
    },
    {
      key: 'is_active',
      label: 'Active',
      type: 'boolean',
    },
    {
      key: 'gender',
      label: 'Gender',
      type: 'select',
      options: [
        { value: Gender.MALE, label: GenderLabels[Gender.MALE] },
        { value: Gender.FEMALE, label: GenderLabels[Gender.FEMALE] },
        { value: Gender.OTHER, label: GenderLabels[Gender.OTHER] },
      ],
    },
  ];

  // Advanced search fields
  const searchFields: SearchField[] = [
    { key: 'email', label: 'Email', type: 'text' },
    { key: 'user_name', label: 'Username', type: 'text' },
    { key: 'first_name', label: 'First Name', type: 'text' },
    { key: 'last_name', label: 'Last Name', type: 'text' },
    { 
      key: 'status', 
      label: 'Status', 
      type: 'select',
      options: [
        { value: '1', label: 'Active' },
        { value: '2', label: 'Inactive' },
      ]
    },
    {
      key: 'gender',
      label: 'Gender',
      type: 'select',
      options: [
        { value: '1', label: 'Male' },
        { value: '2', label: 'Female' },
        { value: '3', label: 'Other' },
      ]
    },
    { key: 'created_at', label: 'Created Date', type: 'date' },
  ];

  // Bulk actions
  const bulkActions: BulkAction[] = [
    {
      label: 'Delete Selected',
      icon: <Trash2 className="h-4 w-4" />,
      variant: 'destructive',
      onClick: async (ids) => {
        await remove(ids);
        refetch();
      },
      confirmMessage: `Are you sure you want to delete ${selectedIds.length} admin(s)? This action cannot be undone.`,
      confirmTitle: 'Delete Admins',
    },
    {
      label: 'Activate Selected',
      icon: <CheckCircle className="h-4 w-4" />,
      onClick: async (ids) => {
        // TODO: Implement bulk activate
        console.log('Activate:', ids);
        refetch();
      },
    },
    {
      label: 'Deactivate Selected',
      icon: <XCircle className="h-4 w-4" />,
      onClick: async (ids) => {
        // TODO: Implement bulk deactivate
        console.log('Deactivate:', ids);
        refetch();
      },
    },
  ];

  const handleAdvancedSearch = (criteria: SearchCriteria[]) => {
    setAdvancedCriteria(criteria);
    // Convert criteria to filters
    const newFilters = criteria.reduce((acc, c) => ({
      ...acc,
      [c.field]: c.value
    }), {});
    setFilters(newFilters);
    setPage(1);
  };

  const handleImport = async (importedData: any[]) => {
    // TODO: Implement import logic
    console.log('Import data:', importedData);
    refetch();
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Admin Management"
        description="Manage system administrators"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Admins', isActive: true },
        ]}
        action={
          <Can module="admin" action="create">
            <Button onClick={() => router.push('/admin/admins/create')}>
              Create Admin
            </Button>
          </Can>
        }
      />

      <div className="mt-6 space-y-4">
        {/* Advanced Search & Filters */}
        <div className="flex gap-2">
          <AdvancedSearch
            fields={searchFields}
            onSearch={handleAdvancedSearch}
          />
          <SavedFilters
            currentFilters={filters}
            onLoad={(loadedFilters) => {
              setFilters(loadedFilters);
              setPage(1);
            }}
            filterKey="admin-filters"
          />
          <Can module="admin" action="export">
            <ImportExport
              data={data}
              onImport={handleImport}
              filename="admins-export"
            />
          </Can>
        </div>

        <FilterPanel
          filters={filters}
          onFilterChange={(newFilters) => {
            setFilters(newFilters);
            setPage(1);
          }}
          onReset={() => {
            setFilters({});
            setPage(1);
          }}
          fields={filterFields}
        />

        {/* Bulk Actions */}
        <Can module="admin" action="delete">
          <BulkActions
            selectedIds={selectedIds}
            onClearSelection={() => setSelectedIds([])}
            actions={bulkActions}
            isLoading={loading}
          />
        </Can>

        <DataTable
          data={data}
          columns={columns}
          loading={loading}
          selectedIds={selectedIds}
          onSelectionChange={setSelectedIds}
          onSort={handleSort}
          sortBy={sortBy}
          sortOrder={sortOrder}
          onEdit={(id) => router.push(`/admin/admins/${id}/edit`)}
          onDelete={(id) => handleDelete([id])}
          renderActions={(admin) => (
            <div className="flex gap-2">
              <Can module="admin" action="update">
                <Button
                  size="sm"
                  variant="outline"
                  onClick={() => router.push(`/admin/admins/${admin.id}/edit`)}
                >
                  Edit
                </Button>
              </Can>
              <Can module="admin" action="delete">
                <Button
                  size="sm"
                  variant="destructive"
                  onClick={() => handleDelete([admin.id])}
                >
                  Delete
                </Button>
              </Can>
            </div>
          )}
        />

        <Pagination
          pagination={pagination}
          page={page}
          onPageChange={setPage}
          perPage={perPage}
          onPerPageChange={(newPerPage) => {
            setPerPage(newPerPage);
            setPage(1);
          }}
        />
      </div>

      <ConfirmDialog
        open={deleteDialogOpen}
        onOpenChange={setDeleteDialogOpen}
        title="Delete Admin(s)"
        description={`Are you sure you want to delete ${deleteIds.length} admin(s)? This action cannot be undone.`}
        onConfirm={confirmDelete}
        confirmText="Delete"
        variant="destructive"
      />
    </AdminLayout>
  );
}

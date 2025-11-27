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
import type { UserMgmt } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, StatusLabels, Gender, GenderLabels } from '@/lib/types/enums';
import { AdvancedSearch, type SearchField, type SearchCriteria } from '@/components/advanced/advanced-search';
import { SavedFilters } from '@/components/advanced/saved-filters';
import { BulkActions, type BulkAction } from '@/components/crud/bulk-actions';
import { ImportExport } from '@/components/crud/import-export';
import { Can } from '@/components/advanced/permission-control';
import { Trash2, CheckCircle, XCircle } from 'lucide-react';

export default function UsersPage() {
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

  const { data, loading, pagination, refetch } = useApiData<UserMgmt>(
    ENDPOINTS.MANAGEMENT.USER,
    { page, per_page: perPage, filters, sort_by: sortBy, sort_order: sortOrder }
  );

  const { remove, loading: deleteLoading } = useCrud<UserMgmt>(ENDPOINTS.MANAGEMENT.USER);

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

  const columns: Column<UserMgmt>[] = [
    { key: 'id', label: 'ID', sortable: true },
    { key: 'user_name', label: 'Username', sortable: true },
    {
      key: 'first_name',
      label: 'Name',
      sortable: true,
      render: (user) => `${user.first_name} ${user.last_name}`,
    },
    { key: 'email', label: 'Email', sortable: true },
    {
      key: 'gender',
      label: 'Gender',
      render: (user) => GenderLabels[user.gender as Gender] || 'Unknown',
    },
    {
      key: 'status',
      label: 'Status',
      sortable: true,
      render: (user) => (
        <Badge variant={user.is_active ? 'default' : 'secondary'}>
          {user.is_active ? 'Active' : 'Inactive'}
        </Badge>
      ),
    },
    { key: 'updated_at', label: 'Updated', sortable: true },
  ];

  const filterFields: FilterField[] = [
    { key: 'user_name', label: 'Username', type: 'text', placeholder: 'Search by username...' },
    { key: 'email', label: 'Email', type: 'text', placeholder: 'Search by email...' },
    { key: 'first_name', label: 'First Name', type: 'text', placeholder: 'Search by first name...' },
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
    {
      key: 'status',
      label: 'Status',
      type: 'select',
      options: [
        { value: Status.ACTIVE, label: StatusLabels[Status.ACTIVE] },
        { value: Status.INACTIVE, label: StatusLabels[Status.INACTIVE] },
      ],
    },
    { key: 'is_active', label: 'Active', type: 'boolean' },
  ];
  const searchFields: SearchField[] = [{ key: 'user_name', label: 'Username', type: 'text' }, { key: 'email', label: 'Email', type: 'text' }, { key: 'first_name', label: 'Name', type: 'text' }, { key: 'status', label: 'Status', type: 'select', options: [{ value: '1', label: 'Active' }, { value: '2', label: 'Inactive' }] }, { key: 'created_at', label: 'Created Date', type: 'date' }];
  const bulkActions: BulkAction[] = [{ label: 'Delete Selected', icon: <Trash2 className="h-4 w-4" />, variant: 'destructive', onClick: async (ids) => { await remove(ids); refetch(); }, confirmMessage: `Delete ${selectedIds.length} user(s)?`, confirmTitle: 'Delete Users' }, { label: 'Activate Selected', icon: <CheckCircle className="h-4 w-4" />, onClick: async (ids) => { refetch(); } }, { label: 'Deactivate Selected', icon: <XCircle className="h-4 w-4" />, onClick: async (ids) => { refetch(); } }];
  const handleAdvancedSearch = (criteria: SearchCriteria[]) => { setAdvancedCriteria(criteria); const newFilters = criteria.reduce((acc, c) => ({ ...acc, [c.field]: c.value }), {}); setFilters(newFilters); setPage(1); };
  const handleImport = async (importedData: any[]) => { refetch(); };

  return (
    <AdminLayout>
      <PageHeader
        title="Users Management"
        description="Manage all users in your system"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Users', isActive: true },
        ]}
        action={<Can module="user" action="create"><Button onClick={() => router.push('/admin/users/create')}>Create User</Button></Can>}
      />

      <div className="mt-6 space-y-4"><div className="flex gap-2"><AdvancedSearch fields={searchFields} onSearch={handleAdvancedSearch} /><SavedFilters currentFilters={filters} onLoad={(f) => { setFilters(f); setPage(1); }} filterKey="user-filters" /><Can module="user" action="export"><ImportExport data={data} onImport={handleImport} filename="users-export" /></Can></div><FilterPanel
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

        <Can module="user" action="delete"><BulkActions selectedIds={selectedIds} onClearSelection={() => setSelectedIds([])} actions={bulkActions} isLoading={loading} /></Can>

        <DataTable
          data={data}
          columns={columns}
          loading={loading}
          selectedIds={selectedIds}
          onSelectionChange={setSelectedIds}
          onSort={handleSort}
          sortBy={sortBy}
          sortOrder={sortOrder}
          onEdit={(id) => router.push(`/admin/users/${id}/edit`)}
          onDelete={(id) => handleDelete([id])}
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
        title="Delete User(s)"
        description={`Are you sure you want to delete ${deleteIds.length} user(s)? This action cannot be undone.`}
        onConfirm={confirmDelete}
        confirmText="Delete"
        variant="destructive"
      />
    </AdminLayout>
  );
}

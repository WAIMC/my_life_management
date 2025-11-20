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
import type { ApiMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, StatusLabels } from '@/lib/types/enums';

export default function ApiListPage() {
  const router = useRouter();
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(20);
  const [filters, setFilters] = useState({});
  const [sortBy, setSortBy] = useState('created_at');
  const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('desc');
  const [selectedIds, setSelectedIds] = useState<number[]>([]);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [deleteIds, setDeleteIds] = useState<number[]>([]);

  const { data, loading, pagination, refetch } = useApiData<ApiMst>(
    ENDPOINTS.MASTER.API,
    { page, per_page: perPage, filters, sort_by: sortBy, sort_order: sortOrder }
  );

  const { remove, loading: deleteLoading } = useCrud<ApiMst>(ENDPOINTS.MASTER.API);

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

  const columns: Column<ApiMst>[] = [
    { key: 'id', label: 'ID', sortable: true },
    { key: 'name', label: 'Name', sortable: true },
    { key: 'description', label: 'Description' },
    {
      key: 'status',
      label: 'Status',
      sortable: true,
      render: (api) => (
        <Badge variant={api.is_active ? 'default' : 'secondary'}>
          {api.is_active ? 'Active' : 'Inactive'}
        </Badge>
      ),
    },
    { key: 'updated_at', label: 'Updated', sortable: true },
  ];

  const filterFields: FilterField[] = [
    { key: 'name', label: 'Name', type: 'text', placeholder: 'Search by name...' },
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

  return (
    <AdminLayout>
      <PageHeader
        title="API Management"
        description="Manage system APIs and endpoints"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'APIs', isActive: true },
        ]}
        action={
          <Button onClick={() => router.push('/admin/apis/create')}>
            Create API
          </Button>
        }
      />

      <div className="mt-6 space-y-4">
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

        {selectedIds.length > 0 && (
          <div className="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-between border border-blue-200 dark:border-blue-800">
            <span className="text-sm font-medium text-blue-900 dark:text-blue-100">
              {selectedIds.length} API(s) selected
            </span>
            <Button
              variant="destructive"
              size="sm"
              onClick={() => handleDelete(selectedIds)}
              disabled={deleteLoading}
            >
              Delete Selected
            </Button>
          </div>
        )}

        <DataTable
          data={data}
          columns={columns}
          loading={loading}
          selectedIds={selectedIds}
          onSelectionChange={setSelectedIds}
          onSort={handleSort}
          sortBy={sortBy}
          sortOrder={sortOrder}
          onEdit={(id) => router.push(`/admin/apis/${id}/edit`)}
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
        title="Delete API(s)"
        description={`Are you sure you want to delete ${deleteIds.length} API(s)? This action cannot be undone.`}
        onConfirm={confirmDelete}
        confirmText="Delete"
        variant="destructive"
      />
    </AdminLayout>
  );
}

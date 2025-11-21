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
import type { BannerMgmt } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, StatusLabels } from '@/lib/types/enums';
import Image from 'next/image';

export default function BannerListPage() {
  const router = useRouter();
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(20);
  const [filters, setFilters] = useState({});
  const [sortBy, setSortBy] = useState('rank_order');
  const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('asc');
  const [selectedIds, setSelectedIds] = useState<number[]>([]);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [deleteIds, setDeleteIds] = useState<number[]>([]);

  const { data, loading, pagination, refetch } = useApiData<BannerMgmt>(
    ENDPOINTS.MANAGEMENT.BANNER,
    { page, per_page: perPage, filters, sort_by: sortBy, sort_order: sortOrder }
  );

  const { remove, loading: deleteLoading } = useCrud<BannerMgmt>(ENDPOINTS.MANAGEMENT.BANNER);

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

  const columns: Column<BannerMgmt>[] = [
    { key: 'id', label: 'ID', sortable: true },
    {
      key: 'image_url',
      label: 'Image',
      render: (banner) => (
        <div className="relative w-20 h-12 rounded overflow-hidden">
          {banner.image_url ? (
            <Image
              src={banner.image_url}
              alt={banner.title}
              fill
              className="object-cover"
              unoptimized
            />
          ) : (
            <div className="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs text-gray-400">
              No image
            </div>
          )}
        </div>
      ),
    },
    { key: 'title', label: 'Title', sortable: true },
    { 
      key: 'link_url', 
      label: 'Link',
      render: (banner) => (
        banner.link_url ? (
          <a 
            href={banner.link_url} 
            target="_blank" 
            rel="noopener noreferrer"
            className="text-blue-600 hover:underline truncate max-w-xs block"
          >
            {banner.link_url}
          </a>
        ) : (
          <span className="text-gray-400">-</span>
        )
      ),
    },
    { key: 'rank_order', label: 'Order', sortable: true },
    {
      key: 'status',
      label: 'Status',
      sortable: true,
      render: (banner) => (
        <Badge variant={banner.is_active ? 'default' : 'secondary'}>
          {banner.is_active ? 'Active' : 'Inactive'}
        </Badge>
      ),
    },
    { key: 'updated_at', label: 'Updated', sortable: true },
  ];

  const filterFields: FilterField[] = [
    {
      key: 'title',
      label: 'Title',
      type: 'text',
      placeholder: 'Search by title...',
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
  ];

  return (
    <AdminLayout>
      <PageHeader
        title="Banner Management"
        description="Manage website banners and promotional images"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Banners', isActive: true },
        ]}
        action={
          <Button onClick={() => router.push('/admin/banners/create')}>
            Create Banner
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
              {selectedIds.length} banner(s) selected
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
          onEdit={(id) => router.push(`/admin/banners/${id}/edit`)}
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
        title="Delete Banner(s)"
        description={`Are you sure you want to delete ${deleteIds.length} banner(s)? This action cannot be undone.`}
        onConfirm={confirmDelete}
        confirmText="Delete"
        variant="destructive"
      />
    </AdminLayout>
  );
}

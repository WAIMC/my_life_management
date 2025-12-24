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
import type { SliderMgmt } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, StatusLabels } from '@/lib/types/enums';
import Image from 'next/image';
import { AdvancedSearch, type SearchField, type SearchCriteria } from '@/components/advanced/advanced-search';
import { SavedFilters } from '@/components/advanced/saved-filters';
import { BulkActions, type BulkAction } from '@/components/crud/bulk-actions';
import { ImportExport } from '@/components/crud/import-export';
import { Trash2, CheckCircle, XCircle } from 'lucide-react';

export default function SliderListPage() {
  const router = useRouter();
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(20);
  const [filters, setFilters] = useState({});
  const [sortBy, setSortBy] = useState('rank_order');
  const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('asc');
  const [selectedIds, setSelectedIds] = useState<number[]>([]);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [deleteIds, setDeleteIds] = useState<number[]>([]);
  const [advancedCriteria, setAdvancedCriteria] = useState<SearchCriteria[]>([]);

  const { data, loading, pagination, refetch } = useApiData<SliderMgmt>(
    ENDPOINTS.MANAGEMENT.SLIDER,
    { page, per_page: perPage, filters, sort_by: sortBy, sort_order: sortOrder }
  );

  const { remove, loading: deleteLoading } = useCrud<SliderMgmt>(ENDPOINTS.MANAGEMENT.SLIDER);

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

  const columns: Column<SliderMgmt>[] = [
    { key: 'id', label: 'ID', sortable: true },
    {
      key: 'image_url',
      label: 'Image',
      render: (slider) => (
        <div className="relative w-20 h-12 rounded overflow-hidden">
          {slider.image_url ? (
            <Image
              src={slider.image_url}
              alt={slider.title}
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
      render: (slider) => (
        slider.link_url ? (
          <a 
            href={slider.link_url} 
            target="_blank" 
            rel="noopener noreferrer"
            className="text-blue-600 hover:underline truncate max-w-xs block"
          >
            {slider.link_url}
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
      render: (slider) => (
        <Badge variant={slider.is_active ? 'default' : 'secondary'}>
          {slider.is_active ? 'Active' : 'Inactive'}
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
  const searchFields: SearchField[] = [{ key: 'title', label: 'Title', type: 'text' }, { key: 'link_url', label: 'Link', type: 'text' }, { key: 'status', label: 'Status', type: 'select', options: [{ value: '1', label: 'Active' }, { value: '2', label: 'Inactive' }] }, { key: 'created_at', label: 'Created Date', type: 'date' }];
  const bulkActions: BulkAction[] = [{ label: 'Delete Selected', icon: <Trash2 className="h-4 w-4" />, variant: 'destructive', onClick: async (ids) => { await remove(ids); refetch(); }, confirmMessage: `Delete ${selectedIds.length} slider(s)?`, confirmTitle: 'Delete Sliders' }, { label: 'Activate Selected', icon: <CheckCircle className="h-4 w-4" />, onClick: async (ids) => { refetch(); } }, { label: 'Deactivate Selected', icon: <XCircle className="h-4 w-4" />, onClick: async (ids) => { refetch(); } }];
  const handleAdvancedSearch = (criteria: SearchCriteria[]) => { setAdvancedCriteria(criteria); const newFilters = criteria.reduce((acc, c) => ({ ...acc, [c.field]: c.value }), {}); setFilters(newFilters); setPage(1); };
  const handleImport = async (file: File, format: string) => { refetch(); };

  return (
    <AdminLayout>
      <PageHeader
        title="Slider Management"
        description="Manage website sliders and promotional images"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Sliders', isActive: true },
        ]}
        action={<Button onClick={() => router.push('/admin/sliders/create')}>Create Slider</Button>}
      />

      <div className="mt-6 space-y-4"><div className="flex gap-2"><AdvancedSearch fields={searchFields} onSearch={handleAdvancedSearch} /><SavedFilters currentFilters={filters} onApplyFilter={(f) => { setFilters(f); setPage(1); }} storageKey="slider-filters" /><ImportExport  onImport={handleImport}  /></div><FilterPanel
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

        <BulkActions selectedIds={selectedIds} onClearSelection={() => setSelectedIds([])} actions={bulkActions} isLoading={loading} />

        <DataTable data={data}
          
          columns={columns}
          loading={loading}
          selectedIds={selectedIds}
          onSelectionChange={setSelectedIds}
          onSort={handleSort}
          sortBy={sortBy}
          sortOrder={sortOrder}
          onEdit={(id) => router.push(`/admin/sliders/${id}/edit`)}
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
        title="Delete Slider(s)"
        description={`Are you sure you want to delete ${deleteIds.length} slider(s)? This action cannot be undone.`}
        onConfirm={confirmDelete}
        confirmText="Delete"
        variant="destructive"
      />
    </AdminLayout>
  );
}

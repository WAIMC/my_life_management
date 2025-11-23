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
import type { OriginalTranslatorMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, StatusLabels } from '@/lib/types/enums';
import { AdvancedSearch, type SearchField, type SearchCriteria } from '@/components/advanced/advanced-search';
import { SavedFilters } from '@/components/advanced/saved-filters';
import { BulkActions, type BulkAction } from '@/components/crud/bulk-actions';
import { ImportExport } from '@/components/crud/import-export';
import { Can } from '@/components/advanced/permission-control';
import { Trash2, CheckCircle, XCircle } from 'lucide-react';

export default function OriginalTranslatorListPage() {
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

  const { data, loading, pagination, refetch } = useApiData<OriginalTranslatorMst>(
    ENDPOINTS.MASTER.ORIGINAL_TRANSLATOR,
    { page, per_page: perPage, filters, sort_by: sortBy, sort_order: sortOrder }
  );

  const { remove, loading: deleteLoading } = useCrud<OriginalTranslatorMst>(ENDPOINTS.MASTER.ORIGINAL_TRANSLATOR);

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

  const columns: Column<OriginalTranslatorMst>[] = [
    { key: 'id', label: 'ID', sortable: true },
    { key: 'name', label: 'Name', sortable: true },
    { key: 'description', label: 'Description' },
    {
      key: 'status',
      label: 'Status',
      sortable: true,
      render: (item) => (
        <Badge variant={item.is_active ? 'default' : 'secondary'}>
          {item.is_active ? 'Active' : 'Inactive'}
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
  const searchFields: SearchField[] = [{ key: 'name', label: 'Name', type: 'text' }, { key: 'description', label: 'Description', type: 'text' }, { key: 'status', label: 'Status', type: 'select', options: [{ value: '1', label: 'Active' }, { value: '2', label: 'Inactive' }] }, { key: 'created_at', label: 'Created Date', type: 'date' }];
  const bulkActions: BulkAction[] = [{ label: 'Delete Selected', icon: <Trash2 className="h-4 w-4" />, variant: 'destructive', onClick: async (ids) => { await remove(ids); refetch(); }, confirmMessage: `Delete ${selectedIds.length} translator(s)?`, confirmTitle: 'Delete Translators' }, { label: 'Activate Selected', icon: <CheckCircle className="h-4 w-4" />, onClick: async (ids) => { console.log('Activate:', ids); refetch(); } }, { label: 'Deactivate Selected', icon: <XCircle className="h-4 w-4" />, onClick: async (ids) => { console.log('Deactivate:', ids); refetch(); } }];
  const handleAdvancedSearch = (criteria: SearchCriteria[]) => { setAdvancedCriteria(criteria); const newFilters = criteria.reduce((acc, c) => ({ ...acc, [c.field]: c.value }), {}); setFilters(newFilters); setPage(1); };
  const handleImport = async (importedData: any[]) => { console.log('Import:', importedData); refetch(); };

  return (
    <AdminLayout>
      <PageHeader
        title="Original Translator Management"
        description="Manage original translators"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Original Translators', isActive: true },
        ]}
        action={<Can module="original_translator" action="create"><Button onClick={() => router.push('/admin/original-translators/create')}>Create Translator</Button></Can>}
      />

      <div className="mt-6 space-y-4"><div className="flex gap-2"><AdvancedSearch fields={searchFields} onSearch={handleAdvancedSearch} /><SavedFilters currentFilters={filters} onLoad={(f) => { setFilters(f); setPage(1); }} filterKey="original-translator-filters" /><Can module="original_translator" action="export"><ImportExport data={data} onImport={handleImport} filename="original-translators-export" /></Can></div><FilterPanel
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

        <Can module="original_translator" action="delete"><BulkActions selectedIds={selectedIds} onClearSelection={() => setSelectedIds([])} actions={bulkActions} isLoading={loading} /></Can>

        <DataTable
          data={data}
          columns={columns}
          loading={loading}
          selectedIds={selectedIds}
          onSelectionChange={setSelectedIds}
          onSort={handleSort}
          sortBy={sortBy}
          sortOrder={sortOrder}
          onEdit={(id) => router.push(`/admin/original-translators/${id}/edit`)}
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
        title="Delete Translator(s)"
        description={`Are you sure you want to delete ${deleteIds.length} translator(s)? This action cannot be undone.`}
        onConfirm={confirmDelete}
        confirmText="Delete"
        variant="destructive"
      />
    </AdminLayout>
  );
}

'use client';

import { useState } from 'react';
import { useApiData } from '@/shared/hooks/useApiData';
import { useCrud } from '@/shared/hooks/useCrud';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { DataTable, type Column } from '@/components/common/data-table/data-table';
import { Pagination } from '@/components/common/data-table/pagination';
import { FilterPanel, type FilterField } from '@/components/common/data-table/filter-panel';
import { ConfirmDialog } from '@/components/ui/confirm-dialog';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import type { SettingLinkMgmt } from '@/shared/types/api';
import { API_ENDPOINTS } from '@/shared/api';
import { 
  SORT_ORDER, 
  type SortOrder, 
  PAGINATION, 
  ADMIN_ROUTES 
} from '@/shared/constants';
import { IsActive, IsActiveLabels } from '@/shared/enums/enums';
import { AdvancedSearch, type SearchField, type SearchCriteria } from '@/components/common/advanced-search';
import { SavedFilters } from '@/components/common/saved-filters';
import { BulkActions, type BulkAction } from '@/components/common/bulk-actions';
import { ImportExport } from '@/components/common/import-export';
import { Trash2, CheckCircle, XCircle, Plus } from 'lucide-react';
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from '@/components/ui/dialog';
import { SettingLinkForm } from '@/components/forms/setting-link-form';
import { useTranslations } from 'next-intl';

export default function SettingLinkListPage() {
  const [page, setPage] = useState<number>(PAGINATION.DEFAULT_PAGE);
  const [perPage, setPerPage] = useState<number>(PAGINATION.DEFAULT_PER_PAGE);
  const [filters, setFilters] = useState({});
  const [sortBy, setSortBy] = useState('rank_order');
  const [sortOrder, setSortOrder] = useState<SortOrder>(SORT_ORDER.ASC);
  const [selectedIds, setSelectedIds] = useState<number[]>([]);
  
  // Dialog states
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [deleteIds, setDeleteIds] = useState<number[]>([]);
  const [formDialogOpen, setFormDialogOpen] = useState(false);
  const [editingLink, setEditingLink] = useState<SettingLinkMgmt | null>(null);

  const [advancedCriteria, setAdvancedCriteria] = useState<SearchCriteria[]>([]);

  const tCommon = useTranslations('common');
  const tEntities = useTranslations('entities');
  const tFields = useTranslations('fields');
  const tManagement = useTranslations('management');
  const tCrud = useTranslations('crud');
  const tBulkActions = useTranslations('bulkActions');

  const { data, loading, pagination, refetch } = useApiData<SettingLinkMgmt>(
    API_ENDPOINTS.MANAGEMENT.SETTING_LINK,
    { page, per_page: perPage, filters, sort_by: sortBy, sort_order: sortOrder }
  );

  const { remove } = useCrud<SettingLinkMgmt>(API_ENDPOINTS.MANAGEMENT.SETTING_LINK);

  const handleCreate = () => {
    setEditingLink(null);
    setFormDialogOpen(true);
  };

  const handleEdit = (link: SettingLinkMgmt) => {
    setEditingLink(link);
    setFormDialogOpen(true);
  };

  const handleFormSuccess = () => {
    setFormDialogOpen(false);
    refetch();
  };

  const handleDelete = async (ids: number[]) => {
    setDeleteIds(ids);
    setDeleteDialogOpen(true);
  };

  const confirmDelete = async () => {
    await remove(deleteIds);
    setSelectedIds([]);
    setDeleteIds([]);
    setDeleteDialogOpen(false);
    refetch();
  };

  const handleSort = (column: string) => {
    if (sortBy === column) {
      setSortOrder(sortOrder === SORT_ORDER.ASC ? SORT_ORDER.DESC : SORT_ORDER.ASC);
    } else {
      setSortBy(column);
      setSortOrder(SORT_ORDER.ASC);
    }
  };

  const columns: Column<SettingLinkMgmt>[] = [
    { key: 'id', label: tFields('id'), sortable: true },
    { key: 'rank_order', label: tFields('order'), sortable: true },
    { key: 'name', label: tFields('name'), sortable: true },
    { key: 'url', label: tFields('url') },
    { key: 'description', label: tFields('description') },
    {
      key: 'status',
      label: tFields('status'),
      sortable: true,
      render: (item) => (
        <Badge variant={item.is_active ? 'default' : 'secondary'}>
          {item.is_active ? tCommon('active') : tCommon('inactive')}
        </Badge>
      ),
    },
    { key: 'updated_at', label: tFields('updatedAt'), sortable: true },
  ];

  const filterFields: FilterField[] = [
    { key: 'name', label: tFields('name'), type: 'text', placeholder: tCommon('search') },
    {
      key: 'status',
      label: tFields('status'),
      type: 'select',
      options: [
        { value: Status.ACTIVE, label: StatusLabels[Status.ACTIVE] },
        { value: Status.INACTIVE, label: StatusLabels[Status.INACTIVE] },
      ],
    },
    { key: 'is_active', label: tCommon('active'), type: 'boolean' },
  ];
  const searchFields: SearchField[] = [
    { key: 'name', label: tFields('name'), type: 'text' },
    { key: 'url', label: tFields('url'), type: 'text' },
    { key: 'description', label: tFields('description'), type: 'text' },
    {
      key: 'status',
      label: tFields('status'),
      type: 'select',
      options: Object.entries(StatusLabels).map(([value, label]) => ({
        value: value.toString(),
        label
      }))
    },
    { key: 'created_at', label: tFields('createdAt'), type: 'date' }
  ];
  const bulkActions: BulkAction[] = [
    { 
      label: tBulkActions('deleteSelected'), 
      icon: <Trash2 className="h-4 w-4" />, 
      variant: 'destructive', 
      onClick: async (ids) => { await remove(ids); refetch(); }, 
      confirmMessage: tCrud('deleteConfirm', { count: selectedIds.length, entity: tEntities('settingLink').toLowerCase() }), 
      confirmTitle: tCrud('deleteEntity', { entity: tEntities('settingLinks') }) 
    }, 
    { 
      label: tBulkActions('activateSelected'), 
      icon: <CheckCircle className="h-4 w-4" />, 
      onClick: async (ids) => { refetch(); } 
    }, 
    { 
      label: tBulkActions('deactivateSelected'), 
      icon: <XCircle className="h-4 w-4" />, 
      onClick: async (ids) => { refetch(); } 
    }
  ];
  const handleAdvancedSearch = (criteria: SearchCriteria[]) => { setAdvancedCriteria(criteria); const newFilters = criteria.reduce((acc, c) => ({ ...acc, [c.field]: c.value }), {}); setFilters(newFilters); setPage(1); };
  const handleImport = async (file: File, format: string) => { refetch(); };

  return (
    <AdminLayout>
      <PageHeader
        title={tManagement('title', { entity: tEntities('settingLinks') })}
        description={tManagement('description', { entity: tEntities('settingLinks').toLowerCase() })}
        breadcrumbs={[
          { label: tCommon('admin'), href: '/admin' },
          { label: tEntities('settingLinks'), isActive: true },
        ]}
        action={
          <Button onClick={handleCreate}>
            <Plus className="mr-2 h-4 w-4" /> {tCrud('createEntity', { entity: tEntities('settingLink') })}
          </Button>
        }
      />

      <div className="mt-6 space-y-4">
        <div className="flex gap-2">
          <AdvancedSearch fields={searchFields} onSearch={handleAdvancedSearch} />
          <SavedFilters 
            currentFilters={filters} 
            onApplyFilter={(f) => { 
              setFilters(f); 
              setPage(PAGINATION.DEFAULT_PAGE); 
            }} 
            storageKey="setting-link-filters" 
          />
          <ImportExport onImport={handleImport} />
        </div>

        <FilterPanel
          filters={filters}
          onFilterChange={(newFilters) => {
            setFilters(newFilters);
            setPage(PAGINATION.DEFAULT_PAGE);
          }}
          onReset={() => {
            setFilters({});
            setPage(PAGINATION.DEFAULT_PAGE);
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
          onEdit={(id) => {
            const link = data.find(l => l.id === id);
            if (link) handleEdit(link);
          }}
          onDelete={(id) => handleDelete([id])}
        />

        <Pagination
          pagination={pagination}
          page={page}
          onPageChange={setPage}
          perPage={perPage}
          onPerPageChange={(newPerPage) => {
            setPerPage(newPerPage);
            setPage(PAGINATION.DEFAULT_PAGE);
          }}
        />
      </div>

      {/* Create/Edit Setting Link Modal */}
      <Dialog open={formDialogOpen} onOpenChange={setFormDialogOpen}>
        <DialogContent className="max-w-xl max-h-[90vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>{editingLink ? tCrud('editEntity', { entity: tEntities('settingLink') }) : tCrud('createEntity', { entity: tEntities('settingLink') })}</DialogTitle>
            <DialogDescription>
              {editingLink ? tCrud('editDescription', { entity: tEntities('settingLink').toLowerCase() }) : tCrud('createDescription', { entity: tEntities('settingLink').toLowerCase() })}
            </DialogDescription>
          </DialogHeader>
          <SettingLinkForm
            initialData={editingLink}
            onSuccess={handleFormSuccess}
            onCancel={() => setFormDialogOpen(false)}
          />
        </DialogContent>
      </Dialog>

      <ConfirmDialog
        open={deleteDialogOpen}
        onOpenChange={setDeleteDialogOpen}
        title="Delete Setting Link(s)"
        description={`Are you sure you want to delete ${deleteIds.length} setting link(s)? This action cannot be undone.`}
        onConfirm={confirmDelete}
        confirmText="Delete"
        variant="destructive"
      />
    </AdminLayout>
  );
}

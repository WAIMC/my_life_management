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
import type { SkillMgmt } from '@/shared/types/api';
import { API_ENDPOINTS } from '@/shared/api';
import { 
  SORT_ORDER, 
  SORT_FIELDS,
  type SortOrder, 
  PAGINATION, 
  ADMIN_ROUTES 
} from '@/shared/constants';
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
import { SkillForm } from '@/components/forms/skill-form';
import { useTranslations } from 'next-intl';

export default function SkillListPage() {
  const [page, setPage] = useState<number>(PAGINATION.DEFAULT_PAGE);
  const [perPage, setPerPage] = useState<number>(PAGINATION.DEFAULT_PER_PAGE);
  const [filters, setFilters] = useState({});
  const [sortBy, setSortBy] = useState<string>(SORT_FIELDS.ORDER);
  const [sortOrder, setSortOrder] = useState<SortOrder>(SORT_ORDER.ASC);
  const [selectedIds, setSelectedIds] = useState<number[]>([]);
  
  // Dialog states
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [deleteIds, setDeleteIds] = useState<number[]>([]);
  const [formDialogOpen, setFormDialogOpen] = useState(false);
  const [editingSkill, setEditingSkill] = useState<SkillMgmt | null>(null);

  const tCommon = useTranslations('common');
  const tEntities = useTranslations('entities');
  const tFields = useTranslations('fields');
  const tManagement = useTranslations('management');
  const tCrud = useTranslations('crud');
  const tBulkActions = useTranslations('bulkActions');

  const { data, loading, pagination, refetch } = useApiData<SkillMgmt>(
    API_ENDPOINTS.MANAGEMENT.SKILL,
    { page, per_page: perPage, filters, sort_by: sortBy, sort_order: sortOrder }
  );

  const { remove } = useCrud<SkillMgmt>(API_ENDPOINTS.MANAGEMENT.SKILL);

  const handleCreate = () => {
    setEditingSkill(null);
    setFormDialogOpen(true);
  };

  const handleEdit = (skill: SkillMgmt) => {
    setEditingSkill(skill);
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

  const columns: Column<SkillMgmt>[] = [
    { key: 'id', label: tFields('id'), sortable: true },
    { key: 'rank_order', label: tFields('order'), sortable: true },
    { key: 'name', label: tFields('name'), sortable: true },
    { key: 'slug', label: tFields('slug') },
    { key: 'status', label: tFields('status'), sortable: true },
    { key: 'updated_at', label: tFields('updatedAt'), sortable: true },
  ];

  const filterFields: FilterField[] = [
    { key: 'name', label: tFields('name'), type: 'text', placeholder: tCommon('search') },
  ];
  const searchFields: SearchField[] = [
    { key: 'name', label: tFields('name'), type: 'text' },
    { key: 'slug', label: tFields('slug'), type: 'text' },
    { key: 'created_at', label: tFields('createdAt'), type: 'date' }
  ];
  const bulkActions: BulkAction[] = [
    { 
      label: tBulkActions('deleteSelected'), 
      icon: <Trash2 className="h-4 w-4" />, 
      variant: 'destructive', 
      onClick: async (ids) => { await remove(ids); refetch(); }, 
      confirmMessage: tCrud('deleteConfirm', { count: selectedIds.length, entity: tEntities('skill').toLowerCase() }), 
      confirmTitle: tCrud('deleteEntity', { entity: tEntities('skills') }) 
    }, 
    { 
      label: tBulkActions('activateSelected'), 
      icon: <CheckCircle className="h-4 w-4" />, 
      onClick: async () => { refetch(); } 
    }, 
    { 
      label: tBulkActions('deactivateSelected'), 
      icon: <XCircle className="h-4 w-4" />, 
      onClick: async () => { refetch(); } 
    }
  ];

  const handleAdvancedSearch = (criteria: SearchCriteria[]) => { 
    const newFilters = criteria.reduce((acc, c) => ({ ...acc, [c.field]: c.value }), {}); 
    setFilters(newFilters); 
    setPage(PAGINATION.DEFAULT_PAGE); 
  };

  const handleImport = async () => { 
    refetch(); 
  };

  return (
    <AdminLayout>
      <PageHeader
        title={tManagement('title', { entity: tEntities('skills') })}
        description={tManagement('description', { entity: tEntities('skills').toLowerCase() })}
        breadcrumbs={[
          { label: tCommon('admin'), href: ADMIN_ROUTES.DASHBOARD },
          { label: tEntities('skills'), isActive: true },
        ]}
        action={
          <Button onClick={handleCreate}>
            <Plus className="mr-2 h-4 w-4" /> {tCrud('createEntity', { entity: tEntities('skill') })}
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
            storageKey="skill-filters" 
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
            const skill = data.find(s => s.id === id);
            if (skill) handleEdit(skill);
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

      {/* Create/Edit Skill Modal */}
      <Dialog open={formDialogOpen} onOpenChange={setFormDialogOpen}>
        <DialogContent className="max-w-2xl max-h-[90vh] overflow-y-auto">
          <DialogHeader>
            <DialogTitle>{editingSkill ? tCrud('editEntity', { entity: tEntities('skill') }) : tCrud('createEntity', { entity: tEntities('skill') })}</DialogTitle>
            <DialogDescription>
              {editingSkill ? tCrud('editDescription', { entity: tEntities('skill').toLowerCase() }) : tCrud('createDescription', { entity: tEntities('skill').toLowerCase() })}
            </DialogDescription>
          </DialogHeader>
          <SkillForm
            initialData={editingSkill}
            onSuccess={handleFormSuccess}
            onCancel={() => setFormDialogOpen(false)}
          />
        </DialogContent>
      </Dialog>

      <ConfirmDialog
        open={deleteDialogOpen}
        onOpenChange={setDeleteDialogOpen}
        title={tCrud('deleteEntity', { entity: tEntities('skill') })}
        description={tCrud('deleteConfirm', { count: deleteIds.length, entity: tEntities('skill').toLowerCase() })}
        onConfirm={confirmDelete}
        confirmText={tCommon('delete')}
        variant="destructive"
      />
    </AdminLayout>
  );
}

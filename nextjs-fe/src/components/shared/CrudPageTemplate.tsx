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
import { AdvancedSearch, type SearchField, type SearchCriteria } from '@/components/advanced/advanced-search';
import { SavedFilters } from '@/components/advanced/saved-filters';
import { BulkActions, type BulkAction } from '@/components/crud/bulk-actions';
import { ImportExport } from '@/components/crud/import-export';

interface CrudPageTemplateProps<T> {
  // Page configuration
  title: string;
  description?: string;
  endpoint: string;
  
  // Permissions
  module: string;
  
  // Table configuration
  columns: Column<T>[];
  filterFields?: FilterField[];
  searchFields?: SearchField[];
  
  // Actions
  bulkActions?: BulkAction[];
  onImport?: (file: File, format: 'csv' | 'excel' | 'json') => Promise<void>;
  
  // Customization
  createPath?: string;
  editPath?: (id: number) => string;
  hideCreate?: boolean;
  hideImportExport?: boolean;
  hideBulkActions?: boolean;
  
  // Additional content
  headerAction?: React.ReactNode;
  beforeTable?: React.ReactNode;
  afterTable?: React.ReactNode;
}

/**
 * Generic CRUD Page Template
 * 
 * Reduces duplication across admin pages by providing a standard layout
 * with data table, filters, pagination, and CRUD actions.
 * 
 * Usage:
 * ```tsx
 * <CrudPageTemplate
 *   title="Users Management"
 *   endpoint={ENDPOINTS.MANAGEMENT.USER}
 *   module="user"
 *   columns={userColumns}
 *   filterFields={userFilterFields}
 * />
 * ```
 */
export function CrudPageTemplate<T extends { id: number }>({
  title,
  description,
  endpoint,
  module,
  columns,
  filterFields = [],
  searchFields = [],
  bulkActions = [],
  onImport,
  createPath,
  editPath,
  hideCreate = false,
  hideImportExport = false,
  hideBulkActions = false,
  headerAction,
  beforeTable,
  afterTable,
}: CrudPageTemplateProps<T>) {
  const router = useRouter();
  
  // State
  const [page, setPage] = useState(1);
  const [perPage, setPerPage] = useState(20);
  const [filters, setFilters] = useState({});
  const [sortBy, setSortBy] = useState('created_at');
  const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('desc');
  const [selectedIds, setSelectedIds] = useState<number[]>([]);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [deleteIds, setDeleteIds] = useState<number[]>([]);

  // Data fetching
  const { data, loading, pagination, refetch } = useApiData<T>(
    endpoint,
    { page, per_page: perPage, filters, sort_by: sortBy, sort_order: sortOrder }
  );

  const { remove, loading: deleteLoading } = useCrud<T>(endpoint);

  // Handlers
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
      setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc');
    } else {
      setSortBy(column);
      setSortOrder('asc');
    }
  };

  const handleAdvancedSearch = (criteria: SearchCriteria[]) => {
    const newFilters = criteria.reduce((acc, c) => ({ ...acc, [c.field]: c.value }), {});
    setFilters(newFilters);
    setPage(1);
  };

  const handleImport = async (file: File, format: 'csv' | 'excel' | 'json') => {
    if (onImport) {
      await onImport(file, format);
      refetch();
    }
  };

  // Default paths
  const defaultCreatePath = createPath || `/admin/${module}s/create`;
  const defaultEditPath = editPath || ((id: number) => `/admin/${module}s/${id}/edit`);

  return (
    <AdminLayout>
      <PageHeader
        title={title}
        description={description}
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: title, isActive: true },
        ]}
        action={
          headerAction || (
            !hideCreate && (
              
                <Button onClick={() => router.push(defaultCreatePath)}>
                  Create {module}
                </Button>
              
            )
          )
        }
      />

      <div className="mt-6 space-y-4">
        {/* Search and filters */}
        {(searchFields.length > 0 || !hideImportExport) && (
          <div className="flex gap-2">
            {searchFields.length > 0 && (
              <>
                <AdvancedSearch fields={searchFields} onSearch={handleAdvancedSearch} />
                <SavedFilters
                  currentFilters={filters}
                  onApplyFilter={(f) => {
                    setFilters(f);
                    setPage(1);
                  }}
                  storageKey={`${module}-filters`}
                />
              </>
            )}
            {!hideImportExport && (
              
                <ImportExport

                  onImport={handleImport}
                  moduleName={module}
                />
              
            )}
          </div>
        )}

        {/* Filter panel */}
        {filterFields.length > 0 && (
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
        )}

        {/* Bulk actions */}
        {!hideBulkActions && bulkActions.length > 0 && (
          
            <BulkActions
              selectedIds={selectedIds}
              onClearSelection={() => setSelectedIds([])}
              actions={bulkActions}
              isLoading={loading}
            />
          
        )}

        {/* Before table content */}
        {beforeTable}

        {/* Data table */}
        <DataTable
          data={data}
          columns={columns}
          loading={loading}
          selectedIds={selectedIds}
          onSelectionChange={setSelectedIds}
          onSort={handleSort}
          sortBy={sortBy}
          sortOrder={sortOrder}
          onEdit={(id) => router.push(defaultEditPath(id))}
          onDelete={(id) => handleDelete([id])}
        />

        {/* Pagination */}
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

        {/* After table content */}
        {afterTable}
      </div>

      {/* Delete confirmation dialog */}
      <ConfirmDialog
        open={deleteDialogOpen}
        onOpenChange={setDeleteDialogOpen}
        title={`Delete ${module}(s)`}
        description={`Are you sure you want to delete ${deleteIds.length} ${module}(s)? This action cannot be undone.`}
        onConfirm={confirmDelete}
        confirmText="Delete"
        variant="destructive"
      />
    </AdminLayout>
  );
}

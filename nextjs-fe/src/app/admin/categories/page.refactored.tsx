'use client';

import { CrudPageTemplate } from '@/components/shared/CrudPageTemplate';
import { type Column } from '@/components/data-table/data-table';
import { type FilterField } from '@/components/data-table/filter-panel';
import { Badge } from '@/components/ui/badge';
import type { Category } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, StatusLabels } from '@/lib/types/enums';

/**
 * Categories Page - Refactored with CrudPageTemplate
 * 
 * Before: ~120 lines of boilerplate
 * After: ~40 lines
 * 
 * Benefits:
 * - No state management boilerplate
 * - No pagination logic
 * - No filter/search logic
 * - No CRUD handlers
 * - Just configuration!
 */
export default function CategoriesPage() {
  // Define table columns
  const columns: Column<Category>[] = [
    { key: 'id', label: 'ID', sortable: true },
    { key: 'name', label: 'Name', sortable: true },
    { key: 'slug', label: 'Slug', sortable: true },
    {
      key: 'status',
      label: 'Status',
      sortable: true,
      render: (category) => (
        <Badge variant={category.is_active ? 'default' : 'secondary'}>
          {category.is_active ? 'Active' : 'Inactive'}
        </Badge>
      ),
    },
    { key: 'updated_at', label: 'Updated', sortable: true },
  ];

  // Define filter fields
  const filterFields: FilterField[] = [
    { 
      key: 'name', 
      label: 'Name', 
      type: 'text', 
      placeholder: 'Search by name...' 
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
      type: 'boolean' 
    },
  ];

  // That's it! Just return the template with configuration
  return (
    <CrudPageTemplate
      title="Categories Management"
      description="Manage all categories in your system"
      endpoint={ENDPOINTS.MANAGEMENT.CATEGORY}
      module="category"
      columns={columns}
      filterFields={filterFields}
    />
  );
}

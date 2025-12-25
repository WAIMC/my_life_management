'use client';

import { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { useJunctionTable } from '@/hooks/useJunctionTable';
import { departmentService } from '@/services/department.service';
import { handleBindErrors } from '@/lib/utils/error-handler';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { JunctionManager } from '@/components/junction/junction-manager';
import { HistoryViewer } from '@/components/history';
import { DepartmentTree } from '@/components/crud/department-tree';
import type { DepartmentMst, PolicyDepartmentMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, IsActive, IsDelete } from '@/lib/types/enums';

const departmentSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  description: z.string().optional(),
  parent_id: z.coerce.number().optional().nullable(),
  status: z.coerce.number().min(0).max(3),
  is_active: z.coerce.number().min(0).max(1),
});


type DepartmentFormData = z.infer<typeof departmentSchema>;

interface DepartmentFormProps {
  initialData?: DepartmentMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export function DepartmentForm({ initialData, onSuccess, onCancel }: DepartmentFormProps) {
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<DepartmentMst>(ENDPOINTS.MASTER.DEPARTMENT);
  const [allDepartments, setAllDepartments] = useState<DepartmentMst[]>([]);
  const [selectedParentId, setSelectedParentId] = useState<number | undefined | null>();

  // Junction table for Department-PolicyDepartment (Only in Edit mode)
  const policyDepartmentJunction = useJunctionTable<PolicyDepartmentMst>(
    ENDPOINTS.JUNCTION.DEPARTMENT_MANAGEMENT,
    ENDPOINTS.MASTER.POLICY_DEPARTMENT,
    'department_mst_id',
    'policy_department_mst_id',
    initialData?.id || 0
  );

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
  } = useForm<DepartmentFormData>({
    resolver: zodResolver(departmentSchema),
    defaultValues: {
      status: Status.ACTIVE,
      is_active: IsActive.ACTIVE,
    },
  });

  useEffect(() => {
    const loadDepartments = async () => {
      const response = await departmentService.list({ per_page: 1000 });
      if (response?.data?.data) {
        // Filter out current department to prevent circular reference
        const filtered = isEdit
          ? response.data.data.filter((d: DepartmentMst) => d.id !== initialData.id)
          : response.data.data;
        setAllDepartments(filtered);
      }
    };
    loadDepartments();
  }, [isEdit, initialData]);

  useEffect(() => {
    if (initialData) {
      reset({
        name: initialData.name,
        description: initialData.description || '',
        parent_id: initialData.parent_id,
        status: initialData.status,
        is_active: initialData.is_active ? IsActive.ACTIVE : IsActive.INACTIVE,
      });
      setSelectedParentId(initialData.parent_id);
    } else {
      reset({
        name: '',
        description: '',
        parent_id: null,
        status: Status.ACTIVE,
        is_active: IsActive.ACTIVE,
      });
      setSelectedParentId(undefined);
    }
  }, [initialData, reset]);

  const onSubmit = async (data: DepartmentFormData) => {
    try {
      const payload = { ...data, parent_id: data.parent_id ?? undefined };
      
      if (isEdit && initialData) {
        await update(initialData.id, payload);
      } else {
        await create({
          ...payload,
          is_delete: IsDelete.FALSE,
        });
      }
      onSuccess();
    } catch (error: any) {
      console.error(error);
      handleBindErrors(error, setError);
    }
  };

  const FormContent = (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="space-y-2">
        <Label htmlFor="name">
          Name <span className="text-red-500">*</span>
        </Label>
        <Input
          id="name"
          {...register('name')}
          className={errors.name ? 'border-red-500' : ''}
        />
        {errors.name && (
          <p className="text-sm text-red-500">{errors.name.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="description">Description</Label>
        <Textarea id="description" {...register('description')} rows={3} />
      </div>

      {/* Parent Department Selection */}
      <div className="space-y-2">
        <Label>Parent Department</Label>
        <div className="rounded-md border p-4 max-h-64 overflow-y-auto">
          <DepartmentTree
            departments={allDepartments}
            selectedId={selectedParentId}
            onSelect={(dept) => {
              setSelectedParentId(dept.id);
              setValue('parent_id', dept.id);
            }}
          />
          {selectedParentId && (
            <Button
              type="button"
              variant="ghost"
              size="sm"
              className="mt-2"
              onClick={() => {
                setSelectedParentId(undefined);
                setValue('parent_id', null as any);
              }}
            >
              Clear Selection
            </Button>
          )}
        </div>
        <p className="text-sm text-muted-foreground">
          Select a parent department to create a hierarchy. Leave empty for root level.
        </p>
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="status">
            Status <span className="text-red-500">*</span>
          </Label>
          <Select
            value={watch('status')?.toString()}
            onValueChange={(value) => setValue('status', Number(value) as any)}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={Status.ACTIVE.toString()}>Active</SelectItem>
              <SelectItem value={Status.INACTIVE.toString()}>Inactive</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div className="space-y-2 mt-0">
          <Label htmlFor="is_active">Is Active</Label>
          <Select
            value={watch('is_active')?.toString()}
            onValueChange={(value) => setValue('is_active', Number(value))}
          >
            <SelectTrigger>
              <SelectValue placeholder="Select activity" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={IsActive.ACTIVE.toString()}>Active</SelectItem>
              <SelectItem value={IsActive.INACTIVE.toString()}>Inactive</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <div className="flex justify-end gap-2 pt-4">
        <Button type="button" variant="outline" onClick={onCancel}>
          Cancel
        </Button>
        <Button type="submit" disabled={loading}>
          {loading ? (isEdit ? 'Updating...' : 'Creating...') : (isEdit ? 'Update' : 'Create')}
        </Button>
      </div>
    </form>
  );

  if (!isEdit) {
    return FormContent;
  }

  return (
    <Tabs defaultValue="details" className="w-full">
      <TabsList className="grid w-full grid-cols-3">
        <TabsTrigger value="details">Details</TabsTrigger>
        <TabsTrigger value="policy-departments">Policy Departments</TabsTrigger>
        <TabsTrigger value="history">History</TabsTrigger>
      </TabsList>
      
      <TabsContent value="details" className="mt-4">
        {FormContent}
      </TabsContent>

      <TabsContent value="policy-departments" className="mt-4">
        <JunctionManager
          allItems={policyDepartmentJunction.allItems}
          selectedIds={policyDepartmentJunction.selectedIds}
          onSelectionChange={policyDepartmentJunction.setSelectedIds}
          onSave={policyDepartmentJunction.save}
          loading={policyDepartmentJunction.loading}
          saving={policyDepartmentJunction.saving}
          title="Manage Policy Departments"
          itemLabel="policy departments"
          searchPlaceholder="Search policy departments..."
        />
      </TabsContent>
      
      <TabsContent value="history" className="mt-4">
        <div className="h-[400px] overflow-y-auto pr-2">
          {initialData && (
            <HistoryViewer
              entityType="department"
              entityId={initialData.id}
              endpoint={`${ENDPOINTS.MASTER.DEPARTMENT}-hist`}
            />
          )}
        </div>
      </TabsContent>
    </Tabs>
  );
}

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
import type { DepartmentMst, PolicyDepartmentMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, IsActive, IsDelete } from '@/lib/types/enums';

const departmentSchema = z.object({
  code: z.string().min(1, 'Code is required').max(50, 'Code must be at most 50 characters'),
  name: z.string().min(1, 'Name is required'),
  status: z.coerce.number().min(0).max(2),
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
    setError,
  } = useForm<DepartmentFormData>({
    resolver: zodResolver(departmentSchema),
    defaultValues: {
      status: Status.PUBLISHED,
    },
  });


  useEffect(() => {
    if (initialData) {
      reset({
        code: initialData.code || '',
        name: initialData.name,
        status: initialData.status,
      });
    } else {
      reset({
        code: '',
        name: '',
        status: Status.PUBLISHED,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: DepartmentFormData) => {
    try {
      const payload = { ...data };
      
      if (isEdit && initialData) {
        if (!initialData) return;
        await update(initialData.id, {
          ...payload,
          is_delete: initialData.is_delete || IsDelete.FALSE,
        });
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
      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="code">
            Code <span className="text-red-500">*</span>
          </Label>
          <Input
            id="code"
            {...register('code')}
            className={errors.code ? 'border-red-500' : ''}
            disabled={isEdit}
          />
          {errors.code && (
            <p className="text-sm text-red-500">{errors.code.message}</p>
          )}
        </div>

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
      </div>

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
            <SelectItem value={Status.DRAFT.toString()}>Draft</SelectItem>
            <SelectItem value={Status.PUBLISHED.toString()}>Published</SelectItem>
            <SelectItem value={Status.ARCHIVED.toString()}>Archived</SelectItem>
          </SelectContent>
        </Select>
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

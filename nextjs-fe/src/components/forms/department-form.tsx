'use client';
'use no memo';

import { useEffect } from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslations } from 'next-intl';
import { useCrud } from '@/shared/hooks/useCrud';
import { useJunctionTable } from '@/shared/hooks/useJunctionTable';
import { handleBindErrors } from '@/shared/utils/error-handler';
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
// import { JunctionManager } from '@/components/features/junction/junction-manager';
import { HistoryViewer } from '@/components/features/history/history-viewer';
import type { DepartmentMst, PolicyDepartmentMst } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { IsActive, DepartmentStatus } from '@/shared/enums';
import { departmentSchema, type DepartmentFormData } from '@/shared/validation/validation';
import type { DepartmentFormProps } from './types';

export function DepartmentForm({ initialData, onSuccess, onCancel }: DepartmentFormProps) {
  const tCommon = useTranslations('common');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<DepartmentMst>(ENDPOINTS.MASTER.DEPARTMENT);

  // Junction table for Department-PolicyDepartment (Only in Edit mode)
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
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
    control,
    reset,
    setError,
  } = useForm<DepartmentFormData>({
    resolver: zodResolver(departmentSchema),
    defaultValues: {
      status: DepartmentStatus.ACTIVE as unknown as IsActive,
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
        status: DepartmentStatus.ACTIVE as unknown as IsActive,
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
          is_delete: initialData.is_delete || false,
        });
      } else {
        await create({
          ...payload,
          is_delete: false,
        });
      }
      onSuccess();
    } catch (error: unknown) {
      console.error(error);
      handleBindErrors(error, setError);
    }
  };

  // Use useWatch hook instead of watch() to avoid React Compiler issues
  const statusValue = useWatch({ control, name: 'status' });

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
          value={statusValue?.toString()}
          onValueChange={(value) => setValue('status', Number(value) as unknown as IsActive)}
        >
          <SelectTrigger>
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value={DepartmentStatus.ACTIVE.toString()}>Active</SelectItem>
            <SelectItem value={DepartmentStatus.INACTIVE.toString()}>Inactive</SelectItem>
            <SelectItem value={DepartmentStatus.DRAFT.toString()}>Draft</SelectItem>
            <SelectItem value={DepartmentStatus.ARCHIVED.toString()}>Archived</SelectItem>
          </SelectContent>
        </Select>
      </div>

      <div className="flex justify-end gap-2 pt-4">
        <Button type="button" variant="outline" onClick={onCancel}>
          {tCommon('cancel')}
        </Button>
        <Button type="submit" disabled={loading}>
          {loading ? (isEdit ? tCommon('updating') : tCommon('creating')) : (isEdit ? tCommon('update') : tCommon('create'))}
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
        {/* TODO: Uncomment when JunctionManager component is available
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
        */}
        <p className="text-muted-foreground">Junction management coming soon</p>
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

'use client';

import { useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useCrud } from '@/shared/hooks/useCrud';
import { handleBindErrors } from '@/shared/utils/error-handler';
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
import type { PolicyDepartmentMst } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { Status } from '@/shared/enums';
import { policyDepartmentSchema, type PolicyDepartmentFormData } from '@/shared/validation/validation';

interface PolicyDepartmentFormProps {
  initialData?: PolicyDepartmentMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export function PolicyDepartmentForm({ initialData, onSuccess, onCancel }: PolicyDepartmentFormProps) {
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<PolicyDepartmentMst>(ENDPOINTS.MASTER.POLICY_DEPARTMENT);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
  } = useForm<PolicyDepartmentFormData>({
    resolver: zodResolver(policyDepartmentSchema),
    defaultValues: {
      status: Status.ACTIVE,
      is_active: true,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        name: initialData.name,
        description: initialData.description || '',
        status: initialData.status,
        is_active: initialData.is_active,
      });
    } else {
      reset({
        name: '',
        description: '',
        status: Status.ACTIVE,
        is_active: true,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: PolicyDepartmentFormData) => {
    try {
      if (isEdit && initialData) {
        await update(initialData.id, data);
      } else {
        await create({
          ...data,
          is_delete: false,
        });
      }
      onSuccess();
    } catch (error: any) {
      console.error(error);
      handleBindErrors(error, setError);
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="space-y-2">
        <Label htmlFor="name">
          Name <span className="text-red-500">*</span>
        </Label>
        <Input
          id="name"
          {...register('name')}
          className={errors.name ? 'border-red-500' : ''}
          placeholder="e.g. Terms of Service"
        />
        {errors.name && (
          <p className="text-sm text-red-500">{errors.name.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="description">Description</Label>
        <Textarea id="description" {...register('description')} rows={3} />
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
            <SelectItem value={Status.ACTIVE.toString()}>Active</SelectItem>
            <SelectItem value={Status.INACTIVE.toString()}>Inactive</SelectItem>
          </SelectContent>
        </Select>
      </div>

      <div className="flex items-center gap-2 mt-4">
        <input
          type="checkbox"
          id="is_active"
          {...register('is_active')}
          className="rounded"
        />
        <Label htmlFor="is_active">Is Active</Label>
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
}

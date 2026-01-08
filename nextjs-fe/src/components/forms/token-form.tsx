'use client';

import { useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useCrud } from '@/shared/hooks/useCrud';
import { useApiData } from '@/shared/hooks/useApiData';
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
import type { TokenMst, AdminMst } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { Status } from '@/shared/enums';
import { tokenSchema, type TokenFormData } from '@/shared/validation/validation';

interface TokenFormProps {
  initialData?: TokenMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export function TokenForm({ initialData, onSuccess, onCancel }: TokenFormProps) {
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<TokenMst>(ENDPOINTS.MASTER.TOKEN);

  // Fetch admins for the dropdown
  const { data: admins, loading: adminsLoading } = useApiData<AdminMst>(
    ENDPOINTS.MASTER.ADMIN,
    { page: 1, per_page: 1000, sort_by: 'email', sort_order: 'asc', filters: { status: Status.ACTIVE } }
  );

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
  } = useForm<TokenFormData>({
    resolver: zodResolver(tokenSchema),
    defaultValues: {
      status: Status.ACTIVE,
      is_active: true,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        token: initialData.token,
        admin_mst_id: initialData.admin_mst_id,
        status: initialData.status,
        is_active: initialData.is_active,
      });
    } else {
      reset({
        token: '',
        admin_mst_id: 0,
        status: Status.ACTIVE,
        is_active: true,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: TokenFormData) => {
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
        <Label htmlFor="admin_mst_id">
          Admin <span className="text-red-500">*</span>
        </Label>
        <Select
          value={watch('admin_mst_id')?.toString()}
          onValueChange={(value) => setValue('admin_mst_id', Number(value))}
          disabled={adminsLoading}
        >
          <SelectTrigger>
            <SelectValue placeholder={adminsLoading ? 'Loading admins...' : 'Select an admin'} />
          </SelectTrigger>
          <SelectContent>
            {admins.map((admin) => (
              <SelectItem key={admin.id} value={admin.id.toString()}>
                {admin.email} ({admin.first_name} {admin.last_name})
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
        {errors.admin_mst_id && (
          <p className="text-sm text-red-500">{errors.admin_mst_id.message}</p>
        )}
      </div>

       <div className="space-y-2">
        <Label htmlFor="token">
          Token <span className="text-red-500">*</span>
        </Label>
        <Input
          id="token"
          {...register('token')}
          className={errors.token ? 'border-red-500' : ''}
          placeholder="Enter token key"
        />
        {errors.token && (
          <p className="text-sm text-red-500">{errors.token.message}</p>
        )}
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

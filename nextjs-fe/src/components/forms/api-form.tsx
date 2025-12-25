'use client';

import { useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
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
import type { ApiMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const apiSchema = z.object({
  uri: z.string().min(1, 'URI is required'),
  method: z.string().min(1, 'Method is required'),
  description: z.string().optional(),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type ApiFormData = z.infer<typeof apiSchema>;

interface ApiFormProps {
  initialData?: ApiMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export function ApiForm({ initialData, onSuccess, onCancel }: ApiFormProps) {
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<ApiMst>(ENDPOINTS.MASTER.API);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
  } = useForm<ApiFormData>({
    resolver: zodResolver(apiSchema),
    defaultValues: {
      method: 'GET',
      status: Status.ACTIVE,
      is_active: true,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        uri: initialData.uri,
        method: initialData.method,
        description: initialData.description || '',
        status: initialData.status,
        is_active: initialData.is_active,
      });
    } else {
      reset({
        uri: '',
        method: 'GET',
        description: '',
        status: Status.ACTIVE,
        is_active: true,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: ApiFormData) => {
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
        <Label htmlFor="uri">
          URI <span className="text-red-500">*</span>
        </Label>
        <Input
          id="uri"
          {...register('uri')}
          className={errors.uri ? 'border-red-500' : ''}
          placeholder="/api/v1/..."
        />
        {errors.uri && (
          <p className="text-sm text-red-500">{errors.uri.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="method">
          Method <span className="text-red-500">*</span>
        </Label>
        <Select
          value={watch('method')}
          onValueChange={(value) => setValue('method', value)}
        >
          <SelectTrigger>
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="GET">GET</SelectItem>
            <SelectItem value="POST">POST</SelectItem>
            <SelectItem value="PUT">PUT</SelectItem>
            <SelectItem value="DELETE">DELETE</SelectItem>
            <SelectItem value="PATCH">PATCH</SelectItem>
          </SelectContent>
        </Select>
        {errors.method && (
          <p className="text-sm text-red-500">{errors.method.message}</p>
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

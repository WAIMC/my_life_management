'use client';

import { useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { useApiData } from '@/hooks/useApiData';
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
import type { ApiMst, FeatureMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';

const apiSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  path: z.string().min(1, 'Path is required'),
  method: z.string().min(1, 'Method is required'),
  description: z.string().optional(),
  is_active: z.boolean(),
  feature_mst_id: z.coerce.number().min(1, 'Feature is required'),
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
  
  // Fetch features for dropdown
  const { data: features } = useApiData<FeatureMst>(ENDPOINTS.MASTER.FEATURE, {
    per_page: 1000,
    sort_by: 'name',
    sort_order: 'asc',
  });

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
    setError,
  } = useForm<ApiFormData>({
    resolver: zodResolver(apiSchema),
    defaultValues: {
      name: '',
      path: '',
      method: 'GET',
      is_active: true,
      feature_mst_id: 0,
    },
  });

  useEffect(() => {
    if (initialData) {
      const methods = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
      const typeIndex = typeof initialData.type === 'string' ? parseInt(initialData.type) : initialData.type;
      const mappedMethod = methods[typeIndex] || 'GET';
      
      reset({
        name: initialData.name,
        path: initialData.path,
        method: mappedMethod,
        description: '', // Not in backend
        is_active: initialData.is_active,
        feature_mst_id: initialData.feature_mst_id,
      });
    } else {
      reset({
        name: '',
        path: '',
        method: 'GET',
        description: '',
        is_active: true,
        feature_mst_id: 0,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: ApiFormData) => {
    try {
      const methodMap: Record<string, number> = {
        'GET': 0, 'POST': 1, 'PUT': 2, 'PATCH': 3, 'DELETE': 4
      };
      
      const payload = { 
        ...data,
        type: methodMap[data.method] ?? 0,
        is_delete: false 
      };

      if (isEdit && initialData) {
        if (!initialData) return;
        await update(initialData.id, {
            ...payload,
            is_delete: initialData.is_delete || false
        });
      } else {
        await create(payload);
      }
      onSuccess();
    } catch (error: any) {
      console.error(error);
      handleBindErrors(error, setError);
    }
  };

  const currentFeatureId = watch('feature_mst_id');

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="space-y-2">
        <Label htmlFor="feature_mst_id">
          Feature <span className="text-red-500">*</span>
        </Label>
        <Select
          value={currentFeatureId.toString()}
          onValueChange={(value) => {
            if (value && value.trim() !== '') {
              setValue('feature_mst_id', Number(value));
            }
          }}
        >
          <SelectTrigger>
            <SelectValue placeholder="Select Feature" />
          </SelectTrigger>
          <SelectContent>
            {features.map((feature) => (
              <SelectItem key={feature.id} value={feature.id.toString()}>
                {feature.name}
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
        {errors.feature_mst_id && (
          <p className="text-sm text-red-500">{errors.feature_mst_id.message}</p>
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
          placeholder="API Name"
        />
        {errors.name && (
          <p className="text-sm text-red-500">{errors.name.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="path">
          Path / URI <span className="text-red-500">*</span>
        </Label>
        <Input
          id="path"
          {...register('path')}
          className={errors.path ? 'border-red-500' : ''}
          placeholder="/api/v1/..."
        />
        {errors.path && (
          <p className="text-sm text-red-500">{errors.path.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="method">
          Method <span className="text-red-500">*</span>
        </Label>
        <Select
          value={watch('method')}
          onValueChange={(value) => {
            if (value && value.trim() !== '') {
              setValue('method', value);
            }
          }}
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
        <Label htmlFor="description">Description (Optional)</Label>
        <Textarea id="description" {...register('description')} rows={3} />
      </div>

      <div className="space-y-2">
        <Label htmlFor="is_active">
          Status <span className="text-red-500">*</span>
        </Label>
        <Select
          value={watch('is_active') ? '1' : '0'}
          onValueChange={(value) => setValue('is_active', value === '1')}
        >
          <SelectTrigger>
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value="1">Active</SelectItem>
            <SelectItem value="0">Inactive</SelectItem>
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
}

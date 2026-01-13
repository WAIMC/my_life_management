'use client';

import { useEffect } from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslations } from 'next-intl';
import { useCrud } from '@/shared/hooks/useCrud';
import { useApiData } from '@/shared/hooks/useApiData';
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
import type { ApiMst, FeatureMst } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { SORT_ORDER, SORT_FIELDS, HTTP_METHODS } from '@/shared/config/constant';
import { IsActive, IsActiveLabels, TypeOfMethod } from '@/shared/enums';
import { getApiSchema, type ApiFormData } from '@/shared/validation/validation';
import type { ApiFormProps } from './types';

export function ApiForm({ initialData, onSuccess, onCancel }: ApiFormProps) {
  const tCommon = useTranslations('common');
  const tForms = useTranslations('forms.placeholders');
  const tLabels = useTranslations('forms.labels');
  const tValidation = useTranslations('validation');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<ApiMst>(ENDPOINTS.MASTER.API);
  
  // Fetch features for dropdown
  const { data: features } = useApiData<FeatureMst>(ENDPOINTS.MASTER.FEATURE, {
    per_page: 1000,
    sort_by: SORT_FIELDS.NAME,
    sort_order: SORT_ORDER.ASC,
  });

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<ApiFormData>({
    resolver: zodResolver(getApiSchema(tValidation)),
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
      const methods: Array<'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'> = [
        HTTP_METHODS.GET,
        HTTP_METHODS.POST,
        HTTP_METHODS.PUT,
        HTTP_METHODS.PATCH,
        HTTP_METHODS.DELETE
      ];
      const typeIndex = typeof initialData.type === 'string' ? parseInt(initialData.type) : initialData.type;
      const mappedMethod = methods[typeIndex] || HTTP_METHODS.GET;
      
      reset({
        name: initialData.name,
        path: initialData.path,
        type: initialData.type,
        method: mappedMethod,
        description: '', // Not in backend
        is_active: initialData.is_active,
        feature_mst_id: initialData.feature_mst_id,
      });
    } else {
      reset({
        name: '',
        path: '',
        type: 0,
        method: HTTP_METHODS.GET,
        description: '',
        is_active: true,
        feature_mst_id: 0,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: ApiFormData) => {
    try {
      const methodMap: Record<string, number> = {
        [HTTP_METHODS.GET]: TypeOfMethod.GET,
        [HTTP_METHODS.POST]: TypeOfMethod.POST,
        [HTTP_METHODS.PUT]: TypeOfMethod.PUT,
        [HTTP_METHODS.PATCH]: TypeOfMethod.PATCH,
        [HTTP_METHODS.DELETE]: TypeOfMethod.DELETE
      };
      
      const payload = { 
        ...data,
        type: Number(data.method ? (methodMap[data.method] ?? 0) : 0),
        feature_mst_id: Number(data.feature_mst_id),
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
    } catch (error: unknown) {
      console.error(error);
      handleBindErrors(error, setError);
    }
  };

  // Use useWatch hook instead of watch() to avoid React Compiler issues
  const currentFeatureId = useWatch({ control, name: 'feature_mst_id' });
  const methodValue = useWatch({ control, name: 'method' });
  const isActiveValue = useWatch({ control, name: 'is_active' });

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="space-y-2">
        <Label htmlFor="feature_mst_id">
          {tLabels('feature')} <span className="text-red-500">*</span>
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
            <SelectValue placeholder={tForms('selectFeature')} />
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
          {tLabels('name')} <span className="text-red-500">*</span>
        </Label>
        <Input
          id="name"
          {...register('name')}
          className={errors.name ? 'border-red-500' : ''}
          placeholder={tForms('apiName')}
        />
        {errors.name && (
          <p className="text-sm text-red-500">{errors.name.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="path">
          {tLabels('path')} <span className="text-red-500">*</span>
        </Label>
        <Input
          id="path"
          {...register('path')}
          className={errors.path ? 'border-red-500' : ''}
          placeholder={tForms('apiPath')}
        />
        {errors.path && (
          <p className="text-sm text-red-500">{errors.path.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="method">
          {tLabels('method')} <span className="text-red-500">*</span>
        </Label>
        <Select
          value={methodValue}
          onValueChange={(value) => {
            const validMethods: Array<'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'> = [
              HTTP_METHODS.GET,
              HTTP_METHODS.POST,
              HTTP_METHODS.PUT,
              HTTP_METHODS.PATCH,
              HTTP_METHODS.DELETE
            ];
            if (value && validMethods.includes(value as 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE')) {
              setValue('method', value as 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE');
            }
          }}
        >
          <SelectTrigger>
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value={HTTP_METHODS.GET}>{HTTP_METHODS.GET}</SelectItem>
            <SelectItem value={HTTP_METHODS.POST}>{HTTP_METHODS.POST}</SelectItem>
            <SelectItem value={HTTP_METHODS.PUT}>{HTTP_METHODS.PUT}</SelectItem>
            <SelectItem value={HTTP_METHODS.DELETE}>{HTTP_METHODS.DELETE}</SelectItem>
            <SelectItem value={HTTP_METHODS.PATCH}>{HTTP_METHODS.PATCH}</SelectItem>
          </SelectContent>
        </Select>
        {errors.method && (
          <p className="text-sm text-red-500">{errors.method.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="description">{tLabels('description')}</Label>
        <Textarea id="description" {...register('description')} rows={3} />
      </div>

      <div className="space-y-2">
        <Label htmlFor="is_active">
          {tLabels('status')} <span className="text-red-500">*</span>
        </Label>
        <Select
          value={isActiveValue ? IsActive.TRUE.toString() : IsActive.FALSE.toString()}
          onValueChange={(value) => setValue('is_active', value === IsActive.TRUE.toString())}
        >
          <SelectTrigger>
            <SelectValue />
          </SelectTrigger>
          <SelectContent>
            <SelectItem value={IsActive.TRUE.toString()}>{IsActiveLabels[IsActive.TRUE]}</SelectItem>
            <SelectItem value={IsActive.FALSE.toString()}>{IsActiveLabels[IsActive.FALSE]}</SelectItem>
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
}

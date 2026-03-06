'use client';

import { useEffect, useState } from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslations } from 'next-intl';
import { useCrud } from '@/shared/hooks/useCrud';
import { useActionLock } from '@/shared/hooks/useActionLock';
import { UI_CONSTANTS } from '@/shared/config';
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
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import type { CategoryMgmt } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { CategoryStatus, CategoryStatusLabels } from '@/shared/enums';
import { getCategorySchema, type CategoryFormData } from '@/shared/validation/validation';
import { slugify } from '@/shared/utils/string-utils';
import type { CategoryFormProps } from './types';

export function CategoryForm({ initialData, onSuccess, onCancel }: CategoryFormProps) {
  const tCommon = useTranslations('common');
  const tForms = useTranslations('forms.placeholders');
  const tLabels = useTranslations('forms.labels');
  const tValidation = useTranslations('validation');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<CategoryMgmt>(ENDPOINTS.MANAGEMENT.CATEGORY);
  const [activeTab, setActiveTab] = useState('details');

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<CategoryFormData>({
    resolver: zodResolver(getCategorySchema(tValidation)),
    defaultValues: {
      rank_order: 0,
      status: CategoryStatus.ACTIVE,
      is_display: true,
      parent_id: 0,
      slug: '',
      is_delete: false,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        name: initialData.name,
        description: initialData.description || '',
        slug: initialData.slug,
        rank_order: initialData.rank_order,
        status: initialData.status,
        is_display: initialData.is_display,
        parent_id: initialData.parent_id,
        is_delete: initialData.is_delete,
      });
    } else {
      reset({
        name: '',
        description: '',
        slug: '',
        rank_order: 0,
        status: CategoryStatus.ACTIVE,
        is_display: true,
        parent_id: 0,
        is_delete: false,
      });
    }
  }, [initialData, reset]);

  const { execute, isLoading: isActionProcessing } = useActionLock({ delay: UI_CONSTANTS.ACTION_DELAY_MS });

  const onSubmit = async (data: CategoryFormData) => {
    await execute(async () => {
      try {
        // Convert string to number for rank_order and handle boolean to number for Enums
        const payload = {
          ...data,
          rank_order: Number(data.rank_order),
        };
      
      if (isEdit && initialData) {
        await update(initialData.id, payload);
      } else {
        await create(payload);
      }
      onSuccess();
    } catch (error: unknown) {
      console.error(error);
      handleBindErrors(error, setError);
    }
    });
  };

  // Use useWatch hook instead of watch() to avoid React Compiler issues
  const statusValue = useWatch({ control, name: 'status' });

  const FormContent = (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="space-y-2">
        <Label htmlFor="name">
          {tLabels('name')} <span className="text-red-500">*</span>
        </Label>
        <Input
          id="name"
          {...register('name', {
            onChange: (e) => {
              setValue('slug', slugify(e.target.value), { shouldValidate: true });
            },
          })}
          className={errors.name ? 'border-red-500' : ''}
        />
        {errors.name && (
          <p className="text-sm text-red-500">{errors.name.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="description">{tLabels('description')}</Label>
        <Textarea id="description" {...register('description')} rows={3} />
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="slug">{tLabels('slug')} <span className="text-red-500">*</span></Label>
          <Input
            id="slug"
            {...register('slug')}
            placeholder={tForms('slugExample')}
            className={errors.slug ? 'border-red-500' : ''}
          />
           {errors.slug && (
            <p className="text-sm text-red-500">{errors.slug.message}</p>
          )}
        </div>

        <div className="space-y-2">
          <Label htmlFor="rank_order">
            {tLabels('displayOrder')} <span className="text-red-500">*</span>
          </Label>
          <Input
            id="rank_order"
            type="number"
            {...register('rank_order', { valueAsNumber: true })}
            className={errors.rank_order ? 'border-red-500' : ''}
          />
          {errors.rank_order && (
            <p className="text-sm text-red-500">{errors.rank_order.message}</p>
          )}
        </div>
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="status">
            {tLabels('status')} <span className="text-red-500">*</span>
          </Label>
          <Select
            value={statusValue?.toString()}
            onValueChange={(value) => setValue('status', Number(value) as CategoryStatus)}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={CategoryStatus.ACTIVE.toString()}>{CategoryStatusLabels[CategoryStatus.ACTIVE]}</SelectItem>
              <SelectItem value={CategoryStatus.INACTIVE.toString()}>{CategoryStatusLabels[CategoryStatus.INACTIVE]}</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div className="flex items-center gap-2 mt-8">
          <input
            type="checkbox"
            id="is_display"
            {...register('is_display')}
            className="rounded"
          />
          <Label htmlFor="is_display">{tLabels('isDisplay')}</Label>
        </div>
      </div>

      <div className="flex justify-end gap-2 pt-4">
        <Button type="button" variant="outline" onClick={onCancel} disabled={loading || isActionProcessing}>
          {tCommon('cancel')}
        </Button>
        <Button type="submit" disabled={loading || isActionProcessing}>
          {loading || isActionProcessing ? (isEdit ? tCommon('updating') : tCommon('creating')) : (isEdit ? tCommon('update') : tCommon('create'))}
        </Button>
      </div>
    </form>
  );

  if (!isEdit) {
    return FormContent;
  }

  return (
    <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
      <TabsList className="grid w-full grid-cols-2">
        <TabsTrigger value="details">{tCommon('details')}</TabsTrigger>
        <TabsTrigger value="entries">{tCommon('entries')}</TabsTrigger>
      </TabsList>
      
      <TabsContent value="details" className="mt-4">
        {FormContent}
      </TabsContent>

      <TabsContent value="entries" className="mt-4">
        <div className="text-muted-foreground">{tCommon('entriesManagementComingSoon')}</div>
      </TabsContent>
    </Tabs>
  );
}

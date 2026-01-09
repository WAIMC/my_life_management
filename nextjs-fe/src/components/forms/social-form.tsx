'use client';
'use no memo';

import { useEffect } from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslations } from 'next-intl';
import { useCrud } from '@/shared/hooks/useCrud';
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
import type { SocialMgmt } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { SocialStatus, IsActive } from '@/shared/enums';
import { socialSchema, type SocialFormData } from '@/shared/validation/validation';
import type { SocialFormProps } from './types';

export function SocialForm({ initialData, onSuccess, onCancel }: SocialFormProps) {
  const tCommon = useTranslations('common');
  const tForms = useTranslations('forms.placeholders');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<SocialMgmt>(ENDPOINTS.MANAGEMENT.SOCIAL);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<SocialFormData>({
    resolver: zodResolver(socialSchema),
    defaultValues: {
      rank_order: 0,
      status: IsActive.TRUE,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        name: initialData.name,
        link: initialData.link,
        image: initialData.image || '',
        rank_order: initialData.rank_order,
        status: initialData.status,
        is_display: initialData.is_display,
      });
    } else {
      reset({
        name: '',
        link: '',
        image: '',
        rank_order: 0,
        status: IsActive.TRUE,
        is_display: true,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: SocialFormData) => {
    try {
      // Convert string to number for rank_order
      const payload = {
        ...data,
        rank_order: Number(data.rank_order),
      };
      
      if (isEdit && initialData) {
        await update(initialData.id, payload);
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

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="name">
            Name <span className="text-red-500">*</span>
          </Label>
          <Input
            id="name"
            {...register('name')}
            className={errors.name ? 'border-red-500' : ''}
            placeholder={tForms('socialName')}
          />
          {errors.name && (
            <p className="text-sm text-red-500">{errors.name.message}</p>
          )}
        </div>

        <div className="space-y-2">
          <Label htmlFor="image">Icon/Image URL</Label>
          <Input
            id="image"
            {...register('image')}
            placeholder={tForms('socialIcon')}
          />
        </div>
      </div>

      <div className="space-y-2">
        <Label htmlFor="link">
          Link <span className="text-red-500">*</span>
        </Label>
        <Input
          id="link"
          {...register('link')}
          className={errors.link ? 'border-red-500' : ''}
          placeholder={tForms('socialUrl')}
        />
        {errors.link && (
          <p className="text-sm text-red-500">{errors.link.message}</p>
        )}
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="rank_order">
            Display Order <span className="text-red-500">*</span>
          </Label>
          <Input
            id="rank_order"
            type="number"
            {...register('rank_order')}
            className={errors.rank_order ? 'border-red-500' : ''}
          />
          {errors.rank_order && (
            <p className="text-sm text-red-500">{errors.rank_order.message}</p>
          )}
        </div>

        <div className="space-y-2">
          <Label htmlFor="status">
            Status <span className="text-red-500">*</span>
          </Label>
          <Select
            value={statusValue?.toString()}
            onValueChange={(value) => setValue('status', Number(value) as IsActive)}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={SocialStatus.ACTIVE.toString()}>Active</SelectItem>
              <SelectItem value={SocialStatus.INACTIVE.toString()}>Inactive</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <div className="flex items-center gap-2 mt-4">
        <input
          type="checkbox"
          id="is_display"
          {...register('is_display')}
          className="rounded"
        />
        <Label htmlFor="is_display">Is Display</Label>
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

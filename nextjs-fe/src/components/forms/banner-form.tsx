'use client';

import { useEffect, useState } from 'react';
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
import { ImageUpload } from '@/components/common/image-upload';
import type { BannerMgmt } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { IsActive, IsActiveLabels } from '@/shared/enums';
import { bannerSchema, type BannerFormData } from '@/shared/validation/validation';
import type { BannerFormProps } from './types';

export function BannerForm({ initialData, onSuccess, onCancel }: BannerFormProps) {
  const tCommon = useTranslations('common');
  const tForms = useTranslations('forms.placeholders');
  const tLabels = useTranslations('forms.labels');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<BannerMgmt>(ENDPOINTS.MANAGEMENT.BANNER);
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  const [imageFile, setImageFile] = useState<File | null>(null);
  const [imagePreview, setImagePreview] = useState<string | null>(() => initialData?.image || null);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<BannerFormData>({
    resolver: zodResolver(bannerSchema),
    defaultValues: {
      status: IsActive.TRUE,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        title: initialData.title,
        slug: initialData.slug || '',
        description: initialData.description || '',
        link: initialData.link || '',
        image: initialData.image || '',
        position: initialData.position || '',
        status: initialData.status,
      });
    } else {
      reset({
        title: '',
        slug: '',
        description: '',
        link: '',
        image: '',
        position: '',
        status: IsActive.TRUE,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: BannerFormData) => {
    try {
      const payload = { ...data };
      
      // TODO: Handle Image Upload properly if API supports it
      // Currently generic placeholder logic
      
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
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
          <ImageUpload
            label="Banner Image"
            value={imagePreview ?? undefined}
            onChange={(file, preview) => {
              setImageFile(file);
              setImagePreview(preview);
              // if preview is a blob url, we might need to upload it. 
              // For now assuming existing URL is string.
            }}
            maxSize={10}
            shape="rectangle"
            aspectRatio="aspect-video"
          />
        </div>
        
        <div className="space-y-4">
          <div className="space-y-2">
            <Label htmlFor="title">{tLabels('title')} <span className="text-red-500">*</span></Label>
            <Input id="title" {...register('title')} className={errors.title ? 'border-red-500' : ''} />
            {errors.title && <p className="text-sm text-red-500">{errors.title.message}</p>}
          </div>

          <div className="space-y-2">
            <Label htmlFor="link">{tLabels('linkUrl')}</Label>
            <Input id="link" {...register('link')} placeholder={tForms('urlExample')} />
          </div>

          <div className="space-y-2">
            <Label htmlFor="position">{tLabels('position')}</Label>
            <Input id="position" {...register('position')} />
          </div>
        </div>
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
            <Label htmlFor="status">{tLabels('status')} <span className="text-red-500">*</span></Label>
            <Select value={statusValue?.toString()} onValueChange={(value) => setValue('status', Number(value) as IsActive)}>
              <SelectTrigger><SelectValue /></SelectTrigger>
              <SelectContent>
                <SelectItem value={IsActive.TRUE.toString()}>{IsActiveLabels[IsActive.TRUE]}</SelectItem>
                <SelectItem value={IsActive.FALSE.toString()}>{IsActiveLabels[IsActive.FALSE]}</SelectItem>
              </SelectContent>
            </Select>
          </div>
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

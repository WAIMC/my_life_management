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
import { Textarea } from '@/components/ui/textarea';
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
import { StatusEnum, StatusEnumLabels } from '@/shared/enums';
import { UPLOAD_CONFIG } from '@/shared/config/constant';
import { getBannerSchema, type BannerFormData } from '@/shared/validation/validation';
import { mediaFileService } from '@/shared/services/modules/media-file.service';
import { slugify } from '@/shared/utils/string-utils';
import type { BannerFormProps } from './types';

export function BannerForm({ initialData, onSuccess, onCancel }: BannerFormProps) {
  const tCommon = useTranslations('common');
  const tForms = useTranslations('forms.placeholders');
  const tLabels = useTranslations('forms.labels');
  const tValidation = useTranslations('validation');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<BannerMgmt>(ENDPOINTS.MANAGEMENT.BANNER);
  
  const [imagePreview, setImagePreview] = useState<string | null>(() => initialData?.image || null);
  const [isUploading, setIsUploading] = useState(false);
  const [uploadedMediaId, setUploadedMediaId] = useState<number | null>(null);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
    watch,
  } = useForm<BannerFormData>({
    resolver: zodResolver(getBannerSchema(tValidation)),
    defaultValues: {
      status: StatusEnum.PUBLISHED,
    },
  });

  // Watch title to auto-generate slug
  const titleValue = watch('title');
  useEffect(() => {
    if (titleValue && !isEdit) {
        setValue('slug', slugify(titleValue));
    }
  }, [titleValue, isEdit, setValue]);

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
      setImagePreview(initialData.image || null);
    } else {
      reset({
        title: '',
        slug: '',
        description: '',
        link: '',
        image: '',
        position: '',
        status: StatusEnum.PUBLISHED,
      });
    }
  }, [initialData, reset]);

  const handleImageChange = async (file: File | null, preview: string | null) => {
    setImagePreview(preview);
    
    // If file is cleared
    if (!file) {
        setValue('image', '');
        return;
    }

    // Auto upload when file selected
    try {
        setIsUploading(true);
        const uploadedId = await mediaFileService.upload({
            file: file,
            workspace_id: 1, // Default workspace or from context
            parent_path: UPLOAD_CONFIG.TEMP_UPLOAD_PATH, // Upload to temp folder first
        });
        
        setUploadedMediaId(uploadedId);
        
        const fileDetails = await mediaFileService.get(uploadedId);
        if (fileDetails.url) {
            setValue('image', fileDetails.url);
        } else {
             console.error('No URL returned for uploaded image');
        }
    } catch (err) {
        console.error('Upload failed', err);
    } finally {
        setIsUploading(false);
    }
  };


  const onSubmit = async (data: BannerFormData) => {
    try {
      if (isUploading) return; // Prevent submit while uploading

      const payload = { ...data };
      if (uploadedMediaId) {
        (payload as any).media_id = uploadedMediaId;
      }

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
            onChange={handleImageChange}
            maxSize={10}
            shape="rectangle"
            aspectRatio="aspect-video"
          />
           {isUploading && <p className="text-sm text-yellow-600 mt-1">Uploading image...</p>}
           {!imagePreview && errors.image && <p className="text-sm text-red-500">{tValidation('image.required')}</p>}
           {/* Hidden input to register image field for validation */}
           <input type="hidden" {...register('image')} />
        </div>
        
        <div className="space-y-4">
          <div className="space-y-2">
            <Label htmlFor="title">{tLabels('title')} <span className="text-red-500">*</span></Label>
            <Input id="title" {...register('title')} className={errors.title ? 'border-red-500' : ''} />
            {errors.title && <p className="text-sm text-red-500">{errors.title.message}</p>}
          </div>

           <div className="space-y-2">
            <Label htmlFor="slug">{tLabels('slug')} <span className="text-red-500">*</span></Label>
            <Input id="slug" {...register('slug')} className={errors.slug ? 'border-red-500' : ''} />
            {errors.slug && <p className="text-sm text-red-500">{errors.slug.message}</p>}
          </div>

          <div className="space-y-2">
            <Label htmlFor="link">{tLabels('linkUrl')} <span className="text-red-500">*</span></Label>
            <Input id="link" {...register('link')} placeholder={tForms('urlExample')} className={errors.link ? 'border-red-500' : ''} />
             {errors.link && <p className="text-sm text-red-500">{errors.link.message}</p>}
          </div>

          <div className="space-y-2">
            <Label htmlFor="position">{tLabels('position')} <span className="text-red-500">*</span></Label>
            <Input id="position" {...register('position')} className={errors.position ? 'border-red-500' : ''} />
             {errors.position && <p className="text-sm text-red-500">{errors.position.message}</p>}
          </div>
        </div>
      </div>

      <div className="space-y-2">
        <Label htmlFor="description">{tLabels('description')} <span className="text-red-500">*</span></Label>
        <Textarea id="description" {...register('description')} className={errors.description ? 'border-red-500' : ''} />
        {errors.description && <p className="text-sm text-red-500">{errors.description.message}</p>}
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
            <Label htmlFor="status">{tLabels('status')} <span className="text-red-500">*</span></Label>
            <Select value={statusValue?.toString()} onValueChange={(value) => setValue('status', Number(value) as StatusEnum)}>
              <SelectTrigger><SelectValue /></SelectTrigger>
              <SelectContent>
                {Object.values(StatusEnum)
                    .filter((value) => typeof value === 'number')
                    .map((value) => (
                      <SelectItem key={value} value={value.toString()}>
                        {StatusEnumLabels[value as StatusEnum]}
                      </SelectItem>
                    ))}
              </SelectContent>
            </Select>
          </div>
      </div>

      <div className="flex justify-end gap-2 pt-4">
        <Button type="button" variant="outline" onClick={onCancel}>
          {tCommon('cancel')}
        </Button>
        <Button type="submit" disabled={loading || isUploading}>
          {loading || isUploading ? (isEdit ? tCommon('updating') : tCommon('creating')) : (isEdit ? tCommon('update') : tCommon('create'))}
        </Button>
      </div>
    </form>
  );
}

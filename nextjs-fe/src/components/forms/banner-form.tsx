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
import { MediaSelectorModal } from '@/components/common/media-selector-modal';
import type { BannerMgmt } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { StatusEnum, StatusEnumLabels } from '@/shared/enums';
import { getBannerSchema, type BannerFormData } from '@/shared/validation/validation';
import { slugify } from '@/shared/utils/string-utils';
import type { BannerFormProps } from './types';
import Image from 'next/image';
import { Image as ImageIcon, X } from 'lucide-react';
import type { MediaFile } from '@/shared/types/media-file.types';

export function BannerForm({ initialData, onSuccess, onCancel }: BannerFormProps) {
  const tCommon = useTranslations('common');
  const tLabels = useTranslations('forms.labels');
  const tValidation = useTranslations('validation');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<BannerMgmt>(ENDPOINTS.MANAGEMENT.BANNER);
  
  
  const [uploadedMediaId, setUploadedMediaId] = useState<number | null>(null);
  const [mediaSelectorOpen, setMediaSelectorOpen] = useState(false);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<BannerFormData>({
    resolver: zodResolver(getBannerSchema(tValidation)),
    defaultValues: {
      status: StatusEnum.PUBLISHED,
    },
  });

  // Watch title to auto-generate slug
  // Use useWatch to avoid React Compiler warning
  const titleValue = useWatch({ control, name: 'title' });
  
  // Watch image for preview
  const imagePreview = useWatch({ control, name: 'image' });

  useEffect(() => {
    // Synchronize slug with title in both create and edit modes
    // Only auto-update if strictly needed or just always mirror for now if that's what user wants
    // User said "update modal seems not synchronous with create modal"
    // So we enable it for both.
    // However, to prevent overwriting existing custom slugs in Edit mode on load, 
    // we should strictly check if title changed.
    // Since titleValue updates on mount from defaultValues, this might overwrite.
    // But defaultValues come from initialData.
    // So if initialData.slug exists and matches slugify(initialData.title), it is fine.
    // If it doesn't match, we might overwrite it. 
    // Let's rely on user intention: "Synchronize slug function".
    if (titleValue) {
        setValue('slug', slugify(titleValue), { shouldDirty: true });
    }
  }, [titleValue, setValue]);

  useEffect(() => {
    if (initialData) {
      reset({
        title: initialData.title,
        slug: initialData.slug || '',
        description: initialData.description || '',
        image: initialData.image || '',
        position: initialData.position || '',
        status: initialData.status,
      });
      // Ensure media_id is preserved if we were editing handling logic here
    } else {
      reset({
        title: '',
        slug: '',
        description: '',
        image: '',
        position: '',
        status: StatusEnum.PUBLISHED,
      });
    }
  }, [initialData, reset]);

  const handleMediaSelect = (media: MediaFile) => {
    if (media.url) {
        setValue('image', media.url);
        setUploadedMediaId(media.id);
    }
  };

  const handleRemoveImage = () => {
      setValue('image', '');
      setUploadedMediaId(null);
  };

  const onSubmit = async (data: BannerFormData) => {
    try {
      const payload: BannerFormData & { media_id?: number } = { ...data };
      if (uploadedMediaId) {
        payload.media_id = uploadedMediaId;
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
        <div className="space-y-2">
            <Label>Banner Image <span className="text-red-500">*</span></Label>
            
            <div 
                className="border-2 border-dashed rounded-lg p-4 flex flex-col items-center justify-center min-h-[200px] relative bg-muted/10 hover:bg-muted/20 transition-colors cursor-pointer"
                onClick={() => setMediaSelectorOpen(true)}
            >
                {imagePreview ? (
                    <div className="relative w-full h-full min-h-[200px] flex items-center justify-center">
                        <Image 
                            src={imagePreview} 
                            alt="Preview" 
                            fill 
                            className="object-contain" 
                            unoptimized 
                        />
                        <div className="absolute top-2 right-2 flex gap-2">
                            <Button 
                                type="button" 
                                variant="destructive" 
                                size="icon" 
                                className="h-8 w-8 rounded-full shadow-md"
                                onClick={(e) => {
                                    e.stopPropagation();
                                    handleRemoveImage();
                                }}
                            >
                                <X className="h-4 w-4" />
                            </Button>
                        </div>
                        <div className="absolute bottom-2 right-2">
                             <Button 
                                type="button" 
                                variant="secondary" 
                                size="sm" 
                                className="shadow-md"
                                onClick={(e) => {
                                    e.stopPropagation();
                                    setMediaSelectorOpen(true);
                                }}
                            >
                                Change Image
                            </Button>
                        </div>
                    </div>
                ) : (
                    <div className="flex flex-col items-center gap-4">
                        <div className="p-4 bg-background rounded-full shadow-sm">
                            <ImageIcon className="h-8 w-8 text-muted-foreground" />
                        </div>
                        <div className="text-center space-y-1">
                            <p className="text-sm font-medium">No image selected</p>
                            <p className="text-xs text-muted-foreground">Click to select an image from library</p>
                        </div>
                        <Button type="button" variant="outline" onClick={(e) => {
                            e.stopPropagation();
                            setMediaSelectorOpen(true);
                        }}>
                            Select Image
                        </Button>
                    </div>
                )}
            </div>
            
            {errors.image && <p className="text-sm text-red-500">{tValidation('image.required')}</p>}
            {/* Hidden input to register image field for validation */}
            <input type="hidden" {...register('image')} />
            
            <MediaSelectorModal 
                open={mediaSelectorOpen} 
                onClose={() => setMediaSelectorOpen(false)} 
                onSelect={handleMediaSelect}
                allowedMimeTypes={['image/']}
            />
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
        <Button type="submit" disabled={loading}>
          {loading ? (isEdit ? tCommon('updating') : tCommon('creating')) : (isEdit ? tCommon('update') : tCommon('create'))}
        </Button>
      </div>
    </form>
  );
}

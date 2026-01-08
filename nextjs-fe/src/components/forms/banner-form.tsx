'use client';

import { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
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
import { Status } from '@/shared/enums';
import { bannerSchema, type BannerFormData } from '@/shared/validation/validation';

interface BannerFormProps {
  initialData?: BannerMgmt | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export function BannerForm({ initialData, onSuccess, onCancel }: BannerFormProps) {
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<BannerMgmt>(ENDPOINTS.MANAGEMENT.BANNER);
  const [imageFile, setImageFile] = useState<File | null>(null);
  const [imagePreview, setImagePreview] = useState<string | null>(null);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
  } = useForm<BannerFormData>({
    resolver: zodResolver(bannerSchema),
    defaultValues: {
      rank_order: 0,
      status: Status.ACTIVE,
      is_active: true,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        title: initialData.title,
        image_url: initialData.image_url || '',
        link_url: initialData.link_url || '',
        rank_order: initialData.rank_order,
        status: initialData.status,
        is_active: initialData.is_active,
      });
      if (initialData.image_url) {
        setImagePreview(initialData.image_url);
      }
    } else {
      reset({
        title: '',
        image_url: '',
        link_url: '',
        rank_order: 0,
        status: Status.ACTIVE,
        is_active: true,
      });
      setImagePreview(null);
      setImageFile(null);
    }
  }, [initialData, reset]);

  const onSubmit = async (data: BannerFormData) => {
    try {
      const payload: any = { ...data };
      
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
    } catch (error: any) {
      console.error(error);
      handleBindErrors(error, setError);
    }
  };

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
            <Label htmlFor="title">Title <span className="text-red-500">*</span></Label>
            <Input id="title" {...register('title')} className={errors.title ? 'border-red-500' : ''} />
            {errors.title && <p className="text-sm text-red-500">{errors.title.message}</p>}
          </div>

          <div className="space-y-2">
            <Label htmlFor="link_url">Link URL</Label>
            <Input id="link_url" {...register('link_url')} placeholder="https://example.com" />
          </div>

           <div className="space-y-2">
            <Label htmlFor="rank_order">Display Order</Label>
            <Input id="rank_order" type="number" {...register('rank_order')} />
          </div>
        </div>
      </div>

      <div className="grid grid-cols-2 gap-4">
         <div className="space-y-2">
            <Label htmlFor="status">Status <span className="text-red-500">*</span></Label>
            <Select value={watch('status')?.toString()} onValueChange={(value) => setValue('status', Number(value) as any)}>
              <SelectTrigger><SelectValue /></SelectTrigger>
              <SelectContent>
                <SelectItem value={Status.ACTIVE.toString()}>Active</SelectItem>
                <SelectItem value={Status.INACTIVE.toString()}>Inactive</SelectItem>
              </SelectContent>
            </Select>
          </div>

        <div className="flex items-center gap-2 mt-8">
            <input type="checkbox" id="is_active" {...register('is_active')} className="rounded" />
            <Label htmlFor="is_active">Is Active</Label>
        </div>
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

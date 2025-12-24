'use client';

import { useRouter } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
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
import { FileUpload } from '@/components/upload/file-upload';
import type { SliderMgmt } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, StatusLabels } from '@/lib/types/enums';

const sliderSchema = z.object({
  title: z.string().min(1, 'Title is required'),
  description: z.string().optional(),
  image_url: z.string().min(1, 'Image is required'),
  link_url: z.string().url('Invalid URL').optional().or(z.literal('')),
  rank_order: z.coerce.number().min(0, 'Order must be 0 or greater'),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type SliderFormData = z.infer<typeof sliderSchema>;

export default function CreateSliderPage() {
  const router = useRouter();
  const { create, loading } = useCrud<SliderMgmt>(ENDPOINTS.MANAGEMENT.SLIDER);

  const form = useForm<SliderFormData>({
    resolver: zodResolver(sliderSchema) as any,
    defaultValues: {
      title: '',
      description: '',
      image_url: '',
      link_url: '',
      rank_order: 0,
      status: Status.ACTIVE,
      is_active: true,
    },
  });

  const onSubmit = async (data: SliderFormData) => {
    await create(data);
    router.push('/admin/sliders');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Create Slider"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Sliders', href: '/admin/sliders' },
          { label: 'Create', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-2xl">
        <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
          {/* Image Upload */}
          <div className="space-y-2">
            <Label htmlFor="image_url">Slider Image *</Label>
            <FileUpload
              value={form.watch('image_url')}
              onChange={(url) => form.setValue('image_url', url)}
              accept="image/*"
              maxSize={5}
              disabled={loading}
            />
            {form.formState.errors.image_url && (
              <p className="text-sm text-red-500">{form.formState.errors.image_url.message}</p>
            )}
          </div>

          {/* Title */}
          <div className="space-y-2">
            <Label htmlFor="title">Title *</Label>
            <Input
              id="title"
              {...form.register('title')}
              placeholder="Enter slider title"
              className={form.formState.errors.title ? 'border-red-500' : ''}
            />
            {form.formState.errors.title && (
              <p className="text-sm text-red-500">{form.formState.errors.title.message}</p>
            )}
          </div>

          {/* Description */}
          <div className="space-y-2">
            <Label htmlFor="description">Description</Label>
            <Textarea
              id="description"
              {...form.register('description')}
              placeholder="Enter slider description"
              rows={3}
            />
          </div>

          {/* Link URL */}
          <div className="space-y-2">
            <Label htmlFor="link_url">Link URL</Label>
            <Input
              id="link_url"
              {...form.register('link_url')}
              placeholder="https://example.com"
              type="url"
              className={form.formState.errors.link_url ? 'border-red-500' : ''}
            />
            {form.formState.errors.link_url && (
              <p className="text-sm text-red-500">{form.formState.errors.link_url.message}</p>
            )}
          </div>

          {/* Order */}
          <div className="space-y-2">
            <Label htmlFor="rank_order">Display Order *</Label>
            <Input
              id="rank_order"
              type="number"
              {...form.register('rank_order')}
              placeholder="0"
              min="0"
              className={form.formState.errors.rank_order ? 'border-red-500' : ''}
            />
            {form.formState.errors.rank_order && (
              <p className="text-sm text-red-500">{form.formState.errors.rank_order.message}</p>
            )}
          </div>

          {/* Status */}
          <div className="space-y-2">
            <Label htmlFor="status">Status *</Label>
            <Select
              value={form.watch('status').toString()}
              onValueChange={(value) => form.setValue('status', parseInt(value))}
            >
              <SelectTrigger>
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value={Status.ACTIVE.toString()}>
                  {StatusLabels[Status.ACTIVE]}
                </SelectItem>
                <SelectItem value={Status.INACTIVE.toString()}>
                  {StatusLabels[Status.INACTIVE]}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          {/* Is Active */}
          <div className="flex items-center gap-2">
            <input
              type="checkbox"
              id="is_active"
              {...form.register('is_active')}
              className="rounded"
            />
            <Label htmlFor="is_active" className="cursor-pointer">
              Is Active
            </Label>
          </div>

          {/* Actions */}
          <div className="flex justify-end gap-4 pt-4 border-t">
            <Button
              type="button"
              variant="outline"
              onClick={() => router.push('/admin/sliders')}
              disabled={loading}
            >
              Cancel
            </Button>
            <Button type="submit" disabled={loading}>
              {loading ? 'Creating...' : 'Create Slider'}
            </Button>
          </div>
        </form>
      </div>
    </AdminLayout>
  );
}

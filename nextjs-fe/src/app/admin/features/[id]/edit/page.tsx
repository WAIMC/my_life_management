'use client';

import { useEffect } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { featureService } from '@/services/feature.service';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card } from '@/components/ui/card';
import type { FeatureMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const featureSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  description: z.string().optional(),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type FeatureFormData = z.infer<typeof featureSchema>;

export default function EditFeaturePage() {
  const router = useRouter();
  const params = useParams();
  const featureId = Number(params.id);
  const { update, loading: updateLoading } = useCrud<FeatureMst>(ENDPOINTS.MASTER.FEATURE);

  const { register, handleSubmit, formState: { errors }, setValue, watch, reset } = useForm<FeatureFormData>({
    resolver: zodResolver(featureSchema),
  });

  useEffect(() => {
    const loadFeature = async () => {
      const feature = await featureService.getById(featureId);
      if (feature) {
        reset({
          name: feature.name,
          description: feature.description || '',
          status: feature.status,
          is_active: feature.is_active,
        });
      }
    };
    loadFeature();
  }, [featureId, reset]);

  const onSubmit = async (data: FeatureFormData) => {
    await update(featureId, data);
    router.push('/admin/features');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Edit Feature"
        description="Update feature information"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Features', href: '/admin/features' },
          { label: 'Edit', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-2xl">
        <Card className="p-6">
          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="name">Name <span className="text-red-500">*</span></Label>
              <Input id="name" {...register('name')} className={errors.name ? 'border-red-500' : ''} />
              {errors.name && <p className="text-sm text-red-500">{errors.name.message}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="description">Description</Label>
              <Textarea id="description" {...register('description')} rows={4} />
            </div>

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

            <div className="flex items-center gap-2">
              <input type="checkbox" id="is_active" {...register('is_active')} className="rounded" />
              <Label htmlFor="is_active">Is Active</Label>
            </div>

            <div className="flex justify-end gap-4 pt-4">
              <Button type="button" variant="outline" onClick={() => router.push('/admin/features')}>Cancel</Button>
              <Button type="submit" disabled={updateLoading}>{updateLoading ? 'Updating...' : 'Update Feature'}</Button>
            </div>
          </form>
        </Card>
      </div>
    </AdminLayout>
  );
}

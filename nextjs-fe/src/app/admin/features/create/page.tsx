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

export default function CreateFeaturePage() {
  const router = useRouter();
  const { create, loading } = useCrud<FeatureMst>(ENDPOINTS.MASTER.FEATURE);

  const { register, handleSubmit, formState: { errors }, setValue, watch } = useForm<FeatureFormData>({
    resolver: zodResolver(featureSchema),
    defaultValues: { status: Status.ACTIVE, is_active: true },
  });

  const onSubmit = async (data: FeatureFormData) => {
    await create({ ...data, is_delete: false });
    router.push('/admin/features');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Create Feature"
        description="Add a new feature to the system"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Features', href: '/admin/features' },
          { label: 'Create', isActive: true },
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
              <Button type="submit" disabled={loading}>{loading ? 'Creating...' : 'Create Feature'}</Button>
            </div>
          </form>
        </Card>
      </div>
    </AdminLayout>
  );
}

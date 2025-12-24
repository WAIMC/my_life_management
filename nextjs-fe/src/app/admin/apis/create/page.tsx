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
import type { ApiMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const apiSchema = z.object({
  uri: z.string().min(1, 'URI is required'),
  method: z.string().min(1, 'Method is required'),
  description: z.string().optional(),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type ApiFormData = z.infer<typeof apiSchema>;

export default function CreateApiPage() {
  const router = useRouter();
  const { create, loading } = useCrud<ApiMst>(ENDPOINTS.MASTER.API);

  const { register, handleSubmit, formState: { errors }, setValue, watch } = useForm<ApiFormData>({
    resolver: zodResolver(apiSchema) as any,
    defaultValues: { status: Status.ACTIVE, is_active: true },
  });

  const onSubmit = async (data: ApiFormData) => {
    await create({ ...data, is_delete: false });
    router.push('/admin/apis');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Create API"
        description="Add a new API to the system"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'APIs', href: '/admin/apis' },
          { label: 'Create', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-2xl">
        <Card className="p-6">
          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="uri">URI <span className="text-red-500">*</span></Label>
              <Input id="uri" {...register('uri')} className={errors.uri ? 'border-red-500' : ''} />
              {errors.uri && <p className="text-sm text-red-500">{errors.uri.message}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="method">Method <span className="text-red-500">*</span></Label>
              <Select value={watch('method')} onValueChange={(value) => setValue('method', value)}>
                <SelectTrigger><SelectValue /></SelectTrigger>
                <SelectContent>
                  <SelectItem value="GET">GET</SelectItem>
                  <SelectItem value="POST">POST</SelectItem>
                  <SelectItem value="PUT">PUT</SelectItem>
                  <SelectItem value="DELETE">DELETE</SelectItem>
                </SelectContent>
              </Select>
              {errors.method && <p className="text-sm text-red-500">{errors.method.message}</p>}
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
              <Button type="button" variant="outline" onClick={() => router.push('/admin/apis')}>Cancel</Button>
              <Button type="submit" disabled={loading}>{loading ? 'Creating...' : 'Create API'}</Button>
            </div>
          </form>
        </Card>
      </div>
    </AdminLayout>
  );
}

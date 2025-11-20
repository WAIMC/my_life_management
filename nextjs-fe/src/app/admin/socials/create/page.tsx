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
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card } from '@/components/ui/card';
import type { SocialMgmt } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const schema = z.object({
  platform: z.string().min(1, 'Platform is required'),
  url: z.string().url('Invalid URL'),
  icon: z.string().optional(),
  rank_order: z.coerce.number().min(0),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type FormData = z.infer<typeof schema>;

export default function CreateSocialPage() {
  const router = useRouter();
  const { create, loading } = useCrud<SocialMgmt>(ENDPOINTS.MANAGEMENT.SOCIAL);

  const { register, handleSubmit, formState: { errors }, setValue, watch } = useForm<FormData>({
    resolver: zodResolver(schema),
    defaultValues: { rank_order: 0, status: Status.ACTIVE, is_active: true },
  });

  const onSubmit = async (data: FormData) => {
    await create({ ...data, is_delete: false });
    router.push('/admin/socials');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Create Social Link"
        description="Add a new social media link"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Socials', href: '/admin/socials' },
          { label: 'Create', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-2xl">
        <Card className="p-6">
          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="platform">Platform <span className="text-red-500">*</span></Label>
              <Input id="platform" {...register('platform')} placeholder="e.g., Facebook, Twitter, LinkedIn" className={errors.platform ? 'border-red-500' : ''} />
              {errors.platform && <p className="text-sm text-red-500">{errors.platform.message}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="url">URL <span className="text-red-500">*</span></Label>
              <Input id="url" {...register('url')} placeholder="https://..." className={errors.url ? 'border-red-500' : ''} />
              {errors.url && <p className="text-sm text-red-500">{errors.url.message}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="icon">Icon (CSS class or emoji)</Label>
              <Input id="icon" {...register('icon')} placeholder="e.g., 📱 or fa-facebook" />
            </div>

            <div className="space-y-2">
              <Label htmlFor="rank_order">Display Order <span className="text-red-500">*</span></Label>
              <Input id="rank_order" type="number" {...register('rank_order')} className={errors.rank_order ? 'border-red-500' : ''} />
              {errors.rank_order && <p className="text-sm text-red-500">{errors.rank_order.message}</p>}
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
              <Button type="button" variant="outline" onClick={() => router.push('/admin/socials')}>Cancel</Button>
              <Button type="submit" disabled={loading}>{loading ? 'Creating...' : 'Create'}</Button>
            </div>
          </form>
        </Card>
      </div>
    </AdminLayout>
  );
}

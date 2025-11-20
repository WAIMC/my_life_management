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
import type { SkillMgmt } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const schema = z.object({
  name: z.string().min(1, 'Name is required'),
  description: z.string().optional(),
  icon: z.string().optional(),
  rank_order: z.coerce.number().min(0),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type FormData = z.infer<typeof schema>;

export default function CreateSkillPage() {
  const router = useRouter();
  const { create, loading } = useCrud<SkillMgmt>(ENDPOINTS.MANAGEMENT.SKILL);

  const { register, handleSubmit, formState: { errors }, setValue, watch } = useForm<FormData>({
    resolver: zodResolver(schema),
    defaultValues: { rank_order: 0, status: Status.ACTIVE, is_active: true },
  });

  const onSubmit = async (data: FormData) => {
    await create({ ...data, is_delete: false });
    router.push('/admin/skills');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Create Skill"
        description="Add a new skill"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Skills', href: '/admin/skills' },
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
              <Label htmlFor="icon">Icon</Label>
              <Input id="icon" {...register('icon')} placeholder="e.g., 💻 or fa-code" />
            </div>

            <div className="space-y-2">
              <Label htmlFor="rank_order">Display Order</Label>
              <Input id="rank_order" type="number" {...register('rank_order')} />
            </div>

            <div className="space-y-2">
              <Label htmlFor="status">Status</Label>
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
              <Button type="button" variant="outline" onClick={() => router.push('/admin/skills')}>Cancel</Button>
              <Button type="submit" disabled={loading}>{loading ? 'Creating...' : 'Create'}</Button>
            </div>
          </form>
        </Card>
      </div>
    </AdminLayout>
  );
}

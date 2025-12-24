'use client';

import { useEffect, useState } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { skillService } from '@/services/skill.service';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { HistoryViewer } from '@/components/history';
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

export default function EditSkillPage() {
  const router = useRouter();
  const params = useParams();
  const id = Number(params.id);
  const { update, loading: updateLoading } = useCrud<SkillMgmt>(ENDPOINTS.MANAGEMENT.SKILL);
  const [activeTab, setActiveTab] = useState('details');

  const { register, handleSubmit, formState: { errors }, setValue, watch, reset } = useForm<FormData>({
    resolver: zodResolver(schema) as any,
  });

  useEffect(() => {
    const loadData = async () => {
      const item = await skillService.getById(id);
      if (item) {
        reset({
          name: item.name,
          description: item.description || '',
          icon: item.icon || '',
          rank_order: item.rank_order,
          status: item.status,
          is_active: item.is_active,
        });
      }
    };
    loadData();
  }, [id, reset]);

  const onSubmit = async (data: FormData) => {
    await update(id, data);
    router.push('/admin/skills');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Edit Skill"
        description="Update skill information"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Skills', href: '/admin/skills' },
          { label: 'Edit', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-4xl">
        <Tabs value={activeTab} onValueChange={setActiveTab}>
          <TabsList className="grid w-full grid-cols-2">
            <TabsTrigger value="details">Details</TabsTrigger>
            <TabsTrigger value="history">History</TabsTrigger>
          </TabsList>

          <TabsContent value="details">
            <Card className="p-6">
              <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="name">Name</Label>
              <Input id="name" {...register('name')} className={errors.name ? 'border-red-500' : ''} />
              {errors.name && <p className="text-sm text-red-500">{errors.name.message}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="description">Description</Label>
              <Textarea id="description" {...register('description')} rows={4} />
            </div>

            <div className="space-y-2">
              <Label htmlFor="icon">Icon</Label>
              <Input id="icon" {...register('icon')} />
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
              <Button type="submit" disabled={updateLoading}>{updateLoading ? 'Updating...' : 'Update'}</Button>
            </div>
          </form>
                    </Card>
          </TabsContent>

          <TabsContent value="history">
            <Card className="p-6">
              <HistoryViewer
                entityType="skill"
                entityId={id}
                endpoint={`${ENDPOINTS.MANAGEMENT.SKILL}-hist`}
              />
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </AdminLayout>
  );
}

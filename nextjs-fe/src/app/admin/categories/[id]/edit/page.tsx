'use client';

import { useEffect, useState } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { useJunctionTable } from '@/hooks/useJunctionTable';
import { categoryService } from '@/services/category.service';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { JunctionManager } from '@/components/junction/junction-manager';
import type { CategoryMgmt, SkillMgmt } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const categorySchema = z.object({
  name: z.string().min(1, 'Name is required'),
  description: z.string().optional(),
  icon: z.string().optional(),
  rank_order: z.coerce.number().min(0, 'Order must be 0 or greater'),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type CategoryFormData = z.infer<typeof categorySchema>;

export default function EditCategoryPage() {
  const router = useRouter();
  const params = useParams();
  const categoryId = Number(params.id);
  const { update, loading: updateLoading } = useCrud<CategoryMgmt>(ENDPOINTS.MANAGEMENT.CATEGORY);
  const [activeTab, setActiveTab] = useState('details');

  // Junction table for Category-Skill
  const skillJunction = useJunctionTable<SkillMgmt>(
    ENDPOINTS.JUNCTION.CATEGORY_SKILL,
    ENDPOINTS.MANAGEMENT.SKILL,
    'category_mgmt_id',
    'skill_mgmt_id',
    categoryId
  );

  const { register, handleSubmit, formState: { errors }, setValue, watch, reset } = useForm<CategoryFormData>({
    resolver: zodResolver(categorySchema),
  });

  useEffect(() => {
    const loadCategory = async () => {
      const category = await categoryService.getById(categoryId);
      if (category) {
        reset({
          name: category.name,
          description: category.description || '',
          icon: category.icon || '',
          rank_order: category.rank_order,
          status: category.status,
          is_active: category.is_active,
        });
      }
    };
    loadCategory();
  }, [categoryId, reset]);

  const onSubmit = async (data: CategoryFormData) => {
    await update(categoryId, data);
    router.push('/admin/categories');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Edit Category"
        description="Update category information and manage skills"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Categories', href: '/admin/categories' },
          { label: 'Edit', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-4xl">
        <Tabs value={activeTab} onValueChange={setActiveTab}>
          <TabsList className="grid w-full grid-cols-2">
            <TabsTrigger value="details">Details</TabsTrigger>
            <TabsTrigger value="skills">Skills</TabsTrigger>
          </TabsList>

          {/* Details Tab */}
          <TabsContent value="details">
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
                  <Label htmlFor="icon">Icon (CSS class or emoji)</Label>
                  <Input id="icon" {...register('icon')} placeholder="e.g., 📱 or fa-mobile" />
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
                  <Button type="button" variant="outline" onClick={() => router.push('/admin/categories')}>Cancel</Button>
                  <Button type="submit" disabled={updateLoading}>{updateLoading ? 'Updating...' : 'Update Category'}</Button>
                </div>
              </form>
            </Card>
          </TabsContent>

          {/* Skills Tab */}
          <TabsContent value="skills">
            <Card className="p-6">
              <JunctionManager
                allItems={skillJunction.allItems}
                selectedIds={skillJunction.selectedIds}
                onSelectionChange={skillJunction.setSelectedIds}
                onSave={skillJunction.save}
                loading={skillJunction.loading}
                saving={skillJunction.saving}
                title="Manage Category Skills"
                itemLabel="skills"
                searchPlaceholder="Search skills..."
              />
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </AdminLayout>
  );
}

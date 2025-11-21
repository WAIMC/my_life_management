'use client';

import { useEffect, useState } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { useJunctionTable } from '@/hooks/useJunctionTable';
import { departmentService } from '@/services/department.service';
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
import { Card } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { JunctionManager } from '@/components/junction/junction-manager';
import type { DepartmentMst, PolicyDepartmentMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const departmentSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  description: z.string().optional(),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type DepartmentFormData = z.infer<typeof departmentSchema>;

export default function EditDepartmentPage() {
  const router = useRouter();
  const params = useParams();
  const departmentId = Number(params.id);
  const { update, loading: updateLoading } = useCrud<DepartmentMst>(ENDPOINTS.MASTER.DEPARTMENT);
  const [activeTab, setActiveTab] = useState('details');

  // Junction table for Department-PolicyDepartment
  const policyDepartmentJunction = useJunctionTable<PolicyDepartmentMst>(
    ENDPOINTS.JUNCTION.DEPARTMENT_MANAGEMENT,
    ENDPOINTS.MASTER.POLICY_DEPARTMENT,
    'department_mst_id',
    'policy_department_mst_id',
    departmentId
  );

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
  } = useForm<DepartmentFormData>({
    resolver: zodResolver(departmentSchema),
  });

  useEffect(() => {
    const loadDepartment = async () => {
      const department = await departmentService.getById(departmentId);
      if (department) {
        reset({
          name: department.name,
          description: department.description || '',
          status: department.status,
          is_active: department.is_active,
        });
      }
    };
    loadDepartment();
  }, [departmentId, reset]);

  const onSubmit = async (data: DepartmentFormData) => {
    await update(departmentId, data);
    router.push('/admin/departments');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Edit Department"
        description="Update department information and manage policy departments"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Departments', href: '/admin/departments' },
          { label: 'Edit', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-4xl">
        <Tabs value={activeTab} onValueChange={setActiveTab}>
          <TabsList className="grid w-full grid-cols-2">
            <TabsTrigger value="details">Details</TabsTrigger>
            <TabsTrigger value="policy-departments">Policy Departments</TabsTrigger>
          </TabsList>

          {/* Details Tab */}
          <TabsContent value="details">
            <Card className="p-6">
              <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
                <div className="space-y-2">
                  <Label htmlFor="name">
                    Name <span className="text-red-500">*</span>
                  </Label>
                  <Input
                    id="name"
                    {...register('name')}
                    className={errors.name ? 'border-red-500' : ''}
                  />
                  {errors.name && (
                    <p className="text-sm text-red-500">{errors.name.message}</p>
                  )}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="description">Description</Label>
                  <Textarea
                    id="description"
                    {...register('description')}
                    rows={4}
                  />
                </div>

                <div className="space-y-2">
                  <Label htmlFor="status">
                    Status <span className="text-red-500">*</span>
                  </Label>
                  <Select
                    value={watch('status')?.toString()}
                    onValueChange={(value) => setValue('status', Number(value) as any)}
                  >
                    <SelectTrigger>
                      <SelectValue />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value={Status.ACTIVE.toString()}>Active</SelectItem>
                      <SelectItem value={Status.INACTIVE.toString()}>Inactive</SelectItem>
                    </SelectContent>
                  </Select>
                </div>

                <div className="flex items-center gap-2">
                  <input
                    type="checkbox"
                    id="is_active"
                    {...register('is_active')}
                    className="rounded"
                  />
                  <Label htmlFor="is_active">Is Active</Label>
                </div>

                <div className="flex justify-end gap-4 pt-4">
                  <Button
                    type="button"
                    variant="outline"
                    onClick={() => router.push('/admin/departments')}
                  >
                    Cancel
                  </Button>
                  <Button type="submit" disabled={updateLoading}>
                    {updateLoading ? 'Updating...' : 'Update Department'}
                  </Button>
                </div>
              </form>
            </Card>
          </TabsContent>

          {/* Policy Departments Tab */}
          <TabsContent value="policy-departments">
            <Card className="p-6">
              <JunctionManager
                allItems={policyDepartmentJunction.allItems}
                selectedIds={policyDepartmentJunction.selectedIds}
                onSelectionChange={policyDepartmentJunction.setSelectedIds}
                onSave={policyDepartmentJunction.save}
                loading={policyDepartmentJunction.loading}
                saving={policyDepartmentJunction.saving}
                title="Manage Policy Departments"
                itemLabel="policy departments"
                searchPlaceholder="Search policy departments..."
              />
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </AdminLayout>
  );
}

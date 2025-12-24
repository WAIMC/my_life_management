'use client';

import { useEffect, useState } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { useJunctionTable } from '@/hooks/useJunctionTable';
import { adminService } from '@/services/admin.service';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
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
import { Card } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { JunctionManager } from '@/components/junction/junction-manager';
import { AvatarUpload } from '@/components/crud/avatar-upload';
import { HistoryViewer } from '@/components/history';
import type { AdminMst, RoleMst, DepartmentMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, Gender } from '@/lib/types/enums';

const adminSchema = z.object({
  email: z.string().email('Invalid email address'),
  user_name: z.string().min(3, 'Username must be at least 3 characters'),
  password: z.string().min(8, 'Password must be at least 8 characters').optional(),
  first_name: z.string().min(1, 'First name is required'),
  last_name: z.string().min(1, 'Last name is required'),
  address: z.string().optional(),
  phone_number: z.string().optional(),
  birth: z.string().optional(),
  gender: z.number(),
  status: z.number(),
  is_active: z.boolean(),
});

type AdminFormData = z.infer<typeof adminSchema>;

export default function EditAdminPage() {
  const router = useRouter();
  const params = useParams();
  const adminId = Number(params.id);
  const { update, loading: updateLoading } = useCrud<AdminMst>(ENDPOINTS.MASTER.ADMIN);
  const [activeTab, setActiveTab] = useState('details');
  const [avatarFile, setAvatarFile] = useState<File | null>(null);
  const [avatarPreview, setAvatarPreview] = useState<string | null>(null);

  // Junction table for Admin-Role
  const roleJunction = useJunctionTable<RoleMst>(
    ENDPOINTS.JUNCTION.ADMIN_ROLE,
    ENDPOINTS.MASTER.ROLE,
    'admin_mst_id',
    'role_mst_id',
    adminId
  );

  // Junction table for Admin-Department
  const departmentJunction = useJunctionTable<DepartmentMst>(
    ENDPOINTS.JUNCTION.ADMIN_DEPARTMENT,
    ENDPOINTS.MASTER.DEPARTMENT,
    'admin_mst_id',
    'department_mst_id',
    adminId
  );

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
  } = useForm<AdminFormData>({
    resolver: zodResolver(adminSchema) as any as any,
  });

  useEffect(() => {
    const loadAdmin = async () => {
      const admin = await adminService.getById(adminId);
      if (admin) {
        reset({
          email: admin.email,
          user_name: admin.user_name,
          first_name: admin.first_name,
          last_name: admin.last_name,
          address: admin.address || '',
          phone_number: admin.phone_number || '',
          birth: admin.birth || '',
          gender: admin.gender,
          status: admin.status,
          is_active: admin.is_active,
        });
        // Set avatar preview if exists
        if (admin.avatar) {
          setAvatarPreview(admin.avatar);
        }
      }
    };
    loadAdmin();
  }, [adminId, reset]);

  const onSubmit = async (data: AdminFormData) => {
    const updateData: any = { ...data };
    // Remove password if empty
    if (!updateData.password) {
      delete updateData.password;
    }
    // TODO: Upload avatar if changed
    // if (avatarFile) {
    //   const formData = new FormData();
    //   formData.append('avatar', avatarFile);
    //   const uploadResponse = await apiClient.post('/upload', formData);
    //   updateData.avatar = uploadResponse.data.url;
    // }
    await update(adminId, updateData);
    router.push('/admin/admins');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Edit Admin"
        description="Update administrator information and manage relationships"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Admins', href: '/admin/admins' },
          { label: 'Edit', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-4xl">
        <Tabs value={activeTab} onValueChange={setActiveTab}>
          <TabsList className="grid w-full grid-cols-4">
            <TabsTrigger value="details">Details</TabsTrigger>
            <TabsTrigger value="roles">Roles</TabsTrigger>
            <TabsTrigger value="departments">Departments</TabsTrigger>
            <TabsTrigger value="history">History</TabsTrigger>
          </TabsList>

          {/* Details Tab */}
          <TabsContent value="details">
            <Card className="p-6">
              <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
                {/* Avatar Upload */}
                <AvatarUpload
                  value={avatarPreview ?? undefined}
                  onChange={(file, preview) => {
                    setAvatarFile(file);
                    setAvatarPreview(preview);
                  }}
                  maxSize={5}
                />

                {/* Email */}
                <div className="space-y-2">
                  <Label htmlFor="email">
                    Email <span className="text-red-500">*</span>
                  </Label>
                  <Input
                    id="email"
                    type="email"
                    {...register('email')}
                    className={errors.email ? 'border-red-500' : ''}
                  />
                  {errors.email && (
                    <p className="text-sm text-red-500">{errors.email.message}</p>
                  )}
                </div>

                {/* Username */}
                <div className="space-y-2">
                  <Label htmlFor="user_name">
                    Username <span className="text-red-500">*</span>
                  </Label>
                  <Input
                    id="user_name"
                    {...register('user_name')}
                    className={errors.user_name ? 'border-red-500' : ''}
                  />
                  {errors.user_name && (
                    <p className="text-sm text-red-500">{errors.user_name.message}</p>
                  )}
                </div>

                {/* Password (optional for edit) */}
                <div className="space-y-2">
                  <Label htmlFor="password">
                    Password <span className="text-slate-500">(leave blank to keep current)</span>
                  </Label>
                  <Input
                    id="password"
                    type="password"
                    {...register('password')}
                    className={errors.password ? 'border-red-500' : ''}
                  />
                  {errors.password && (
                    <p className="text-sm text-red-500">{errors.password.message}</p>
                  )}
                </div>

                {/* Name Fields */}
                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <Label htmlFor="first_name">
                      First Name <span className="text-red-500">*</span>
                    </Label>
                    <Input
                      id="first_name"
                      {...register('first_name')}
                      className={errors.first_name ? 'border-red-500' : ''}
                    />
                    {errors.first_name && (
                      <p className="text-sm text-red-500">{errors.first_name.message}</p>
                    )}
                  </div>
                  <div className="space-y-2">
                    <Label htmlFor="last_name">
                      Last Name <span className="text-red-500">*</span>
                    </Label>
                    <Input
                      id="last_name"
                      {...register('last_name')}
                      className={errors.last_name ? 'border-red-500' : ''}
                    />
                    {errors.last_name && (
                      <p className="text-sm text-red-500">{errors.last_name.message}</p>
                    )}
                  </div>
                </div>

                {/* Address */}
                <div className="space-y-2">
                  <Label htmlFor="address">Address</Label>
                  <Input id="address" {...register('address')} />
                </div>

                {/* Phone */}
                <div className="space-y-2">
                  <Label htmlFor="phone_number">Phone Number</Label>
                  <Input id="phone_number" {...register('phone_number')} />
                </div>

                {/* Birth Date */}
                <div className="space-y-2">
                  <Label htmlFor="birth">Birth Date</Label>
                  <Input id="birth" type="date" {...register('birth')} />
                </div>

                {/* Gender & Status */}
                <div className="grid grid-cols-2 gap-4">
                  <div className="space-y-2">
                    <Label htmlFor="gender">
                      Gender <span className="text-red-500">*</span>
                    </Label>
                    <Select
                      value={watch('gender')?.toString()}
                      onValueChange={(value) => setValue('gender', Number(value) as any)}
                    >
                      <SelectTrigger>
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value={Gender.MALE.toString()}>Male</SelectItem>
                        <SelectItem value={Gender.FEMALE.toString()}>Female</SelectItem>
                        <SelectItem value={Gender.OTHER.toString()}>Other</SelectItem>
                      </SelectContent>
                    </Select>
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
                </div>

                {/* Active Checkbox */}
                <div className="flex items-center gap-2">
                  <input
                    type="checkbox"
                    id="is_active"
                    {...register('is_active')}
                    className="rounded"
                  />
                  <Label htmlFor="is_active">Is Active</Label>
                </div>

                {/* Actions */}
                <div className="flex justify-end gap-4 pt-4">
                  <Button
                    type="button"
                    variant="outline"
                    onClick={() => router.push('/admin/admins')}
                  >
                    Cancel
                  </Button>
                  <Button type="submit" disabled={updateLoading}>
                    {updateLoading ? 'Updating...' : 'Update Admin'}
                  </Button>
                </div>
              </form>
            </Card>
          </TabsContent>

          {/* Roles Tab */}
          <TabsContent value="roles">
            <Card className="p-6">
              <JunctionManager
                allItems={roleJunction.allItems}
                selectedIds={roleJunction.selectedIds}
                onSelectionChange={roleJunction.setSelectedIds}
                onSave={roleJunction.save}
                loading={roleJunction.loading}
                saving={roleJunction.saving}
                title="Manage Admin Roles"
                itemLabel="roles"
                searchPlaceholder="Search roles..."
              />
            </Card>
          </TabsContent>

          {/* Departments Tab */}
          <TabsContent value="departments">
            <Card className="p-6">
              <JunctionManager
                allItems={departmentJunction.allItems}
                selectedIds={departmentJunction.selectedIds}
                onSelectionChange={departmentJunction.setSelectedIds}
                onSave={departmentJunction.save}
                loading={departmentJunction.loading}
                saving={departmentJunction.saving}
                title="Manage Admin Departments"
                itemLabel="departments"
                searchPlaceholder="Search departments..."
              />
            </Card>
          </TabsContent>

          {/* History Tab */}
          <TabsContent value="history">
            <Card className="p-6">
              <HistoryViewer
                entityType="admin"
                entityId={adminId}
                endpoint={`${ENDPOINTS.MASTER.ADMIN}-hist`}
              />
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </AdminLayout>
  );
}

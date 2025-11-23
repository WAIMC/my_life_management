'use client';

import { useState } from 'react';
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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Card } from '@/components/ui/card';
import { AvatarUpload } from '@/components/crud/avatar-upload';
import type { AdminMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, Gender } from '@/lib/types/enums';

const adminSchema = z.object({
  email: z.string().email('Invalid email address'),
  user_name: z.string().min(3, 'Username must be at least 3 characters'),
  password: z.string().min(8, 'Password must be at least 8 characters'),
  first_name: z.string().min(1, 'First name is required'),
  last_name: z.string().min(1, 'Last name is required'),
  address: z.string().optional(),
  phone_number: z.string().optional(),
  birth: z.string().optional(),
  gender: z.coerce.number().min(1).max(3),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type AdminFormData = z.infer<typeof adminSchema>;

export default function CreateAdminPage() {
  const router = useRouter();
  const { create, loading } = useCrud<AdminMst>(ENDPOINTS.MASTER.ADMIN);
  const [avatarFile, setAvatarFile] = useState<File | null>(null);
  const [avatarPreview, setAvatarPreview] = useState<string | null>(null);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
  } = useForm<AdminFormData>({
    resolver: zodResolver(adminSchema),
    defaultValues: {
      gender: Gender.MALE,
      status: Status.ACTIVE,
      is_active: true,
    },
  });

  const onSubmit = async (data: AdminFormData) => {
    // TODO: Upload avatar if provided
    // if (avatarFile) {
    //   const formData = new FormData();
    //   formData.append('avatar', avatarFile);
    //   const uploadResponse = await apiClient.post('/upload', formData);
    //   data.avatar = uploadResponse.data.url;
    // }
    
    await create({
      ...data,
      is_delete: false,
    });
    router.push('/admin/admins');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Create Admin"
        description="Add a new administrator to the system"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Admins', href: '/admin/admins' },
          { label: 'Create', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-2xl">
        <Card className="p-6">
          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            {/* Avatar Upload */}
            <AvatarUpload
              value={avatarPreview}
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

            {/* Password */}
            <div className="space-y-2">
              <Label htmlFor="password">
                Password <span className="text-red-500">*</span>
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
              <Button type="submit" disabled={loading}>
                {loading ? 'Creating...' : 'Create Admin'}
              </Button>
            </div>
          </form>
        </Card>
      </div>
    </AdminLayout>
  );
}

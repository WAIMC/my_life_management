'use client';

import { useEffect } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { userService } from '@/services/user.service';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card } from '@/components/ui/card';
import type { UserMgmt } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, Gender } from '@/lib/types/enums';

const userSchema = z.object({
  email: z.string().email('Invalid email address'),
  user_name: z.string().min(3, 'Username must be at least 3 characters'),
  password: z.string().min(6, 'Password must be at least 6 characters').optional().or(z.literal('')),
  first_name: z.string().min(1, 'First name is required'),
  last_name: z.string().min(1, 'Last name is required'),
  address: z.string().optional(),
  phone_number: z.string().optional(),
  birth: z.string().optional(),
  gender: z.coerce.number().min(1).max(3),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type UserFormData = z.infer<typeof userSchema>;

export default function EditUserPage() {
  const router = useRouter();
  const params = useParams();
  const userId = Number(params.id);
  const { update, loading: updateLoading } = useCrud<UserMgmt>(ENDPOINTS.MANAGEMENT.USER);

  const { register, handleSubmit, formState: { errors }, setValue, watch, reset } = useForm<UserFormData>({
    resolver: zodResolver(userSchema),
  });

  useEffect(() => {
    const loadUser = async () => {
      const user = await userService.getById(userId);
      if (user) {
        reset({
          email: user.email,
          user_name: user.user_name,
          first_name: user.first_name,
          last_name: user.last_name,
          address: user.address || '',
          phone_number: user.phone_number || '',
          birth: user.birth || '',
          gender: user.gender,
          status: user.status,
          is_active: user.is_active,
          password: '', // Don't populate password
        });
      }
    };
    loadUser();
  }, [userId, reset]);

  const onSubmit = async (data: UserFormData) => {
    const updateData: Partial<UserMgmt> = { ...data };
    // Only include password if it was changed
    if (!data.password || data.password === '') {
      delete updateData.password;
    }
    await update(userId, updateData);
    router.push('/admin/users');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Edit User"
        description="Update user information"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Users', href: '/admin/users' },
          { label: 'Edit', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-2xl">
        <Card className="p-6">
          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label htmlFor="first_name">First Name <span className="text-red-500">*</span></Label>
                <Input id="first_name" {...register('first_name')} className={errors.first_name ? 'border-red-500' : ''} />
                {errors.first_name && <p className="text-sm text-red-500">{errors.first_name.message}</p>}
              </div>

              <div className="space-y-2">
                <Label htmlFor="last_name">Last Name <span className="text-red-500">*</span></Label>
                <Input id="last_name" {...register('last_name')} className={errors.last_name ? 'border-red-500' : ''} />
                {errors.last_name && <p className="text-sm text-red-500">{errors.last_name.message}</p>}
              </div>
            </div>

            <div className="space-y-2">
              <Label htmlFor="user_name">Username <span className="text-red-500">*</span></Label>
              <Input id="user_name" {...register('user_name')} className={errors.user_name ? 'border-red-500' : ''} />
              {errors.user_name && <p className="text-sm text-red-500">{errors.user_name.message}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="email">Email <span className="text-red-500">*</span></Label>
              <Input id="email" type="email" {...register('email')} className={errors.email ? 'border-red-500' : ''} />
              {errors.email && <p className="text-sm text-red-500">{errors.email.message}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="password">Password (leave blank to keep current)</Label>
              <Input id="password" type="password" {...register('password')} className={errors.password ? 'border-red-500' : ''} />
              {errors.password && <p className="text-sm text-red-500">{errors.password.message}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="phone_number">Phone Number</Label>
              <Input id="phone_number" {...register('phone_number')} />
            </div>

            <div className="space-y-2">
              <Label htmlFor="address">Address</Label>
              <Textarea id="address" {...register('address')} rows={3} />
            </div>

            <div className="grid grid-cols-2 gap-4">
              <div className="space-y-2">
                <Label htmlFor="birth">Birth Date</Label>
                <Input id="birth" type="date" {...register('birth')} />
              </div>

              <div className="space-y-2">
                <Label htmlFor="gender">Gender <span className="text-red-500">*</span></Label>
                <Select value={watch('gender')?.toString()} onValueChange={(value) => setValue('gender', Number(value) as any)}>
                  <SelectTrigger><SelectValue /></SelectTrigger>
                  <SelectContent>
                    <SelectItem value={Gender.MALE.toString()}>Male</SelectItem>
                    <SelectItem value={Gender.FEMALE.toString()}>Female</SelectItem>
                    <SelectItem value={Gender.OTHER.toString()}>Other</SelectItem>
                  </SelectContent>
                </Select>
              </div>
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
              <Button type="button" variant="outline" onClick={() => router.push('/admin/users')}>Cancel</Button>
              <Button type="submit" disabled={updateLoading}>{updateLoading ? 'Updating...' : 'Update User'}</Button>
            </div>
          </form>
        </Card>
      </div>
    </AdminLayout>
  );
}

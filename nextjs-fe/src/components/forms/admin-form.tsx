'use client';

import { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { handleBindErrors } from '@/lib/utils/error-handler';
import { formatDateForBackend, formatDateForInput } from '@/lib/utils/date-formatter';
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
import { AvatarUpload } from '@/components/crud/avatar-upload';
import type { AdminMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { AdminStatus, Gender } from '@/lib/types/enums';

const adminSchema = z.object({
  email: z.string().email('Invalid email address'),
  user_name: z.string().min(3, 'Username must be at least 3 characters'),
  password: z.string().optional(),
  first_name: z.string().min(1, 'First name is required'),
  last_name: z.string().min(1, 'Last name is required'),
  address: z.string().optional(),
  phone_number: z.string().optional(),
  birth: z.string().optional(),
  gender: z.coerce.number(),
  status: z.coerce.number().min(0),
  is_active: z.boolean(),
  avatar: z.string().optional(),
}).refine((data) => {
  // Password is required for new users
  if (!data.password && !data.avatar) { // Checking if it's new (no ID available in schema, but passed in props)
    // We can't easily check for "isNew" inside refine without context.
    // For now, let's handle password validation in the component or assume optional is fine for update and we'll manually check create.
    return true; 
  }
  return true;
});

type AdminFormData = z.infer<typeof adminSchema>;

interface AdminFormProps {
  initialData?: AdminMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export function AdminForm({ initialData, onSuccess, onCancel }: AdminFormProps) {
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<AdminMst>(ENDPOINTS.MASTER.ADMIN);
  const [avatarFile, setAvatarFile] = useState<File | null>(null);
  const [avatarPreview, setAvatarPreview] = useState<string | null>(initialData?.avatar ?? null);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
    setError,
  } = useForm<AdminFormData>({
    resolver: zodResolver(adminSchema),
    defaultValues: {
      gender: Gender.MALE,
      status: AdminStatus.ACTIVE,
      is_active: true,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        email: initialData.email,
        user_name: initialData.user_name,
        first_name: initialData.first_name,
        last_name: initialData.last_name,
        address: initialData.address || '',
        phone_number: initialData.phone_number || '',
        birth: formatDateForInput(initialData.birth),
        gender: initialData.gender,
        status: initialData.status,
        is_active: initialData.is_active,
        avatar: initialData.avatar,
      });
      setAvatarPreview(initialData.avatar ?? null);
    } else {
      reset({
        email: '',
        user_name: '',
        password: '',
        first_name: '',
        last_name: '',
        address: '',
        phone_number: '',
        birth: '',
        gender: Gender.MALE,
        status: AdminStatus.ACTIVE,
        is_active: true,
        avatar: '',
      });
      setAvatarPreview(null);
    }
  }, [initialData, reset]);

  const onSubmit = async (data: AdminFormData) => {
    if (!isEdit && !data.password) {
      setError('password', { type: 'manual', message: 'Password is required for new admins' });
      return;
    }

    try {
      // TODO: Handle avatar upload if avatarFile is present
      // For now, we'll just pass the data. Real implementation would look like:
      // if (avatarFile) {
      //   const uploadData = await upload(avatarFile);
      //   data.avatar = uploadData.url;
      // }

      const payload: any = { ...data };

      if (data.birth) {
        payload.birth = formatDateForBackend(data.birth);
      }

      if (isEdit && initialData) {
        if (!payload.password) {
          delete payload.password;
        }
        await update(initialData.id, payload);
      } else {
        await create({
          ...payload,
          is_delete: false,
        });
      }
      onSuccess();
    } catch (error: any) {
      console.error(error);
      handleBindErrors(error, setError);
    }
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="flex justify-center mb-4">
         <AvatarUpload
            value={avatarPreview ?? undefined}
            onChange={(file, preview) => {
              setAvatarFile(file);
              setAvatarPreview(preview);
              // In a real scenario, we might upload immediately or wait for submit
            }}
            maxSize={5}
          />
      </div>

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

      <div className="grid grid-cols-2 gap-4">
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
      </div>

       <div className="space-y-2">
        <Label htmlFor="password">
          Password {isEdit ? '(Leave blank to keep current)' : <span className="text-red-500">*</span>}
        </Label>
        <Input
          id="password"
          type="password"
          {...register('password')}
          className={errors.password ? 'border-red-500' : ''}
          placeholder={isEdit ? '••••••••' : 'Enter password'}
        />
        {errors.password && (
          <p className="text-sm text-red-500">{errors.password.message}</p>
        )}
      </div>

       <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="phone_number">Phone Number</Label>
          <Input
            id="phone_number"
            {...register('phone_number')}
            className={errors.phone_number ? 'border-red-500' : ''}
          />
          {errors.phone_number && (
            <p className="text-sm text-red-500">{errors.phone_number.message}</p>
          )}
        </div>
        <div className="space-y-2">
          <Label htmlFor="birth">Birth Date</Label>
          <Input
            id="birth"
            type="date"
            {...register('birth')}
            className={errors.birth ? 'border-red-500' : ''}
          />
          {errors.birth && (
            <p className="text-sm text-red-500">{errors.birth.message}</p>
          )}
        </div>
      </div>

      <div className="space-y-2">
        <Label htmlFor="address">Address</Label>
        <Input
          id="address"
          {...register('address')}
          className={errors.address ? 'border-red-500' : ''}
        />
        {errors.address && (
          <p className="text-sm text-red-500">{errors.address.message}</p>
        )}
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="gender">
            Gender <span className="text-red-500">*</span>
          </Label>
          <Select
            value={watch('gender')?.toString() ?? ''}
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
          {errors.gender && (
            <p className="text-sm text-red-500">{errors.gender.message}</p>
          )}
        </div>
        <div className="space-y-2">
          <Label htmlFor="status">
            Status <span className="text-red-500">*</span>
          </Label>
          <Select
            value={watch('status')?.toString() ?? ''}
            onValueChange={(value) => setValue('status', Number(value) as any)}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={AdminStatus.ACTIVE.toString()}>Active</SelectItem>
              <SelectItem value={AdminStatus.INACTIVE.toString()}>Inactive</SelectItem>
              <SelectItem value={AdminStatus.WAITING.toString()}>Waiting</SelectItem>
              <SelectItem value={AdminStatus.SUSPENDED.toString()}>Suspended</SelectItem>
            </SelectContent>
          </Select>
          {errors.status && (
            <p className="text-sm text-red-500">{errors.status.message}</p>
          )}
        </div>
      </div>

      <div className="flex items-center gap-2 mt-4">
        <input
          type="checkbox"
          id="is_active"
          {...register('is_active')}
          className="rounded"
        />
        <Label htmlFor="is_active">Is Active</Label>
      </div>

      <div className="flex justify-end gap-2 pt-4">
        <Button type="button" variant="outline" onClick={onCancel}>
          Cancel
        </Button>
        <Button type="submit" disabled={loading}>
          {loading ? (isEdit ? 'Updating...' : 'Creating...') : (isEdit ? 'Update' : 'Create')}
        </Button>
      </div>
    </form>
  );
}

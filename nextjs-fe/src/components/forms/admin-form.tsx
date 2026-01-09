'use client';
'use no memo';

import { useEffect, useState } from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslations } from 'next-intl';
import { useCrud } from '@/shared/hooks/useCrud';
import { handleBindErrors } from '@/shared/utils/error-handler';
import { formatDateForBackend, formatDateForInput } from '@/shared/utils/date-formatter';
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
import { AvatarUpload } from '@/components/common/avatar-upload';
import type { AdminMst } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { AdminStatus, Gender } from '@/shared/enums';
import { adminSchema, type AdminFormData } from '@/shared/validation/validation';
import type { AdminFormProps } from './types';

export function AdminForm({ initialData, onSuccess, onCancel }: AdminFormProps) {
  const tCommon = useTranslations('common');
  const tForms = useTranslations('forms.placeholders');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<AdminMst>(ENDPOINTS.MASTER.ADMIN);
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  const [avatarFile, setAvatarFile] = useState<File | null>(null);
  const [avatarPreview, setAvatarPreview] = useState<string | null>(() => initialData?.avatar ?? null);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
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

      const payload = { ...data };

      if (data.birth) {
        (payload as AdminFormData & { birth?: string }).birth = formatDateForBackend(data.birth);
      }

      if (isEdit && initialData) {
        if (!payload.password) {
          delete (payload as AdminFormData & { password?: string }).password;
        }
        await update(initialData.id, payload);
      } else {
        await create({
          ...payload,
          is_delete: false,
        } as AdminFormData & { is_delete: boolean });
      }
      onSuccess();
    } catch (error: unknown) {
      console.error(error);
      handleBindErrors(error, setError);
    }
  };

  // Use useWatch hook instead of watch() to avoid React Compiler issues
  const genderValue = useWatch({ control, name: 'gender' });
  const statusValue = useWatch({ control, name: 'status' });

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
          placeholder={isEdit ? tForms('passwordHidden') : tCommon('enterPassword')}
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
            value={genderValue?.toString() ?? ''}
            onValueChange={(value) => setValue('gender', Number(value) as Gender)}
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
            value={statusValue?.toString() ?? ''}
            onValueChange={(value) => setValue('status', Number(value) as AdminStatus)}
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
          {tCommon('cancel')}
        </Button>
        <Button type="submit" disabled={loading}>
          {loading ? (isEdit ? tCommon('updating') : tCommon('creating')) : (isEdit ? tCommon('update') : tCommon('create'))}
        </Button>
      </div>
    </form>
  );
}

'use client';

import { useEffect, useState } from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslations } from 'next-intl';
import { useCrud } from '@/shared/hooks/useCrud';
import { useActionLock } from '@/shared/hooks/useActionLock';
import { UI_CONSTANTS } from '@/shared/config';
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
import { AdminStatus, Gender, GenderLabels, AdminStatusLabels } from '@/shared/enums';
import { UPLOAD_CONFIG } from '@/shared/config/constant';
import { getAdminSchema, type AdminFormData } from '@/shared/validation/validation';
import type { AdminFormProps } from './types';

export function AdminForm({ initialData, onSuccess, onCancel }: AdminFormProps) {
  const tCommon = useTranslations('common');
  const tForms = useTranslations('forms.placeholders');
  const tLabels = useTranslations('forms.labels');
  const tValidation = useTranslations('validation');
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
    resolver: zodResolver(getAdminSchema(tValidation)),
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
        gender: initialData.gender ? Number(initialData.gender) : Gender.MALE,
        status: initialData.status !== undefined ? Number(initialData.status) : AdminStatus.ACTIVE,
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

  const { execute, isLoading: isActionProcessing } = useActionLock({ delay: UI_CONSTANTS.ACTION_DELAY_MS });

  const onSubmit = async (data: AdminFormData) => {
    await execute(async () => {
      if (!isEdit && !data.password) {
        setError('password', { type: 'manual', message: tCommon('passwordRequired') });
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
    });
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
          maxSize={UPLOAD_CONFIG.DEFAULT_AVATAR_MAX_SIZE_MB}
        />
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="first_name">
            {tLabels('firstName')} <span className="text-red-500">*</span>
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
            {tLabels('lastName')} <span className="text-red-500">*</span>
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
            {tLabels('email')} <span className="text-red-500">*</span>
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
            {tLabels('username')} <span className="text-red-500">*</span>
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
          {tLabels('password')} {isEdit ? `(${tForms('leaveBlankToKeepCurrent')})` : <span className="text-red-500">*</span>}
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
          <Label htmlFor="phone_number">{tLabels('phoneNumber')}</Label>
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
          <Label htmlFor="birth">{tLabels('birthDate')}</Label>
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
        <Label htmlFor="address">{tLabels('address')}</Label>
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
            {tLabels('gender')} <span className="text-red-500">*</span>
          </Label>
          <Select
            key={String(genderValue)}
            value={genderValue !== undefined && genderValue !== null ? String(genderValue) : ''}
            onValueChange={(value) => setValue('gender', Number(value) as Gender)}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={Gender.MALE.toString()}>{GenderLabels[Gender.MALE]}</SelectItem>
              <SelectItem value={Gender.FEMALE.toString()}>{GenderLabels[Gender.FEMALE]}</SelectItem>
              <SelectItem value={Gender.OTHER.toString()}>{GenderLabels[Gender.OTHER]}</SelectItem>
            </SelectContent>
          </Select>
          {errors.gender && (
            <p className="text-sm text-red-500">{errors.gender.message}</p>
          )}
        </div>
        <div className="space-y-2">
          <Label htmlFor="status">
            {tLabels('status')} <span className="text-red-500">*</span>
          </Label>
          <Select
            key={String(statusValue)}
            value={statusValue !== undefined && statusValue !== null ? String(statusValue) : ''}
            onValueChange={(value) => setValue('status', Number(value) as AdminStatus)}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={AdminStatus.ACTIVE.toString()}>{AdminStatusLabels[AdminStatus.ACTIVE]}</SelectItem>
              <SelectItem value={AdminStatus.INACTIVE.toString()}>{AdminStatusLabels[AdminStatus.INACTIVE]}</SelectItem>
              <SelectItem value={AdminStatus.WAITING.toString()}>{AdminStatusLabels[AdminStatus.WAITING]}</SelectItem>
              <SelectItem value={AdminStatus.SUSPENDED.toString()}>{AdminStatusLabels[AdminStatus.SUSPENDED]}</SelectItem>
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
        <Label htmlFor="is_active">{tLabels('isActive')}</Label>
      </div>

      <div className="flex justify-end gap-2 pt-4">
        <Button type="button" variant="outline" onClick={onCancel} disabled={loading || isActionProcessing}>
          {tCommon('cancel')}
        </Button>
        <Button type="submit" disabled={loading || isActionProcessing}>
          {loading || isActionProcessing ? (isEdit ? tCommon('updating') : tCommon('creating')) : (isEdit ? tCommon('update') : tCommon('create'))}
        </Button>
      </div>
    </form>
  );
}

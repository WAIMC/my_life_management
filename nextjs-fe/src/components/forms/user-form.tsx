'use client';

import { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useCrud } from '@/shared/hooks/useCrud';
import { handleBindErrors } from '@/shared/utils/error-handler';
import { format } from 'date-fns';
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
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { HistoryViewer } from '@/components/features/history/history-viewer';
import { AvatarUpload } from '@/components/common/avatar-upload';
import type { UserMgmt } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { Status, Gender } from '@/shared/enums';
import { userSchema, type UserFormData } from '@/shared/validation/validation';

interface UserFormProps {
  initialData?: UserMgmt | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export function UserForm({ initialData, onSuccess, onCancel }: UserFormProps) {
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<UserMgmt>(ENDPOINTS.MANAGEMENT.USER);
  const [avatarFile, setAvatarFile] = useState<File | null>(null);
  const [avatarPreview, setAvatarPreview] = useState<string | null>(null);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
  } = useForm<UserFormData>({
    resolver: zodResolver(userSchema),
    defaultValues: {
      gender: Gender.MALE,
      status: Status.ACTIVE,
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
        birth: initialData.birth || '',
        gender: initialData.gender,
        status: initialData.status,
        is_active: initialData.is_active,
        password: '',
      });
      if (initialData.avatar) {
        setAvatarPreview(initialData.avatar);
      }
    } else {
      reset({
        email: '',
        user_name: '',
        first_name: '',
        last_name: '',
        address: '',
        phone_number: '',
        birth: '',
        gender: Gender.MALE,
        status: Status.ACTIVE,
        is_active: true,
        password: '',
      });
      setAvatarPreview(null);
      setAvatarFile(null);
    }
  }, [initialData, reset]);

  const onSubmit = async (data: UserFormData) => {
    try {
      const payload: any = { ...data };
      
      if (isEdit && (!data.password || data.password === '')) {
        delete payload.password;
      }

      if (data.birth) {
        payload.birth = format(new Date(data.birth), 'dd/MM/yyyy');
      }
      
      // Handle avatar upload logic or payload construction here if needed
      // Currently just passing fields, assuming backend or pre-upload handles file
      // NOTE: Real implementation would need FormData or separate upload call if file selected
      
      if (isEdit && initialData) {
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

  const FormContent = (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="flex justify-center mb-4">
        <AvatarUpload
          value={avatarPreview ?? undefined}
          onChange={(file, preview) => {
            setAvatarFile(file);
            setAvatarPreview(preview);
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
        <Label htmlFor="password">
          {isEdit ? 'Password (leave blank to keep current)' : 'Password'} {(!isEdit) && <span className="text-red-500">*</span>}
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
        <Textarea
          id="address"
          {...register('address')}
          rows={2}
          className={errors.address ? 'border-red-500' : ''}
        />
        {errors.address && (
          <p className="text-sm text-red-500">{errors.address.message}</p>
        )}
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="gender">Gender <span className="text-red-500">*</span></Label>
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

  if (!isEdit) {
    return FormContent;
  }

  return (
    <Tabs defaultValue="details" className="w-full">
      <TabsList className="grid w-full grid-cols-2">
        <TabsTrigger value="details">Details</TabsTrigger>
        <TabsTrigger value="history">History</TabsTrigger>
      </TabsList>
      <TabsContent value="details" className="mt-4">
        {FormContent}
      </TabsContent>
      <TabsContent value="history" className="mt-4">
        <div className="h-[400px] overflow-y-auto pr-2">
          {initialData && (
            <HistoryViewer
              entityType="user"
              entityId={initialData.id}
              endpoint={`${ENDPOINTS.MANAGEMENT.USER}-hist`}
            />
          )}
        </div>
      </TabsContent>
    </Tabs>
  );
}

'use client';

import { useEffect } from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslations } from 'next-intl';
import { useCrud } from '@/shared/hooks/useCrud';
import { useActionLock } from '@/shared/hooks/useActionLock';
import { handleBindErrors } from '@/shared/utils/error-handler';
import { UI_CONSTANTS } from '@/shared/config';
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
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { HistoryViewer } from '@/components/features/history/history-viewer';
import type { RoleMst } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { IsActive, IsActiveLabels } from '@/shared/enums';
import { getRoleSchema, type RoleFormData } from '@/shared/validation/validation';
import type { RoleFormProps } from './types';

export function RoleForm({ initialData, onSuccess, onCancel }: RoleFormProps) {
  const tCommon = useTranslations('common');
  const tLabels = useTranslations('forms.labels');
  const tValidation = useTranslations('validation');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<RoleMst>(ENDPOINTS.MASTER.ROLE);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<RoleFormData>({
    resolver: zodResolver(getRoleSchema(tValidation)),
    defaultValues: {
      is_active: true,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        name: initialData.name,
        permission: initialData.permission,
        is_active: initialData.is_active,
      });
    } else {
      reset({
        name: '',
        permission: '',
        is_active: true,
      });
    }
  }, [initialData, reset]);

  const { execute, isLoading: isActionProcessing } = useActionLock({ delay: UI_CONSTANTS.ACTION_DELAY_MS });

  const onSubmit = async (data: RoleFormData) => {
    await execute(async () => {
      try {
        if (isEdit && initialData) {
          await update(initialData.id, {
            ...data,
            is_delete: false,
          });
        } else {
          await create({
            ...data,
            is_delete: false,
          });
        }
        onSuccess();
      } catch (error: unknown) {
        console.error(error);
        handleBindErrors(error, setError);
      }
    });
  };

  // Use useWatch hook instead of watch() to avoid React Compiler issues
  const isActiveValue = useWatch({ control, name: 'is_active' });

  const FormContent = (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="space-y-2">
        <Label htmlFor="name">
          {tLabels('name')} <span className="text-red-500">*</span>
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
        <Label htmlFor="permission">
          {tLabels('permission')} <span className="text-red-500">*</span>
        </Label>
        <Input
          id="permission"
          {...register('permission')}
          className={errors.permission ? 'border-red-500' : ''}
        />
        {errors.permission && (
          <p className="text-sm text-red-500">{errors.permission.message}</p>
        )}
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="is_active">
            {tLabels('status')} <span className="text-red-500">*</span>
          </Label>
          <Select
            value={isActiveValue ? IsActive.TRUE.toString() : IsActive.FALSE.toString()}
            onValueChange={(value) => setValue('is_active', value === IsActive.TRUE.toString())}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={IsActive.TRUE.toString()}>{IsActiveLabels[IsActive.TRUE]}</SelectItem>
              <SelectItem value={IsActive.FALSE.toString()}>{IsActiveLabels[IsActive.FALSE]}</SelectItem>
            </SelectContent>
          </Select>
          {errors.is_active && (
            <p className="text-sm text-red-500">{errors.is_active.message}</p>
          )}
        </div>
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

  if (!isEdit) {
    return FormContent;
  }

  return (
    <Tabs defaultValue="details" className="w-full">
      <TabsList className="grid w-full grid-cols-2">
        <TabsTrigger value="details">{tCommon('details')}</TabsTrigger>
        <TabsTrigger value="history">{tCommon('history')}</TabsTrigger>
      </TabsList>
      <TabsContent value="details" className="mt-4">
        {FormContent}
      </TabsContent>
      <TabsContent value="history" className="mt-4">
        <div className="h-[400px] overflow-y-auto pr-2">
          {initialData && (
            <HistoryViewer
              entityType="role"
              entityId={initialData.id}
              endpoint={`${ENDPOINTS.MASTER.ROLE}-hist`}
            />
          )}
        </div>
      </TabsContent>
    </Tabs>
  );
}

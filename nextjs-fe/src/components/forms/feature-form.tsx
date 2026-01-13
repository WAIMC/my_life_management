'use client';

import { useEffect } from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslations } from 'next-intl';
import { useCrud } from '@/shared/hooks/useCrud';
import { handleBindErrors } from '@/shared/utils/error-handler';
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
import type { FeatureMst } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { FeatureStatus, IsActive, FeatureStatusLabels } from '@/shared/enums';
import { getFeatureSchema, type FeatureFormData } from '@/shared/validation/validation';
import type { FeatureFormProps } from './types';

export function FeatureForm({ initialData, onSuccess, onCancel }: FeatureFormProps) {
  const tCommon = useTranslations('common');
  const tLabels = useTranslations('forms.labels');
  const tValidation = useTranslations('validation');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<FeatureMst>(ENDPOINTS.MASTER.FEATURE);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<FeatureFormData>({
    resolver: zodResolver(getFeatureSchema(tValidation)),
    defaultValues: {
      status: FeatureStatus.ACTIVE as unknown as IsActive,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        name: initialData.name,
        description: initialData.description || '',
        status: initialData.status,
      });
    } else {
      reset({
        name: '',
        description: '',
        status: FeatureStatus.ACTIVE as unknown as IsActive,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: FeatureFormData) => {
    try {
      const payload = { ...data };

      if (isEdit && initialData) {
        if (!initialData) return;
        await update(initialData.id, {
          ...payload,
          is_delete: initialData.is_delete || false,
        });
      } else {
        await create({
          ...payload,
          is_delete: false,
        });
      }
      onSuccess();
    } catch (error: unknown) {
      // Error is handled by useCrud toast
      console.error(error);
      handleBindErrors(error, setError);
    }
  };

  // Use useWatch hook instead of watch() to avoid React Compiler issues
  const statusValue = useWatch({ control, name: 'status' });

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
        <Label htmlFor="description">{tLabels('description')}</Label>
        <Textarea id="description" {...register('description')} rows={3} />
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="status">
            {tLabels('status')} <span className="text-red-500">*</span>
          </Label>
          <Select
            value={statusValue?.toString()}
            onValueChange={(value) => setValue('status', Number(value) as IsActive)}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={FeatureStatus.ACTIVE.toString()}>{FeatureStatusLabels[FeatureStatus.ACTIVE]}</SelectItem>
              <SelectItem value={FeatureStatus.INACTIVE.toString()}>{FeatureStatusLabels[FeatureStatus.INACTIVE]}</SelectItem>
            </SelectContent>
          </Select>
        </div>

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
              entityType="feature"
              entityId={initialData.id}
              endpoint={`${ENDPOINTS.MASTER.FEATURE}-hist`}
            />
          )}
        </div>
      </TabsContent>
    </Tabs>
  );
}

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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { HistoryViewer } from '@/components/features/history/history-viewer';
import type { SkillMgmt } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { FORM_DEFAULTS } from '@/shared/config/constant';
import { SkillStatus, SkillStatusLabels, IsActive } from '@/shared/enums';
import { skillSchema, type SkillFormData } from '@/shared/validation/validation';
import type { SkillFormProps } from './types';

export function SkillForm({ initialData, onSuccess, onCancel }: SkillFormProps) {
  const tCommon = useTranslations('common');
  const tForms = useTranslations('forms.placeholders');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<SkillMgmt>(ENDPOINTS.MANAGEMENT.SKILL);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<SkillFormData>({
    resolver: zodResolver(skillSchema),
    defaultValues: {
      rank_order: FORM_DEFAULTS.RANK_ORDER,
      status: SkillStatus.ACTIVE as unknown as IsActive,
      is_display: true,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        name: initialData.name,
        slug: initialData.slug || '',
        rank_order: initialData.rank_order,
        status: initialData.status,
        is_display: initialData.is_display ?? true,
      });
    } else {
      reset({
        name: '',
        slug: '',
        rank_order: FORM_DEFAULTS.RANK_ORDER,
        status: SkillStatus.ACTIVE as unknown as IsActive,
        is_display: true,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: SkillFormData) => {
    try {
      // Convert string to number for rank_order
      const payload = {
        ...data,
        rank_order: Number(data.rank_order),
      };
      
      if (isEdit && initialData) {
        await update(initialData.id, payload);
      } else {
        await create({
          ...payload,
          is_delete: false,
        });
      }
      onSuccess();
    } catch (error: unknown) {
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
          {tCommon('name')} <span className="text-red-500">*</span>
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
        <Label htmlFor="slug">{tCommon('slug')}</Label>
        <Input id="slug" {...register('slug')} placeholder={tForms('slugExample')} />
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="rank_order">
            {tCommon('displayOrder')} <span className="text-red-500">*</span>
          </Label>
          <Input
            id="rank_order"
            type="number"
            {...register('rank_order')}
            className={errors.rank_order ? 'border-red-500' : ''}
          />
          {errors.rank_order && (
            <p className="text-sm text-red-500">{errors.rank_order.message}</p>
          )}
        </div>

        <div className="space-y-2">
          <Label htmlFor="status">
            {tCommon('status')} <span className="text-red-500">*</span>
          </Label>
          <Select
            value={statusValue?.toString()}
            onValueChange={(value) => setValue('status', Number(value) as IsActive)}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={SkillStatus.ACTIVE.toString()}>{SkillStatusLabels[SkillStatus.ACTIVE]}</SelectItem>
              <SelectItem value={SkillStatus.INACTIVE.toString()}>{SkillStatusLabels[SkillStatus.INACTIVE]}</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <div className="flex items-center gap-2 mt-4">
        <input
          type="checkbox"
          id="is_display"
          {...register('is_display')}
          className="rounded"
        />
        <Label htmlFor="is_display">{tCommon('isDisplay')}</Label>
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
              entityType="skill"
              entityId={initialData.id}
              endpoint={`${ENDPOINTS.MANAGEMENT.SKILL}-hist`}
            />
          )}
        </div>
      </TabsContent>
    </Tabs>
  );
}

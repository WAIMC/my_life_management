'use client';

import { useEffect } from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslations } from 'next-intl';
import { useCrud } from '@/shared/hooks/useCrud';
import { useApiData } from '@/shared/hooks/useApiData';
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
import type { SkillDescriptionMgmt, SkillMgmt } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { SORT_ORDER, SORT_FIELDS, PAGINATION, FORM_DEFAULTS } from '@/shared/config/constant';
import { StatusEnum, StatusEnumLabels } from '@/shared/enums';
import { getSkillDescriptionSchema, type SkillDescriptionFormData } from '@/shared/validation/validation';
import type { SkillDescriptionFormProps } from './types';

export function SkillDescriptionForm({ initialData, onSuccess, onCancel }: SkillDescriptionFormProps) {
  const tCommon = useTranslations('common');
  const tForms = useTranslations('forms.placeholders');
  const tValidation = useTranslations('validation');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<SkillDescriptionMgmt>(ENDPOINTS.MANAGEMENT.SKILL_DESCRIPTION);

  // Fetch skills for the dropdown
  // We'll fetch all active skills (no pagination effectively, or big page size)
  // For simplicity assuming reasonable number of skills
  const { data: skills, loading: skillsLoading } = useApiData<SkillMgmt>(
    ENDPOINTS.MANAGEMENT.SKILL,
    { page: PAGINATION.DEFAULT_PAGE, per_page: PAGINATION.MAX_PER_PAGE, sort_by: SORT_FIELDS.NAME, sort_order: SORT_ORDER.ASC }
  );

  // Transform initial data to form data
  const getFormValues = (data: typeof initialData): SkillDescriptionFormData => {
    if (data) {
      return {
        skill_mgmt_id: Number(data.skill_mgmt_id),
        parent_id: data.parent_id !== null ? Number(data.parent_id) : 0,
        title: data.title,
        summary: data.summary || '',
        article: data.article || '',
        rank_order: Number(data.rank_order),
        status: Number(data.status) as StatusEnum,
        is_display: Boolean(data.is_display),
      };
    }
    return {
      skill_mgmt_id: 0, // Using 0 as default for number input, though validation requires min 1
      parent_id: 0,
      title: '',
      summary: '',
      article: '',
      rank_order: FORM_DEFAULTS.RANK_ORDER,
      status: StatusEnum.PUBLISHED,
      is_display: true,
    };
  };

  const defaultValues = getFormValues(initialData);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<SkillDescriptionFormData>({
    resolver: zodResolver(getSkillDescriptionSchema(tValidation)),
    defaultValues: defaultValues,
  });

  useEffect(() => {
    reset(getFormValues(initialData));
  }, [initialData, reset]);

  const onSubmit = async (data: SkillDescriptionFormData) => {
    try {
      // Convert string to number for numeric fields
      const payload = {
        ...data,
        skill_mgmt_id: Number(data.skill_mgmt_id),
        parent_id: Number(data.parent_id ?? 0),
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
  const skillMgmtIdValue = useWatch({ control, name: 'skill_mgmt_id' });
  const statusValue = useWatch({ control, name: 'status' });

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="space-y-2">
        <Label htmlFor="parent_id">
          {tCommon('parentId')}
        </Label>
        <Input
          id="parent_id"
          type="number"
          {...register('parent_id', { valueAsNumber: true })}
        />
      </div>
      
      <div className="space-y-2">
        <Label htmlFor="skill_mgmt_id">
          {tCommon('skill')} <span className="text-red-500">*</span>
        </Label>
        <Select
          value={skillMgmtIdValue ? String(skillMgmtIdValue) : ''}
          onValueChange={(value) => setValue('skill_mgmt_id', Number(value))}
          disabled={skillsLoading}
        >
          <SelectTrigger>
            <SelectValue placeholder={skillsLoading ? tCommon('loading') : tForms('selectSkill')} />
          </SelectTrigger>
          <SelectContent>
            {skills.map((skill) => (
              <SelectItem key={skill.id} value={skill.id.toString()}>
                {skill.name}
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
        {errors.skill_mgmt_id && (
          <p className="text-sm text-red-500">{errors.skill_mgmt_id.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="title">
          {tCommon('title')} <span className="text-red-500">*</span>
        </Label>
        <Input
          id="title"
          {...register('title')}
          className={errors.title ? 'border-red-500' : ''}
        />
        {errors.title && (
          <p className="text-sm text-red-500">{errors.title.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="summary">{tCommon('summary')}</Label>
        <Textarea
          id="summary"
          {...register('summary')}
          rows={3}
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="article">{tCommon('article')}</Label>
        <Textarea
          id="article"
          {...register('article')}
          rows={5}
        />
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="rank_order">
            {tCommon('displayOrder')} <span className="text-red-500">*</span>
          </Label>
          <Input
            id="rank_order"
            type="number"
            {...register('rank_order', { valueAsNumber: true })}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="status">
            {tCommon('status')} <span className="text-red-500">*</span>
          </Label>
          <Select
            value={statusValue !== undefined ? String(statusValue) : ''}
            onValueChange={(value) => setValue('status', Number(value) as StatusEnum)}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={String(StatusEnum.PUBLISHED)}>{StatusEnumLabels[StatusEnum.PUBLISHED]}</SelectItem>
              <SelectItem value={String(StatusEnum.DRAFT)}>{StatusEnumLabels[StatusEnum.DRAFT]}</SelectItem>
              <SelectItem value={String(StatusEnum.ARCHIVED)}>{StatusEnumLabels[StatusEnum.ARCHIVED]}</SelectItem>
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
}

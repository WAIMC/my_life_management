'use client';

import { useEffect, useState } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
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
import { Status } from '@/shared/enums';
import { skillDescriptionSchema, type SkillDescriptionFormData } from '@/shared/validation/validation';

interface SkillDescriptionFormProps {
  initialData?: SkillDescriptionMgmt | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export function SkillDescriptionForm({ initialData, onSuccess, onCancel }: SkillDescriptionFormProps) {
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<SkillDescriptionMgmt>(ENDPOINTS.MANAGEMENT.SKILL_DESCRIPTION);

  // Fetch skills for the dropdown
  // We'll fetch all active skills (no pagination effectively, or big page size)
  // For simplicity assuming reasonable number of skills
  const { data: skills, loading: skillsLoading } = useApiData<SkillMgmt>(
    ENDPOINTS.MASTER.SKILL,
    { page: 1, per_page: 1000, sort_by: 'name', sort_order: 'asc', filters: { status: Status.ACTIVE } }
  );

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
  } = useForm<SkillDescriptionFormData>({
    resolver: zodResolver(skillDescriptionSchema),
    defaultValues: {
      rank_order: 0,
      status: Status.ACTIVE,
      is_active: true,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        skill_mgmt_id: initialData.skill_mgmt_id,
        description: initialData.description,
        rank_order: initialData.rank_order,
        status: initialData.status,
        is_active: initialData.is_active,
      });
    } else {
      reset({
        skill_mgmt_id: 0,
        description: '',
        rank_order: 0,
        status: Status.ACTIVE,
        is_active: true,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: SkillDescriptionFormData) => {
    try {
      if (isEdit && initialData) {
        await update(initialData.id, data);
      } else {
        await create({
          ...data,
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
      <div className="space-y-2">
        <Label htmlFor="skill_mgmt_id">
          Skill <span className="text-red-500">*</span>
        </Label>
        <Select
          value={watch('skill_mgmt_id')?.toString()}
          onValueChange={(value) => setValue('skill_mgmt_id', Number(value))}
          disabled={skillsLoading}
        >
          <SelectTrigger>
            <SelectValue placeholder={skillsLoading ? 'Loading skills...' : 'Select a skill'} />
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
        <Label htmlFor="description">
          Description <span className="text-red-500">*</span>
        </Label>
        <Textarea
          id="description"
          {...register('description')}
          rows={4}
          className={errors.description ? 'border-red-500' : ''}
        />
        {errors.description && (
          <p className="text-sm text-red-500">{errors.description.message}</p>
        )}
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="rank_order">
            Display Order <span className="text-red-500">*</span>
          </Label>
          <Input
            id="rank_order"
            type="number"
            {...register('rank_order')}
          />
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
}

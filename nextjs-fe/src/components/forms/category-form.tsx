'use client';
'use no memo';

import { useEffect, useState } from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslations } from 'next-intl';
import { useCrud } from '@/shared/hooks/useCrud';
import { useJunctionTable } from '@/shared/hooks/useJunctionTable';
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
// import { JunctionManager } from '@/components/features/junction/junction-manager';
import type { CategoryMgmt, SkillMgmt } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { CategoryStatus } from '@/shared/enums';
import { categorySchema, type CategoryFormData } from '@/shared/validation/validation';
import type { CategoryFormProps } from './types';

export function CategoryForm({ initialData, onSuccess, onCancel }: CategoryFormProps) {
  const tCommon = useTranslations('common');
  const tForms = useTranslations('forms.placeholders');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<CategoryMgmt>(ENDPOINTS.MANAGEMENT.CATEGORY);
  const [activeTab, setActiveTab] = useState('details');

  // Junction table for Category-Skill (Only in Edit mode)
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  const skillJunction = useJunctionTable<SkillMgmt>(
    ENDPOINTS.JUNCTION.CATEGORY_SKILL,
    ENDPOINTS.MANAGEMENT.SKILL,
    'category_mgmt_id',
    'skill_mgmt_id',
    initialData?.id || 0
  );

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<CategoryFormData>({
    resolver: zodResolver(categorySchema),
    defaultValues: {
      rank_order: 0,
      status: CategoryStatus.ACTIVE,
      is_display: true,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        name: initialData.name,
        description: initialData.description || '',
        slug: initialData.slug || '',
        rank_order: initialData.rank_order,
        status: initialData.status,
        is_display: initialData.is_display,
      });
    } else {
      reset({
        name: '',
        description: '',
        slug: '',
        rank_order: 0,
        status: CategoryStatus.ACTIVE,
        is_display: true,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: CategoryFormData) => {
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
          Name <span className="text-red-500">*</span>
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
        <Label htmlFor="description">Description</Label>
        <Textarea id="description" {...register('description')} rows={3} />
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="slug">Slug</Label>
          <Input
            id="slug"
            {...register('slug')}
            placeholder={tForms('slugExample')}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor="rank_order">
            Display Order <span className="text-red-500">*</span>
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
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="status">
            Status <span className="text-red-500">*</span>
          </Label>
          <Select
            value={statusValue?.toString()}
            onValueChange={(value) => setValue('status', Number(value) as CategoryStatus)}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={CategoryStatus.ACTIVE.toString()}>Active</SelectItem>
              <SelectItem value={CategoryStatus.INACTIVE.toString()}>Inactive</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <div className="flex items-center gap-2 mt-8">
          <input
            type="checkbox"
            id="is_display"
            {...register('is_display')}
            className="rounded"
          />
          <Label htmlFor="is_display">Is Display</Label>
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
    <Tabs value={activeTab} onValueChange={setActiveTab} className="w-full">
      <TabsList className="grid w-full grid-cols-2">
        <TabsTrigger value="details">Details</TabsTrigger>
        <TabsTrigger value="skills">Skills</TabsTrigger>
      </TabsList>
      
      <TabsContent value="details" className="mt-4">
        {FormContent}
      </TabsContent>

      <TabsContent value="skills" className="mt-4">
        {/* TODO: Restore JunctionManager when component is available */}
        <div className="text-muted-foreground">Skills management will be available soon.</div>
        {/* <JunctionManager
          allItems={skillJunction.allItems}
          selectedIds={skillJunction.selectedIds}
          onSelectionChange={skillJunction.setSelectedIds}
          onSave={skillJunction.save}
          loading={skillJunction.loading}
          saving={skillJunction.saving}
          title="Manage Category Skills"
          itemLabel="skills"
          searchPlaceholder="Search skills..."
        /> */}
      </TabsContent>
    </Tabs>
  );
}

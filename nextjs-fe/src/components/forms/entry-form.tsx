'use client';

import { useEffect, useState, useMemo, useCallback } from 'react';
import { useForm, useWatch } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { useTranslations } from 'next-intl';
import { IsActive } from '@/shared/enums/enums';
import { useCrud } from '@/shared/hooks/useCrud';
import { useActionLock } from '@/shared/hooks/useActionLock';
import { useApiData } from '@/shared/hooks/useApiData';
import { UI_CONSTANTS } from '@/shared/config';
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
import type { EntryMgmt, LayoutStructureItem, EntryDescriptionMgmt } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { FORM_DEFAULTS } from '@/shared/config/constant';
import { EntryStatus, EntryStatusLabels } from '@/shared/enums';
import { getEntrySchema, type EntryFormData } from '@/shared/validation/validation';
import { slugify } from '@/shared/utils/string-utils';
import type { EntryFormProps } from './types';
import { LayoutStructureEditor } from './layout-structure-editor';

export function EntryForm({ initialData, onSuccess, onCancel, renderActions = true, submitTriggerRef }: EntryFormProps) {
  const tCommon = useTranslations('common');
  const tForms = useTranslations('forms.placeholders');
  const tValidation = useTranslations('validation');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<EntryMgmt>(ENDPOINTS.MANAGEMENT.ENTRY);
  const [activeTab, setActiveTab] = useState('details');
  const [layoutStructure, setLayoutStructure] = useState<LayoutStructureItem[]>([]);
  const [descSearchQuery, setDescSearchQuery] = useState('');

  // Fetch available entry descriptions for layout structure
  const { data: availableDescriptions, loading: descriptionsLoading } = useApiData<EntryDescriptionMgmt>(
    ENDPOINTS.MANAGEMENT.ENTRY_DESCRIPTION,
    { 
      page: 1, 
      per_page: 100, 
      filters: descSearchQuery ? { title: descSearchQuery } : {} 
    }
  );

  // Map descriptions to match LayoutStructureEditor's expected format
  const mappedDescriptions = useMemo(() => {
    return (availableDescriptions || []).map(desc => ({
      id: desc.id,
      name: desc.title,
    }));
  }, [availableDescriptions]);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<EntryFormData>({
    // @ts-expect-error - z.preprocess causes status to be inferred as unknown
    resolver: zodResolver(getEntrySchema(tValidation)),
    defaultValues: {
      rank_order: FORM_DEFAULTS.RANK_ORDER,
      status: EntryStatus.ACTIVE,
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
      queueMicrotask(() => {
        setLayoutStructure(initialData.layout_structure || []);
      });
    } else {
      reset({
        name: '',
        slug: '',
        rank_order: FORM_DEFAULTS.RANK_ORDER,
        status: EntryStatus.ACTIVE,
        is_display: true,
      });
      queueMicrotask(() => {
        setLayoutStructure([]);
      });
    }
  }, [initialData, reset]);

  const { execute, isLoading: isActionProcessing } = useActionLock({ delay: UI_CONSTANTS.ACTION_DELAY_MS });

  const onSubmit = useCallback(async (data: EntryFormData) => {
    await execute(async () => {
      try {
        const payload = {
          name: data.name,
          slug: data.slug || '',
          rank_order: Number(data.rank_order),
          status: Number(data.status),
          is_display: data.is_display ? IsActive.TRUE : IsActive.FALSE,
          is_delete: false,
          layout_structure: layoutStructure,
        };
      
        if (isEdit && initialData) {
          await update(initialData.id, payload);
        } else {
          await create(payload);
        }
        onSuccess();
      } catch (error: unknown) {
        console.error('[EntryForm] Submit error:', error);
        handleBindErrors(error, setError);
      }
    });
  }, [execute, isEdit, initialData, update, create, onSuccess, setError, layoutStructure]);

  // Expose submit function via ref (must be after onSubmit is defined)
  useEffect(() => {
    if (submitTriggerRef && typeof submitTriggerRef !== 'function') {
      submitTriggerRef.current = () => {
        console.log('[EntryForm] submitTriggerRef.current() called, executing handleSubmit...');
        handleSubmit(
          (data) => {
            console.log('[EntryForm] Validation Success. Payload:', data);
            void onSubmit(data as unknown as EntryFormData);
          },
          (errs) => {
            console.error('[EntryForm] Validation Failed. Errors:', errs);
          }
        )();
      };
    }
    
    return () => {
      if (submitTriggerRef && typeof submitTriggerRef !== 'function') {
        submitTriggerRef.current = null;
      }
    };
  }, [submitTriggerRef, handleSubmit, onSubmit, errors, control]);

  // Use useWatch hook instead of watch() to avoid React Compiler issues
  const statusValue = useWatch({ control, name: 'status' });

  const FormFields = (
    <>
      <div className="space-y-2">
        <Label htmlFor="name">
          {tCommon('name')} <span className="text-red-500">*</span>
        </Label>
        <Input
          id="name"
          {...register('name', {
            onChange: (e) => {
              setValue('slug', slugify(e.target.value), { shouldValidate: true });
            },
          })}
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
            {...register('rank_order', { valueAsNumber: true })}
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
            onValueChange={(value) => setValue('status', Number(value) as EntryStatus)}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={EntryStatus.ACTIVE.toString()}>{EntryStatusLabels[EntryStatus.ACTIVE]}</SelectItem>
              <SelectItem value={EntryStatus.INACTIVE.toString()}>{EntryStatusLabels[EntryStatus.INACTIVE]}</SelectItem>
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
    </>
  );

  // For non-edit mode (create), wrap fields in form with buttons
  if (!isEdit) {
    return (
      // eslint-disable-next-line @typescript-eslint/no-explicit-any
      <form onSubmit={handleSubmit(onSubmit as any)} className="flex flex-col h-full overflow-hidden">
        <div className="flex-1 overflow-y-auto px-6 space-y-4 pb-4">
          {FormFields}
        </div>
        <div className="shrink-0 flex justify-end gap-2 px-6 py-4 border-t bg-muted/20">
          <Button type="button" variant="outline" onClick={onCancel} disabled={loading || isActionProcessing}>
            {tCommon('cancel')}
          </Button>
          <Button type="submit" disabled={loading || isActionProcessing}>
            {loading || isActionProcessing ? tCommon('creating') : tCommon('create')}
          </Button>
        </div>
      </form>
    );
  }

  // For edit mode, show tabs mapped identical to CategoryForm
  return (
    <div className="flex flex-col h-full overflow-hidden">
      <Tabs key={`entry-tabs-${initialData?.id || 'new'}`} value={activeTab} onValueChange={setActiveTab} className="flex flex-col h-full overflow-hidden">
        <div className="shrink-0 px-6 pb-2 border-b">
          <TabsList className="grid w-full grid-cols-3">
            <TabsTrigger value="details" type="button">{tCommon('details')}</TabsTrigger>
            <TabsTrigger value="descriptions" type="button">{tCommon('descriptions')}</TabsTrigger>
            <TabsTrigger value="history" type="button">{tCommon('history')}</TabsTrigger>
          </TabsList>
        </div>
        
        <div className="flex-1 overflow-y-auto px-6 py-4 min-h-0">
          <TabsContent value="details" className="m-0 space-y-4">
            {FormFields}
          </TabsContent>

          <TabsContent value="descriptions" className="m-0 h-full">
            <div className="space-y-4 h-full flex flex-col">
              <div className="text-sm text-muted-foreground shrink-0">
                Manage the layout structure for descriptions in this entry. Drag and drop to reorder or change hierarchy.
              </div>
              <div className="flex-1 min-h-[400px]">
                <LayoutStructureEditor
                  type="entry_desc"
                  value={layoutStructure}
                  onChange={setLayoutStructure}
                  availableItems={mappedDescriptions}
                  loading={descriptionsLoading}
                  onSearch={setDescSearchQuery}
                />
              </div>
            </div>
          </TabsContent>

          <TabsContent value="history" className="m-0 h-full">
            <div className="h-full overflow-y-auto pr-2">
              {initialData && (
                <HistoryViewer
                  entityType="entry"
                  entityId={initialData.id}
                  endpoint={`${ENDPOINTS.MANAGEMENT.ENTRY}-hist`}
                />
              )}
            </div>
          </TabsContent>
        </div>
      </Tabs>
      
      {/* Action buttons - only render if renderActions is true */}
      {renderActions && (
        <div className="shrink-0 flex justify-end gap-2 px-6 py-4 border-t bg-muted/20">
          <Button type="button" variant="outline" onClick={onCancel} disabled={loading || isActionProcessing}>
            {tCommon('cancel')}
          </Button>
          <Button
            type="button"
            onClick={() => {
              // eslint-disable-next-line @typescript-eslint/no-explicit-any
              handleSubmit(onSubmit as any)();
            }}
            disabled={loading || isActionProcessing}
          >
            {loading || isActionProcessing ? tCommon('updating') : tCommon('update')}
          </Button>
        </div>
      )}
    </div>
  );
}

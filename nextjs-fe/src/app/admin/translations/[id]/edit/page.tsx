'use client';

import { useEffect, useState } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { useJunctionTable } from '@/hooks/useJunctionTable';
import { translationService } from '@/services/translation.service';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { JunctionManager } from '@/components/junction/junction-manager';
import { HistoryViewer } from '@/components/history';
import type { TranslationMst, LanguageMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const translationSchema = z.object({
  key: z.string().min(1, 'Key is required'),
  value: z.string().min(1, 'Value is required'),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type TranslationFormData = z.infer<typeof translationSchema>;

export default function EditTranslationPage() {
  const router = useRouter();
  const params = useParams();
  const translationId = Number(params.id);
  const { update, loading: updateLoading } = useCrud<TranslationMst>(ENDPOINTS.MASTER.TRANSLATION);
  const [activeTab, setActiveTab] = useState('details');

  // Junction table for Translation-Language
  const languageJunction = useJunctionTable<LanguageMst>(
    ENDPOINTS.JUNCTION.TRANSLATION_LANGUAGE,
    ENDPOINTS.MASTER.LANGUAGE,
    'translation_mst_id',
    'language_mst_id',
    translationId
  );

  const { register, handleSubmit, formState: { errors }, setValue, watch, reset } = useForm<TranslationFormData>({
    resolver: zodResolver(translationSchema),
  });

  useEffect(() => {
    const loadTranslation = async () => {
      const translation = await translationService.getById(translationId);
      if (translation) {
        reset({
          key: translation.key,
          value: translation.value,
          status: translation.status,
          is_active: translation.is_active,
        });
      }
    };
    loadTranslation();
  }, [translationId, reset]);

  const onSubmit = async (data: TranslationFormData) => {
    await update(translationId, data);
    router.push('/admin/translations');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Edit Translation"
        description="Update translation information and manage languages"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Translations', href: '/admin/translations' },
          { label: 'Edit', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-4xl">
        <Tabs value={activeTab} onValueChange={setActiveTab}>
          <TabsList className="grid w-full grid-cols-3">
            <TabsTrigger value="details">Details</TabsTrigger>
            <TabsTrigger value="languages">Languages</TabsTrigger>
            <TabsTrigger value="history">History</TabsTrigger>
          </TabsList>

          {/* Details Tab */}
          <TabsContent value="details">
            <Card className="p-6">
              <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
                <div className="space-y-2">
                  <Label htmlFor="key">Key <span className="text-red-500">*</span></Label>
                  <Input id="key" {...register('key')} className={errors.key ? 'border-red-500' : ''} />
                  {errors.key && <p className="text-sm text-red-500">{errors.key.message}</p>}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="value">Value <span className="text-red-500">*</span></Label>
                  <Textarea id="value" {...register('value')} rows={4} className={errors.value ? 'border-red-500' : ''} />
                  {errors.value && <p className="text-sm text-red-500">{errors.value.message}</p>}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="status">Status <span className="text-red-500">*</span></Label>
                  <Select value={watch('status')?.toString()} onValueChange={(value) => setValue('status', Number(value) as any)}>
                    <SelectTrigger><SelectValue /></SelectTrigger>
                    <SelectContent>
                      <SelectItem value={Status.ACTIVE.toString()}>Active</SelectItem>
                      <SelectItem value={Status.INACTIVE.toString()}>Inactive</SelectItem>
                    </SelectContent>
                  </Select>
                </div>

                <div className="flex items-center gap-2">
                  <input type="checkbox" id="is_active" {...register('is_active')} className="rounded" />
                  <Label htmlFor="is_active">Is Active</Label>
                </div>

                <div className="flex justify-end gap-4 pt-4">
                  <Button type="button" variant="outline" onClick={() => router.push('/admin/translations')}>Cancel</Button>
                  <Button type="submit" disabled={updateLoading}>{updateLoading ? 'Updating...' : 'Update Translation'}</Button>
                </div>
              </form>
            </Card>
          </TabsContent>

          {/* Languages Tab */}
          <TabsContent value="languages">
            <Card className="p-6">
              <JunctionManager
                allItems={languageJunction.allItems}
                selectedIds={languageJunction.selectedIds}
                onSelectionChange={languageJunction.setSelectedIds}
                onSave={languageJunction.save}
                loading={languageJunction.loading}
                saving={languageJunction.saving}
                title="Manage Translation Languages"
                itemLabel="languages"
                searchPlaceholder="Search languages..."
              />
            </Card>
          </TabsContent>

          {/* History Tab */}
          <TabsContent value="history">
            <Card className="p-6">
              <HistoryViewer
                entityType="translation"
                entityId={translationId}
                endpoint={`${ENDPOINTS.MASTER.TRANSLATION}-hist`}
              />
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </AdminLayout>
  );
}

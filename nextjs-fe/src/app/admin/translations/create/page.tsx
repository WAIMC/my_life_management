'use client';

import { useRouter } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card } from '@/components/ui/card';
import type { TranslationMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const translationSchema = z.object({
  key: z.string().min(1, 'Key is required'),
  value: z.string().min(1, 'Value is required'),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type TranslationFormData = z.infer<typeof translationSchema>;

export default function CreateTranslationPage() {
  const router = useRouter();
  const { create, loading } = useCrud<TranslationMst>(ENDPOINTS.MASTER.TRANSLATION);

  const { register, handleSubmit, formState: { errors }, setValue, watch } = useForm<TranslationFormData>({
    resolver: zodResolver(translationSchema),
    defaultValues: { status: Status.ACTIVE, is_active: true },
  });

  const onSubmit = async (data: TranslationFormData) => {
    await create({ ...data, is_delete: false });
    router.push('/admin/translations');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Create Translation"
        description="Add a new translation to the system"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Translations', href: '/admin/translations' },
          { label: 'Create', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-2xl">
        <Card className="p-6">
          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="key">Key <span className="text-red-500">*</span></Label>
              <Input id="key" placeholder="e.g., common.welcome" {...register('key')} className={errors.key ? 'border-red-500' : ''} />
              {errors.key && <p className="text-sm text-red-500">{errors.key.message}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="value">Value <span className="text-red-500">*</span></Label>
              <Textarea id="value" placeholder="Translation text" {...register('value')} rows={4} className={errors.value ? 'border-red-500' : ''} />
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
              <Button type="submit" disabled={loading}>{loading ? 'Creating...' : 'Create Translation'}</Button>
            </div>
          </form>
        </Card>
      </div>
    </AdminLayout>
  );
}

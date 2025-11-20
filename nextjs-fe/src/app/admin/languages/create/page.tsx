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
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card } from '@/components/ui/card';
import type { LanguageMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const languageSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  code: z.string().min(2, 'Code must be at least 2 characters').max(5, 'Code must be at most 5 characters'),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type LanguageFormData = z.infer<typeof languageSchema>;

export default function CreateLanguagePage() {
  const router = useRouter();
  const { create, loading } = useCrud<LanguageMst>(ENDPOINTS.MASTER.LANGUAGE);

  const { register, handleSubmit, formState: { errors }, setValue, watch } = useForm<LanguageFormData>({
    resolver: zodResolver(languageSchema),
    defaultValues: { status: Status.ACTIVE, is_active: true },
  });

  const onSubmit = async (data: LanguageFormData) => {
    await create({ ...data, is_delete: false });
    router.push('/admin/languages');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Create Language"
        description="Add a new language to the system"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Languages', href: '/admin/languages' },
          { label: 'Create', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-2xl">
        <Card className="p-6">
          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="name">Name <span className="text-red-500">*</span></Label>
              <Input id="name" placeholder="e.g., English" {...register('name')} className={errors.name ? 'border-red-500' : ''} />
              {errors.name && <p className="text-sm text-red-500">{errors.name.message}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="code">Code <span className="text-red-500">*</span></Label>
              <Input id="code" placeholder="e.g., en" {...register('code')} className={errors.code ? 'border-red-500' : ''} />
              {errors.code && <p className="text-sm text-red-500">{errors.code.message}</p>}
              <p className="text-sm text-slate-500">Language code (e.g., 'en', 'vi', 'ja')</p>
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
              <Button type="button" variant="outline" onClick={() => router.push('/admin/languages')}>Cancel</Button>
              <Button type="submit" disabled={loading}>{loading ? 'Creating...' : 'Create Language'}</Button>
            </div>
          </form>
        </Card>
      </div>
    </AdminLayout>
  );
}

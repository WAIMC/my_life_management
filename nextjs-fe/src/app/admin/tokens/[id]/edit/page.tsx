'use client';

import { useEffect } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { tokenService } from '@/services/token.service';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Card } from '@/components/ui/card';
import type { TokenMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const tokenSchema = z.object({
  token: z.string().min(1, 'Token is required'),
  admin_mst_id: z.coerce.number().min(1, 'Admin is required'),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type TokenFormData = z.infer<typeof tokenSchema>;

export default function EditTokenPage() {
  const router = useRouter();
  const params = useParams();
  const tokenId = Number(params.id);
  const { update, loading: updateLoading } = useCrud<TokenMst>(ENDPOINTS.MASTER.TOKEN);

  const { register, handleSubmit, formState: { errors }, setValue, watch, reset } = useForm<TokenFormData>({
    resolver: zodResolver(tokenSchema),
  });

  useEffect(() => {
    const loadToken = async () => {
      const token = await tokenService.getById(tokenId);
      if (token) {
        reset({
          token: token.token,
          admin_mst_id: token.admin_mst_id,
          status: token.status,
          is_active: token.is_active,
        });
      }
    };
    loadToken();
  }, [tokenId, reset]);

  const onSubmit = async (data: TokenFormData) => {
    await update(tokenId, data);
    router.push('/admin/tokens');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Edit Token"
        description="Update token information"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Tokens', href: '/admin/tokens' },
          { label: 'Edit', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-2xl">
        <Card className="p-6">
          <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
            <div className="space-y-2">
              <Label htmlFor="token">Token <span className="text-red-500">*</span></Label>
              <Input id="token" {...register('token')} className={errors.token ? 'border-red-500' : ''} />
              {errors.token && <p className="text-sm text-red-500">{errors.token.message}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor="admin_mst_id">Admin ID <span className="text-red-500">*</span></Label>
              <Input id="admin_mst_id" type="number" {...register('admin_mst_id')} className={errors.admin_mst_id ? 'border-red-500' : ''} />
              {errors.admin_mst_id && <p className="text-sm text-red-500">{errors.admin_mst_id.message}</p>}
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
              <Button type="button" variant="outline" onClick={() => router.push('/admin/tokens')}>Cancel</Button>
              <Button type="submit" disabled={updateLoading}>{updateLoading ? 'Updating...' : 'Update Token'}</Button>
            </div>
          </form>
        </Card>
      </div>
    </AdminLayout>
  );
}

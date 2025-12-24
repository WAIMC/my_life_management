'use client';

import { useEffect, useState } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { useJunctionTable } from '@/hooks/useJunctionTable';
import { apiService } from '@/services/api.service';
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
import type { ApiMst, RoleMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status } from '@/lib/types/enums';

const apiSchema = z.object({
  uri: z.string().min(1, 'URI is required'),
  method: z.string().min(1, 'Method is required'),
  description: z.string().optional(),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type ApiFormData = z.infer<typeof apiSchema>;

export default function EditApiPage() {
  const router = useRouter();
  const params = useParams();
  const apiId = Number(params.id);
  const { update, loading: updateLoading } = useCrud<ApiMst>(ENDPOINTS.MASTER.API);
  const [activeTab, setActiveTab] = useState('details');

  // Junction table for API-Role
  const roleJunction = useJunctionTable<RoleMst>(
    ENDPOINTS.JUNCTION.API_ROLE,
    ENDPOINTS.MASTER.ROLE,
    'api_mst_id',
    'role_mst_id',
    apiId
  );

  const { register, handleSubmit, formState: { errors }, setValue, watch, reset } = useForm<ApiFormData>({
    resolver: zodResolver(apiSchema) as any,
  });

  useEffect(() => {
    const loadApi = async () => {
      const api = await apiService.getById(apiId);
      if (api) {
        reset({
          uri: api.uri,
          method: api.method,
          description: api.description || '',
          status: api.status,
          is_active: api.is_active,
        });
      }
    };
    loadApi();
  }, [apiId, reset]);

  const onSubmit = async (data: ApiFormData) => {
    await update(apiId, data);
    router.push('/admin/apis');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Edit API"
        description="Update API information and manage roles"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'APIs', href: '/admin/apis' },
          { label: 'Edit', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-4xl">
        <Tabs value={activeTab} onValueChange={setActiveTab}>
          <TabsList className="grid w-full grid-cols-3">
            <TabsTrigger value="details">Details</TabsTrigger>
            <TabsTrigger value="roles">Roles</TabsTrigger>
            <TabsTrigger value="history">History</TabsTrigger>
          </TabsList>

          {/* Details Tab */}
          <TabsContent value="details">
            <Card className="p-6">
              <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
                <div className="space-y-2">
                  <Label htmlFor="uri">URI <span className="text-red-500">*</span></Label>
                  <Input id="uri" {...register('uri')} className={errors.uri ? 'border-red-500' : ''} />
                  {errors.uri && <p className="text-sm text-red-500">{errors.uri.message}</p>}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="method">Method <span className="text-red-500">*</span></Label>
                  <Select value={watch('method')} onValueChange={(value) => setValue('method', value)}>
                    <SelectTrigger><SelectValue /></SelectTrigger>
                    <SelectContent>
                      <SelectItem value="GET">GET</SelectItem>
                      <SelectItem value="POST">POST</SelectItem>
                      <SelectItem value="PUT">PUT</SelectItem>
                      <SelectItem value="DELETE">DELETE</SelectItem>
                    </SelectContent>
                  </Select>
                  {errors.method && <p className="text-sm text-red-500">{errors.method.message}</p>}
                </div>

                <div className="space-y-2">
                  <Label htmlFor="description">Description</Label>
                  <Textarea id="description" {...register('description')} rows={4} />
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
                  <Button type="button" variant="outline" onClick={() => router.push('/admin/apis')}>Cancel</Button>
                  <Button type="submit" disabled={updateLoading}>{updateLoading ? 'Updating...' : 'Update API'}</Button>
                </div>
              </form>
            </Card>
          </TabsContent>

          {/* Roles Tab */}
          <TabsContent value="roles">
            <Card className="p-6">
              <JunctionManager
                allItems={roleJunction.allItems}
                selectedIds={roleJunction.selectedIds}
                onSelectionChange={roleJunction.setSelectedIds}
                onSave={roleJunction.save}
                loading={roleJunction.loading}
                saving={roleJunction.saving}
                title="Manage API Roles"
                itemLabel="roles"
                searchPlaceholder="Search roles..."
              />
            </Card>
          </TabsContent>

          {/* History Tab */}
          <TabsContent value="history">
            <Card className="p-6">
              <HistoryViewer
                entityType="api"
                entityId={apiId}
                endpoint={`${ENDPOINTS.MASTER.API}-hist`}
              />
            </Card>
          </TabsContent>
        </Tabs>
      </div>
    </AdminLayout>
  );
}

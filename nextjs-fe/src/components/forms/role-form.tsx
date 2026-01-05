'use client';

import { useEffect } from 'react';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { handleBindErrors } from '@/lib/utils/error-handler';
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
import { HistoryViewer } from '@/components/history';
import type { RoleMst } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { IsActive, IsDelete } from '@/lib/types/enums';

const roleSchema = z.object({
  name: z.string().min(1, 'Name is required').max(30),
  permission: z.string().min(1, 'Permission is required').max(50),
  is_active: z.coerce.number().refine((val) => val === IsActive.TRUE || val === IsActive.FALSE, {
    message: 'Invalid status',
  }),
});

type RoleFormData = z.infer<typeof roleSchema>;

interface RoleFormProps {
  initialData?: RoleMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export function RoleForm({ initialData, onSuccess, onCancel }: RoleFormProps) {
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<RoleMst>(ENDPOINTS.MASTER.ROLE);

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    watch,
    reset,
    setError,
  } = useForm<RoleFormData>({
    resolver: zodResolver(roleSchema),
    defaultValues: {
      is_active: IsActive.TRUE,
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        name: initialData.name,
        permission: initialData.permission,
        is_active: initialData.is_active ? IsActive.TRUE : IsActive.FALSE,
      });
    } else {
      reset({
        name: '',
        permission: '',
        is_active: IsActive.TRUE,
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: RoleFormData) => {
    try {
      if (isEdit && initialData) {
        await update(initialData.id, {
          ...data,
          is_delete: IsDelete.FALSE,
        });
      } else {
        await create({
          ...data,
          is_delete: IsDelete.FALSE,
        });
      }
      onSuccess();
    } catch (error: any) {
      console.error(error);
      handleBindErrors(error, setError);
    }
  };

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
        <Label htmlFor="permission">
          Permission <span className="text-red-500">*</span>
        </Label>
        <Input
          id="permission"
          {...register('permission')}
          className={errors.permission ? 'border-red-500' : ''}
        />
        {errors.permission && (
          <p className="text-sm text-red-500">{errors.permission.message}</p>
        )}
      </div>

      <div className="grid grid-cols-2 gap-4">
        <div className="space-y-2">
          <Label htmlFor="is_active">
            Status <span className="text-red-500">*</span>
          </Label>
          <Select
            value={watch('is_active')?.toString()}
            onValueChange={(value) => setValue('is_active', Number(value))}
          >
            <SelectTrigger>
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value={IsActive.TRUE.toString()}>Active</SelectItem>
              <SelectItem value={IsActive.FALSE.toString()}>Inactive</SelectItem>
            </SelectContent>
          </Select>
          {errors.is_active && (
            <p className="text-sm text-red-500">{errors.is_active.message}</p>
          )}
        </div>
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

  if (!isEdit) {
    return FormContent;
  }

  return (
    <Tabs defaultValue="details" className="w-full">
      <TabsList className="grid w-full grid-cols-2">
        <TabsTrigger value="details">Details</TabsTrigger>
        <TabsTrigger value="history">History</TabsTrigger>
      </TabsList>
      <TabsContent value="details" className="mt-4">
        {FormContent}
      </TabsContent>
      <TabsContent value="history" className="mt-4">
        <div className="h-[400px] overflow-y-auto pr-2">
          {initialData && (
            <HistoryViewer
              entityType="role"
              entityId={initialData.id}
              endpoint={`${ENDPOINTS.MASTER.ROLE}-hist`}
            />
          )}
        </div>
      </TabsContent>
    </Tabs>
  );
}

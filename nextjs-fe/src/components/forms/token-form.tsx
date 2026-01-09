'use client';
'use no memo';

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
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import type { TokenMst, AdminMst } from '@/shared/types/api';
import { ENDPOINTS } from '@/shared/api';
import { tokenSchema, type TokenFormData } from '@/shared/validation/validation';

interface TokenFormProps {
  initialData?: TokenMst | null;
  onSuccess: () => void;
  onCancel: () => void;
}

export function TokenForm({ initialData, onSuccess, onCancel }: TokenFormProps) {
  const tCommon = useTranslations('common');
  const isEdit = !!initialData;
  const { create, update, loading } = useCrud<TokenMst>(ENDPOINTS.MASTER.TOKEN);

  // Fetch admins for the dropdown
  const { data: admins, loading: adminsLoading } = useApiData<AdminMst>(
    ENDPOINTS.MASTER.ADMIN,
    { page: 1, per_page: 1000, sort_by: 'email', sort_order: 'asc' }
  );

  const {
    register,
    handleSubmit,
    formState: { errors },
    setValue,
    control,
    reset,
    setError,
  } = useForm<TokenFormData>({
    resolver: zodResolver(tokenSchema),
    defaultValues: {
      account_id: 0,
      device_name: '',
    },
  });

  useEffect(() => {
    if (initialData) {
      reset({
        account_id: initialData.account_id || 0,
        device_name: initialData.device_name || '',
        ip_address: initialData.ip_address || '',
        expired_at: initialData.expired_at || '',
      });
    } else {
      reset({
        account_id: 0,
        device_name: '',
        ip_address: '',
        expired_at: '',
      });
    }
  }, [initialData, reset]);

  const onSubmit = async (data: TokenFormData) => {
    try {
      // Convert string to number for account_id
      const payload = {
        ...data,
        account_id: Number(data.account_id),
      };
      
      if (isEdit && initialData) {
        await update(initialData.id, payload);
      } else {
        await create(payload);
      }
      onSuccess();
    } catch (error: unknown) {
      console.error(error);
      handleBindErrors(error, setError);
    }
  };

  // Use useWatch hook instead of watch() to avoid React Compiler issues
  const accountIdValue = useWatch({ control, name: 'account_id' });

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      <div className="space-y-2">
        <Label htmlFor="account_id">
          Account <span className="text-red-500">*</span>
        </Label>
        <Select
          value={accountIdValue?.toString()}
          onValueChange={(value) => setValue('account_id', Number(value))}
          disabled={adminsLoading}
        >
          <SelectTrigger>
            <SelectValue placeholder={adminsLoading ? 'Loading accounts...' : 'Select an account'} />
          </SelectTrigger>
          <SelectContent>
            {admins.map((admin) => (
              <SelectItem key={admin.id} value={admin.id.toString()}>
                {admin.email} ({admin.first_name} {admin.last_name})
              </SelectItem>
            ))}
          </SelectContent>
        </Select>
        {errors.account_id && (
          <p className="text-sm text-red-500">{errors.account_id.message}</p>
        )}
      </div>

       <div className="space-y-2">
        <Label htmlFor="device_name">
          Device Name <span className="text-red-500">*</span>
        </Label>
        <Input
          id="device_name"
          {...register('device_name')}
          className={errors.device_name ? 'border-red-500' : ''}
          placeholder="Enter device name"
        />
        {errors.device_name && (
          <p className="text-sm text-red-500">{errors.device_name.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <Label htmlFor="ip_address">
          IP Address
        </Label>
        <Input
          id="ip_address"
          {...register('ip_address')}
          placeholder="Enter IP address"
        />
      </div>

      <div className="space-y-2">
        <Label htmlFor="expired_at">
          Expired At
        </Label>
        <Input
          id="expired_at"
          type="datetime-local"
          {...register('expired_at')}
        />
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

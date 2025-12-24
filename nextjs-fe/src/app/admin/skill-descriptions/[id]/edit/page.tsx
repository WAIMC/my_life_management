'use client';

import { useEffect, useState } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import * as z from 'zod';
import { useCrud } from '@/hooks/useCrud';
import { useApiData } from '@/hooks/useApiData';
import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
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
import { skillDescriptionService } from '@/services/skill-description.service';
import type { SkillDescriptionMgmt, SkillMgmt } from '@/lib/types/api';
import { ENDPOINTS } from '@/constants/api-endpoints';
import { Status, StatusLabels } from '@/lib/types/enums';
import { notification } from '@/lib/notification';

const skillDescriptionSchema = z.object({
  skill_mgmt_id: z.coerce.number().min(1, 'Skill is required'),
  description: z.string().min(1, 'Description is required'),
  rank_order: z.coerce.number().min(0, 'Order must be 0 or greater'),
  status: z.coerce.number().min(1).max(2),
  is_active: z.boolean(),
});

type SkillDescriptionFormData = z.infer<typeof skillDescriptionSchema>;

export default function EditSkillDescriptionPage() {
  const router = useRouter();
  const params = useParams();
  const skillDescriptionId = Number(params.id);
  const { update, loading } = useCrud<SkillDescriptionMgmt>(ENDPOINTS.MANAGEMENT.SKILL_DESCRIPTION);

  // Fetch all skills for the dropdown
  const { data: skills, loading: skillsLoading } = useApiData<SkillMgmt>(
    ENDPOINTS.MANAGEMENT.SKILL,
    { per_page: 100, filters: { is_active: true } }
  );

  const form = useForm<SkillDescriptionFormData>({
    resolver: zodResolver(skillDescriptionSchema) as any,
    defaultValues: {
      skill_mgmt_id: 0,
      description: '',
      rank_order: 0,
      status: Status.ACTIVE,
      is_active: true,
    },
  });

  useEffect(() => {
    const loadSkillDescription = async () => {
      try {
        const skillDescription = await skillDescriptionService.getById(skillDescriptionId);
        if (skillDescription) {
          form.reset({
            skill_mgmt_id: skillDescription.skill_mgmt_id,
            description: skillDescription.description,
            rank_order: skillDescription.rank_order,
            status: skillDescription.status,
            is_active: skillDescription.is_active,
          });
        } else {
          notification.error('Skill description not found');
          router.push('/admin/skill-descriptions');
        }
      } catch (error) {
        notification.error('Failed to load skill description');
        router.push('/admin/skill-descriptions');
      }
    };

    loadSkillDescription();
  }, [skillDescriptionId, form, router]);

  const onSubmit = async (data: SkillDescriptionFormData) => {
    await update(skillDescriptionId, data);
    router.push('/admin/skill-descriptions');
  };

  return (
    <AdminLayout>
      <PageHeader
        title="Edit Skill Description"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Skill Descriptions', href: '/admin/skill-descriptions' },
          { label: 'Edit', isActive: true },
        ]}
      />

      <div className="mt-6 max-w-2xl">
        <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
          {/* Skill Selection */}
          <div className="space-y-2">
            <Label htmlFor="skill_mgmt_id">Skill *</Label>
            {skillsLoading ? (
              <div className="text-sm text-gray-500">Loading skills...</div>
            ) : (
              <Select
                value={form.watch('skill_mgmt_id').toString()}
                onValueChange={(value) => form.setValue('skill_mgmt_id', parseInt(value))}
              >
                <SelectTrigger className={form.formState.errors.skill_mgmt_id ? 'border-red-500' : ''}>
                  <SelectValue placeholder="Select a skill" />
                </SelectTrigger>
                <SelectContent>
                  {skills.map((skill) => (
                    <SelectItem key={skill.id} value={skill.id.toString()}>
                      {skill.name}
                    </SelectItem>
                  ))}
                </SelectContent>
              </Select>
            )}
            {form.formState.errors.skill_mgmt_id && (
              <p className="text-sm text-red-500">{form.formState.errors.skill_mgmt_id.message}</p>
            )}
          </div>

          {/* Description */}
          <div className="space-y-2">
            <Label htmlFor="description">Description *</Label>
            <Textarea
              id="description"
              {...form.register('description')}
              placeholder="Enter skill description"
              rows={6}
              className={form.formState.errors.description ? 'border-red-500' : ''}
            />
            {form.formState.errors.description && (
              <p className="text-sm text-red-500">{form.formState.errors.description.message}</p>
            )}
          </div>

          {/* Order */}
          <div className="space-y-2">
            <Label htmlFor="rank_order">Display Order *</Label>
            <Input
              id="rank_order"
              type="number"
              {...form.register('rank_order')}
              placeholder="0"
              min="0"
              className={form.formState.errors.rank_order ? 'border-red-500' : ''}
            />
            {form.formState.errors.rank_order && (
              <p className="text-sm text-red-500">{form.formState.errors.rank_order.message}</p>
            )}
          </div>

          {/* Status */}
          <div className="space-y-2">
            <Label htmlFor="status">Status *</Label>
            <Select
              value={form.watch('status').toString()}
              onValueChange={(value) => form.setValue('status', parseInt(value))}
            >
              <SelectTrigger>
                <SelectValue />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value={Status.ACTIVE.toString()}>
                  {StatusLabels[Status.ACTIVE]}
                </SelectItem>
                <SelectItem value={Status.INACTIVE.toString()}>
                  {StatusLabels[Status.INACTIVE]}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          {/* Is Active */}
          <div className="flex items-center gap-2">
            <input
              type="checkbox"
              id="is_active"
              {...form.register('is_active')}
              className="rounded"
            />
            <Label htmlFor="is_active" className="cursor-pointer">
              Is Active
            </Label>
          </div>

          {/* Actions */}
          <div className="flex justify-end gap-4 pt-4 border-t">
            <Button
              type="button"
              variant="outline"
              onClick={() => router.push('/admin/skill-descriptions')}
              disabled={loading}
            >
              Cancel
            </Button>
            <Button type="submit" disabled={loading || skillsLoading}>
              {loading ? 'Updating...' : 'Update Skill Description'}
            </Button>
          </div>
        </form>
      </div>
    </AdminLayout>
  );
}

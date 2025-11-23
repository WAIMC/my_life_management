/**
 * Integration Example: Admin Create/Edit Page with All Features
 */

'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import {
  FormBuilder,
  AvatarUpload,
  MultiSelect,
  type FormSchema,
} from '@/components/crud';
import { HistoryViewer, Timeline } from '@/components/history';
import { adminService, roleService, departmentService } from '@/services/modules';
import type { AdminMst } from '@/types/models';
import toast from 'react-hot-toast';

interface AdminFormPageProps {
  adminId?: number; // undefined for create, number for edit
}

export default function AdminFormPageEnhanced({ adminId }: AdminFormPageProps) {
  const router = useRouter();
  const [admin, setAdmin] = useState<AdminMst | null>(null);
  const [avatarFile, setAvatarFile] = useState<File | null>(null);
  const [selectedRoles, setSelectedRoles] = useState<number[]>([]);
  const [selectedDepartments, setSelectedDepartments] = useState<number[]>([]);

  // Load data for edit mode
  useEffect(() => {
    if (adminId) {
      loadAdmin();
    }
  }, [adminId]);

  const loadAdmin = async () => {
    const data = await adminService.get(adminId!);
    setAdmin(data);
    setSelectedRoles(data.roles?.map((r) => r.id) || []);
    setSelectedDepartments(data.departments?.map((d) => d.id) || []);
  };

  // Form schema
  const formSchema: FormSchema = {
    title: adminId ? 'Edit Admin' : 'Create Admin',
    layout: 'tabs',
    sections: [
      {
        label: 'Basic Information',
        fields: [
          { name: 'email', label: 'Email', type: 'email', required: true },
          { name: 'user_name', label: 'Username', type: 'text', required: true },
          ...(adminId ? [] : [
            { name: 'password', label: 'Password', type: 'password', required: true },
          ]),
          { name: 'first_name', label: 'First Name', type: 'text', required: true },
          { name: 'last_name', label: 'Last Name', type: 'text', required: true },
          { name: 'address', label: 'Address', type: 'textarea' },
          { name: 'phone_number', label: 'Phone', type: 'text' },
          { name: 'birth', label: 'Birth Date', type: 'date' },
          {
            name: 'gender',
            label: 'Gender',
            type: 'select',
            required: true,
            options: [
              { value: '1', label: 'Male' },
              { value: '2', label: 'Female' },
              { value: '3', label: 'Other' },
            ],
          },
          { name: 'is_active', label: 'Active', type: 'checkbox' },
        ],
      },
    ],
  };

  const handleSubmit = async (data: Record<string, any>) => {
    try {
      // Prepare data
      const formData = {
        ...data,
        role_ids: selectedRoles,
        department_ids: selectedDepartments,
      };

      // Handle avatar upload
      if (avatarFile) {
        // Upload avatar first, then add URL to formData
        // formData.avatar = uploadedUrl;
      }

      if (adminId) {
        await adminService.update(adminId, formData);
        toast.success('Admin updated successfully');
      } else {
        await adminService.create(formData);
        toast.success('Admin created successfully');
      }

      router.push('/admin/admins');
    } catch (error) {
      toast.error('Operation failed');
    }
  };

  return (
    <div className="container mx-auto py-6">
      <Tabs defaultValue="details">
        <TabsList>
          <TabsTrigger value="details">Details</TabsTrigger>
          {adminId && <TabsTrigger value="history">History</TabsTrigger>}
        </TabsList>

        <TabsContent value="details" className="space-y-6">
          {/* Avatar Upload */}
          <AvatarUpload
            value={admin?.avatar}
            onChange={(file, preview) => setAvatarFile(file)}
            maxSize={5}
          />

          {/* Main Form */}
          <FormBuilder
            schema={formSchema}
            initialValues={admin || {}}
            onSubmit={handleSubmit}
            onCancel={() => router.back()}
          />

          {/* Role Assignment */}
          <MultiSelect
            label="Roles"
            options={[
              // Load from roleService.list()
              { value: 1, label: 'Super Admin' },
              { value: 2, label: 'Admin' },
              { value: 3, label: 'Editor' },
            ]}
            value={selectedRoles}
            onChange={setSelectedRoles}
          />

          {/* Department Assignment */}
          <MultiSelect
            label="Departments"
            options={[
              // Load from departmentService.list()
              { value: 1, label: 'IT Department' },
              { value: 2, label: 'HR Department' },
              { value: 3, label: 'Finance' },
            ]}
            value={selectedDepartments}
            onChange={setSelectedDepartments}
          />
        </TabsContent>

        {adminId && (
          <TabsContent value="history">
            <div className="grid gap-6 md:grid-cols-2">
              <HistoryViewer
                baseUrl="/admin/admin-mst-hist"
                recordId={adminId}
                onRestore={() => loadAdmin()}
              />
              <Timeline
                history={[]} // Load from useHistory hook
              />
            </div>
          </TabsContent>
        )}
      </Tabs>
    </div>
  );
}

import { useEffect } from 'react';

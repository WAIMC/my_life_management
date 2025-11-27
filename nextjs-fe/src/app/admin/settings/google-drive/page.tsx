'use client';

import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { GoogleDriveSettingsTable } from '@/components/settings/google-drive-settings-table';

export default function GoogleDriveSettingsPage() {
  return (
    <AdminLayout>
      <PageHeader
        title="Google Drive Settings"
        description="Quản lý cấu hình và thông tin xác thực Google Drive"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'Settings', href: '/admin/settings' },
          { label: 'Google Drive', isActive: true }
        ]}
      />

      <GoogleDriveSettingsTable />
    </AdminLayout>
  );
}

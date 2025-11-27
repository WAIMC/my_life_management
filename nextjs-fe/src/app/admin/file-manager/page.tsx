'use client';

import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { FileManagerContent } from '@/components/file-manager/file-manager-content';

export default function FileManagerPage() {
  return (
    <AdminLayout>
      {/* Page Header */}
      <PageHeader
        title="File Manager"
        description="Quản lý và tổ chức tệp tin và thư mục của bạn"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'File Manager', isActive: true }
        ]}
      />

      {/* File Manager Content */}
      <div className="h-[calc(100vh-16rem)] overflow-hidden">
        <FileManagerContent />
      </div>
    </AdminLayout>
  );
}

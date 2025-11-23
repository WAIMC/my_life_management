'use client';

import { FileManager } from '@/components/file-manager';
import { AdminNavDropdown } from '@/components/layout/admin-nav-dropdown';

export default function FileManagerPage() {
  return (
    <div className="flex h-screen flex-col">
      {/* Navigation Dropdown Bar */}
      <div className="flex items-center justify-between border-b bg-background px-4 py-3">
        <div className="flex items-center gap-3">
          <AdminNavDropdown currentLabel="File Manager" />
          <div className="h-4 w-px bg-border" />
          <h1 className="text-lg font-semibold">File Manager</h1>
        </div>
      </div>
      
      {/* File Manager Component */}
      <div className="flex-1 overflow-hidden">
        <FileManager />
      </div>
    </div>
  );
}

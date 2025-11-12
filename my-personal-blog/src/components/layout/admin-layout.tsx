'use client';

import { Sidebar } from './sidebar';
import { Header } from './header';
import { Content } from './content';

interface AdminLayoutProps {
  children: React.ReactNode;
  className?: string;
}

export function AdminLayout({ children, className }: AdminLayoutProps) {
  return (
    <div className="flex h-screen bg-white dark:bg-slate-950">
      {/* Sidebar */}
      <Sidebar />

      {/* Main Content */}
      <div className="flex flex-1 flex-col lg:ml-64">
        {/* Header */}
        <Header />

        {/* Content Area */}
        <Content className={className}>{children}</Content>
      </div>
    </div>
  );
}

'use client';

import { useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useAppSelector } from '@/redux/hooks';
import { Sidebar } from './sidebar';
import { Header } from './header';
import { Content } from './content';

interface AdminLayoutProps {
  children: React.ReactNode;
  className?: string;
}

export function AdminLayout({ children, className }: AdminLayoutProps) {
  const router = useRouter();
  const { accessToken, isAuthenticated } = useAppSelector((state) => state.auth);

  useEffect(() => {
    if (!accessToken || !isAuthenticated) {
      const currentPath = typeof window !== 'undefined' ? window.location.pathname : '/admin';
      router.push(`/login?redirect=${encodeURIComponent(currentPath)}`);
    }
  }, [accessToken, isAuthenticated, router]);

  if (!accessToken || !isAuthenticated) {
    return null;
  }

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

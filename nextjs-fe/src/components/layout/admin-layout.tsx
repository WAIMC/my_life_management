'use client';

import { useEffect, useState } from 'react';
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
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    if (!accessToken || !isAuthenticated) {
      const currentPath = typeof window !== 'undefined' ? window.location.pathname : '/admin';
      router.push(`/login?redirect=${encodeURIComponent(currentPath)}`);
    } else {
      setIsLoading(false);
    }
  }, [accessToken, isAuthenticated, router]);

  if (isLoading || !accessToken || !isAuthenticated) {
    return (
      <div className="flex h-screen items-center justify-center bg-white dark:bg-slate-950">
        <div className="text-center">
          <div className="mb-4 inline-block h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-blue-600 dark:border-slate-800 dark:border-t-blue-400" />
          <p className="text-sm text-slate-600 dark:text-slate-400">Loading...</p>
        </div>
      </div>
    );
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

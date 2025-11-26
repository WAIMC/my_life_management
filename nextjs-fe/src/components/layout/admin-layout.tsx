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
  const { accessToken, isAuthenticated, authInitialized } = useAppSelector((state) => state.auth);
  const [shouldRedirect, setShouldRedirect] = useState(false);
  const [isMounted, setIsMounted] = useState(false);

  // Fix hydration error: Only render after client-side mount
  useEffect(() => {
    setIsMounted(true);
  }, []);

  useEffect(() => {
    // Only check auth after initialization is complete
    if (!authInitialized) {
      console.log('[AdminLayout] Auth not initialized yet, waiting...');
      return;
    }

    console.log('[AdminLayout] Checking auth state:', { 
      accessToken: accessToken?.substring(0, 10) + '...', 
      isAuthenticated,
      authInitialized 
    });

    // CRITICAL: Wait a bit for state to propagate after authInitialized becomes true
    // This prevents race condition where authInitializer sets authInitialized=true
    // but hasn't set accessToken yet
    const timer = setTimeout(() => {
      // If no valid auth after initialization + delay, redirect to login
      if (!accessToken || !isAuthenticated) {
        const currentPath = typeof window !== 'undefined' ? window.location.pathname : '/admin';
        console.log('[AdminLayout] No valid auth, redirecting to login from:', currentPath);
        setShouldRedirect(true);
      } else {
        console.log('[AdminLayout] Auth valid, allowing access');
      }
    }, 150); // Small delay to let authInitializer finish setting state

    return () => clearTimeout(timer);
  }, [authInitialized, accessToken, isAuthenticated]);

  useEffect(() => {
    if (shouldRedirect) {
      const currentPath = typeof window !== 'undefined' ? window.location.pathname : '/admin';
      router.push(`/login?redirect=${encodeURIComponent(currentPath)}`);
    }
  }, [shouldRedirect, router]);

  // Prevent hydration mismatch: Don't render anything until mounted
  if (!isMounted) {
    return null;
  }

  // Show loading while auth is initializing
  if (!authInitialized) {
    return (
      <div className="flex h-screen items-center justify-center bg-white dark:bg-slate-950">
        <div className="text-center">
          <div className="mb-4 inline-block h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-blue-600 dark:border-slate-800 dark:border-t-blue-400" />
          <p className="text-sm text-slate-600 dark:text-slate-400">Loading...</p>
        </div>
      </div>
    );
  }

  // Show loading while redirecting
  if (shouldRedirect || !accessToken || !isAuthenticated) {
    return (
      <div className="flex h-screen items-center justify-center bg-white dark:bg-slate-950">
        <div className="text-center">
          <div className="mb-4 inline-block h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-blue-600 dark:border-slate-800 dark:border-t-blue-400" />
          <p className="text-sm text-slate-600 dark:text-slate-400">Redirecting to login...</p>
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

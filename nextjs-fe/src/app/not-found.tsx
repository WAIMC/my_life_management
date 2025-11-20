'use client';

import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { AlertTriangle, Home, ArrowLeft } from 'lucide-react';

export default function NotFound() {
  const handleGoBack = () => {
    if (typeof window !== 'undefined') {
      window.history.back();
    }
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-950">
      <div className="mx-auto max-w-md px-4 text-center">
        {/* Icon */}
        <div className="mb-8 flex justify-center">
          <div className="rounded-full bg-red-100 p-6 dark:bg-red-900/20">
            <AlertTriangle className="h-16 w-16 text-red-600 dark:text-red-400" />
          </div>
        </div>

        {/* Error Code */}
        <h1 className="mb-4 text-8xl font-bold text-slate-900 dark:text-white">
          404
        </h1>

        {/* Title */}
        <h2 className="mb-3 text-2xl font-semibold text-slate-900 dark:text-slate-100">
          Page Not Found
        </h2>

        {/* Description */}
        <p className="mb-8 text-slate-600 dark:text-slate-400">
          The page you&apos;re looking for doesn&apos;t exist or has been moved.
          Please check the URL or return to the homepage.
        </p>

        {/* Actions */}
        <div className="flex flex-col gap-3 sm:flex-row sm:justify-center">
          <Button asChild size="lg" className="gap-2">
            <Link href="/admin">
              <Home className="h-4 w-4" />
              Back to Dashboard
            </Link>
          </Button>
          <Button 
            variant="outline" 
            size="lg" 
            onClick={handleGoBack}
            className="gap-2"
          >
            <ArrowLeft className="h-4 w-4" />
            Go Back
          </Button>
        </div>

        {/* Helpful Links */}
        <div className="mt-12 text-sm text-slate-500 dark:text-slate-500">
          Need help? Contact{' '}
          <a
            href="mailto:support@example.com"
            className="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
          >
            support
          </a>
        </div>
      </div>
    </div>
  );
}

'use client';

import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { AlertTriangle, Home, RotateCcw, ChevronDown } from 'lucide-react';
import { useState } from 'react';

interface ErrorProps {
  error: Error & { digest?: string };
  reset: () => void;
}

export default function Error({ error, reset }: ErrorProps) {
  const [showDetails, setShowDetails] = useState(false);
  const isDev = process.env.NODE_ENV === 'development';

  return (
    <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-950">
      <div className="mx-auto max-w-2xl px-4 text-center">
        {/* Icon */}
        <div className="mb-8 flex justify-center">
          <div className="rounded-full bg-red-100 p-6 dark:bg-red-900/20">
            <AlertTriangle className="h-16 w-16 text-red-600 dark:text-red-400" />
          </div>
        </div>

        {/* Error Code */}
        <h1 className="mb-4 text-8xl font-bold text-slate-900 dark:text-white">
          500
        </h1>

        {/* Title */}
        <h2 className="mb-3 text-2xl font-semibold text-slate-900 dark:text-slate-100">
          Internal Server Error
        </h2>

        {/* Description */}
        <p className="mb-6 text-slate-600 dark:text-slate-400">
          Something went wrong on our end. We&apos;re working to fix the issue.
          Please try again later or contact support if the problem persists.
        </p>

        {/* Error Message (Production) */}
        {error.message && !isDev && (
          <div className="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-700 dark:bg-red-900/20 dark:text-red-300">
            <p className="font-medium">Error: {error.message}</p>
          </div>
        )}

        {/* Error Details (Development) */}
        {isDev && (
          <div className="mb-6">
            <button
              onClick={() => setShowDetails(!showDetails)}
              className="mb-3 flex items-center gap-2 text-sm font-medium text-slate-700 hover:text-slate-900 dark:text-slate-300 dark:hover:text-slate-100"
            >
              <ChevronDown
                className={`h-4 w-4 transition-transform ${showDetails ? 'rotate-180' : ''}`}
              />
              {showDetails ? 'Hide' : 'Show'} Error Details (Dev Mode)
            </button>
            {showDetails && (
              <div className="rounded-lg bg-slate-100 p-4 text-left dark:bg-slate-800">
                <p className="mb-2 text-sm font-medium text-red-600 dark:text-red-400">
                  {error.message}
                </p>
                {error.digest && (
                  <p className="mb-2 text-xs text-slate-500 dark:text-slate-400">
                    Digest: {error.digest}
                  </p>
                )}
                {error.stack && (
                  <pre className="mt-3 overflow-auto text-xs text-slate-700 dark:text-slate-300">
                    {error.stack}
                  </pre>
                )}
              </div>
            )}
          </div>
        )}

        {/* Actions */}
        <div className="flex flex-col gap-3 sm:flex-row sm:justify-center">
          <Button onClick={reset} size="lg" className="gap-2">
            <RotateCcw className="h-4 w-4" />
            Try Again
          </Button>
          <Button variant="outline" asChild size="lg" className="gap-2">
            <Link href="/admin">
              <Home className="h-4 w-4" />
              Back to Dashboard
            </Link>
          </Button>
        </div>

        {/* Support Link */}
        <div className="mt-12 text-sm text-slate-500 dark:text-slate-500">
          Still experiencing issues?{' '}
          <a
            href="mailto:support@example.com"
            className="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
          >
            Contact support
          </a>
        </div>
      </div>
    </div>
  );
}

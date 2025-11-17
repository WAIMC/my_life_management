'use client';

import Link from 'next/link';
import { Button } from '@/components/ui/button';

interface ErrorProps {
  error: Error & { digest?: string };
  reset: () => void;
}

export default function Error({ error, reset }: ErrorProps) {
  return (
    <div className="flex h-screen items-center justify-center bg-gradient-to-br from-slate-900 to-slate-800">
      <div className="text-center">
        <div className="mb-8 flex justify-center">
          <div className="rounded-full bg-red-100 p-4 dark:bg-red-900/20">
            <svg className="h-12 w-12 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3.05h16.94a2 2 0 0 0 1.71-3.05L13.71 3.86a2 2 0 0 0-3.42 0z" />
              <line x1="12" y1="9" x2="12" y2="13" />
              <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
          </div>
        </div>
        <h1 className="mb-2 text-6xl font-bold text-white">500</h1>
        <p className="mb-2 text-2xl font-semibold text-slate-200">
          Internal Server Error
        </p>
        <p className="mb-8 text-slate-400">
          Something went wrong on our end. Please try again later.
        </p>
        {error.message && (
          <p className="mb-6 rounded-lg bg-red-900/20 p-4 text-sm text-red-300">
            {error.message}
          </p>
        )}
        <div className="flex flex-col gap-3 sm:flex-row sm:justify-center">
          <Button onClick={reset}>Try Again</Button>
          <Button variant="outline" asChild>
            <Link href="/admin">Back to Dashboard</Link>
          </Button>
        </div>
      </div>
    </div>
  );
}

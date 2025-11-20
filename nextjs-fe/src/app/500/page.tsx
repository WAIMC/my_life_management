'use client';

import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { ServerCrash, Home, RotateCcw } from 'lucide-react';

export default function ServerErrorPage() {
  const handleRetry = () => {
    window.location.reload();
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-950">
      <div className="mx-auto max-w-2xl px-4 text-center">
        {/* Icon */}
        <div className="mb-8 flex justify-center">
          <div className="rounded-full bg-red-100 p-6 dark:bg-red-900/20">
            <ServerCrash className="h-16 w-16 text-red-600 dark:text-red-400" />
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
          Đã xảy ra lỗi từ phía server. Vui lòng thử lại sau hoặc liên hệ hỗ trợ nếu vấn đề vẫn tiếp diễn.
        </p>

        {/* Actions */}
        <div className="flex flex-col gap-3 sm:flex-row sm:justify-center">
          <Button onClick={handleRetry} size="lg" className="gap-2">
            <RotateCcw className="h-4 w-4" />
            Thử lại
          </Button>
          <Button variant="outline" asChild size="lg" className="gap-2">
            <Link href="/admin">
              <Home className="h-4 w-4" />
              Về trang chủ
            </Link>
          </Button>
        </div>

        {/* Support Link */}
        <div className="mt-12 text-sm text-slate-500 dark:text-slate-500">
          Vẫn gặp vấn đề?{' '}
          <a
            href="mailto:support@example.com"
            className="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
          >
            Liên hệ hỗ trợ
          </a>
        </div>
      </div>
    </div>
  );
}

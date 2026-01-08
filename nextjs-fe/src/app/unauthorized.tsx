'use client';

import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Lock, Home, LogIn } from 'lucide-react';
import { useTranslations } from 'next-intl';

export default function Unauthorized() {
  const t = useTranslations('auth');
  const tCommon = useTranslations('common');
  const tErrors = useTranslations('errors');

  return (
    <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-950">
      <div className="mx-auto max-w-md px-4 text-center">
        {/* Icon */}
        <div className="mb-8 flex justify-center">
          <div className="rounded-full bg-yellow-100 p-6 dark:bg-yellow-900/20">
            <Lock className="h-16 w-16 text-yellow-600 dark:text-yellow-400" />
          </div>
        </div>

        {/* Error Code */}
        <h1 className="mb-4 text-8xl font-bold text-slate-900 dark:text-white">
          {tErrors('errorCode')}
        </h1>

        {/* Title */}
        <h2 className="mb-3 text-2xl font-semibold text-slate-900 dark:text-slate-100">
          {t('unauthorized')}
        </h2>

        {/* Description */}
        <p className="mb-8 text-slate-600 dark:text-slate-400">
          {t('unauthorizedDescription')}
        </p>

        {/* Actions */}
        <div className="flex flex-col gap-3 sm:flex-row sm:justify-center">
          <Button asChild size="lg" className="gap-2">
            <Link href="/login">
              <LogIn className="h-4 w-4" />
              {tCommon('signIn')}
            </Link>
          </Button>
          <Button variant="outline" asChild size="lg" className="gap-2">
            <Link href="/">
              <Home className="h-4 w-4" />
              {tCommon('goHome')}
            </Link>
          </Button>
        </div>

        {/* Help Text */}
        <div className="mt-12 rounded-lg bg-slate-100 p-4 dark:bg-slate-800">
          <p className="text-sm text-slate-600 dark:text-slate-400">
            <strong className="font-semibold text-slate-900 dark:text-slate-200">
              {tCommon('needAccess')}
            </strong>
            <br />
            {t('contactAdmin')}{' '}
            <a
              href="mailto:admin@example.com"
              className="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300"
            >
              {tCommon('requestPermissions')}
            </a>
          </p>
        </div>
      </div>
    </div>
  );
}

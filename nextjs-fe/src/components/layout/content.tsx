'use client';

import { cn } from '@/lib/utils';

interface ContentProps {
  children: React.ReactNode;
  className?: string;
  padded?: boolean;
}

export function Content({ children, className, padded = true }: ContentProps) {
  return (
    <main
      className={cn(
        'flex-1 overflow-auto bg-slate-50 dark:bg-slate-900',
        padded && 'p-4 lg:p-6',
        className
      )}
    >
      {children}
    </main>
  );
}

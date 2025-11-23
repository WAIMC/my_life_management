import { Suspense, ReactNode } from 'react';
import { Skeleton } from '@/components/ui/skeleton';

interface SuspenseBoundaryProps {
  children: ReactNode;
  fallback?: ReactNode;
  loadingText?: string;
}

/**
 * Reusable Suspense Boundary with default loading UI
 * 
 * Usage:
 * ```tsx
 * <SuspenseBoundary loadingText="Loading users...">
 *   <UserList />
 * </SuspenseBoundary>
 * ```
 */
export function SuspenseBoundary({
  children,
  fallback,
  loadingText = 'Loading...',
}: SuspenseBoundaryProps) {
  const defaultFallback = (
    <div className="space-y-4">
      <div className="flex items-center gap-2">
        <Skeleton className="h-4 w-4 rounded-full" />
        <Skeleton className="h-4 w-32" />
      </div>
      <Skeleton className="h-32 w-full" />
      <Skeleton className="h-32 w-full" />
      <Skeleton className="h-32 w-full" />
    </div>
  );

  return (
    <Suspense fallback={fallback || defaultFallback}>
      {children}
    </Suspense>
  );
}

/**
 * Table Loading Skeleton
 */
export function TableSkeleton({ rows = 5 }: { rows?: number }) {
  return (
    <div className="space-y-2">
      <Skeleton className="h-10 w-full" /> {/* Header */}
      {Array.from({ length: rows }).map((_, i) => (
        <Skeleton key={i} className="h-16 w-full" />
      ))}
    </div>
  );
}

/**
 * Form Loading Skeleton
 */
export function FormSkeleton({ fields = 6 }: { fields?: number }) {
  return (
    <div className="space-y-4">
      {Array.from({ length: fields }).map((_, i) => (
        <div key={i} className="space-y-2">
          <Skeleton className="h-4 w-24" /> {/* Label */}
          <Skeleton className="h-10 w-full" /> {/* Input */}
        </div>
      ))}
      <Skeleton className="h-10 w-32" /> {/* Button */}
    </div>
  );
}

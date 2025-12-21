'use client';

import { useEffect } from 'react';
import { useRouter } from 'next/navigation';

interface ProtectedRouteProps {
  children: React.ReactNode;
}

export default function ProtectedRoute({ children }: ProtectedRouteProps) {
  // Auth check logic removed as per request.
  // This component now acts as a simple wrapper.
  // Future implementation should check for cookie existence or use middleware.

  return <>{children}</>;
}

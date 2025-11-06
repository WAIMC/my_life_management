'use client';

import { useSelector } from 'react-redux';
import { RootState } from '@/redux/rootReducer';

export default function Loading() {
  const isLoading = useSelector((state: RootState) => state.common.isLoading);

  if (!isLoading) return null;

  return (
    <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 z-[9999]">
      <div className="animate-spin rounded-full h-16 w-16 border-t-4 border-blue-500 border-solid" />
    </div>
  );
}

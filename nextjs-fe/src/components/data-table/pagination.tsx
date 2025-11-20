'use client';

import { Button } from '@/components/ui/button';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight } from 'lucide-react';

interface PaginationInfo {
  currentPage: number;
  lastPage: number;
  total: number;
  perPage: number;
  from: number;
  to: number;
}

interface PaginationProps {
  pagination: PaginationInfo;
  page: number;
  onPageChange: (page: number) => void;
  perPage: number;
  onPerPageChange: (perPage: number) => void;
}

export function Pagination({
  pagination,
  page,
  onPageChange,
  perPage,
  onPerPageChange,
}: PaginationProps) {
  const { currentPage, lastPage, total, from, to } = pagination;

  return (
    <div className="flex items-center justify-between px-2 py-4">
      {/* Info */}
      <div className="flex items-center gap-4">
        <div className="text-sm text-slate-600 dark:text-slate-400">
          Showing <span className="font-medium">{from}</span> to{' '}
          <span className="font-medium">{to}</span> of{' '}
          <span className="font-medium">{total}</span> results
        </div>

        {/* Per Page Selector */}
        <div className="flex items-center gap-2">
          <span className="text-sm text-slate-600 dark:text-slate-400">
            Rows per page:
          </span>
          <Select
            value={perPage.toString()}
            onValueChange={(value) => onPerPageChange(Number(value))}
          >
            <SelectTrigger className="w-20">
              <SelectValue />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="10">10</SelectItem>
              <SelectItem value="20">20</SelectItem>
              <SelectItem value="50">50</SelectItem>
              <SelectItem value="100">100</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      {/* Navigation */}
      <div className="flex items-center gap-2">
        <Button
          variant="outline"
          size="sm"
          onClick={() => onPageChange(1)}
          disabled={currentPage === 1}
        >
          <ChevronsLeft className="h-4 w-4" />
        </Button>
        <Button
          variant="outline"
          size="sm"
          onClick={() => onPageChange(page - 1)}
          disabled={currentPage === 1}
        >
          <ChevronLeft className="h-4 w-4" />
        </Button>

        <div className="flex items-center gap-1 px-2">
          <span className="text-sm text-slate-600 dark:text-slate-400">
            Page{' '}
            <span className="font-medium">
              {currentPage}
            </span>{' '}
            of{' '}
            <span className="font-medium">
              {lastPage}
            </span>
          </span>
        </div>

        <Button
          variant="outline"
          size="sm"
          onClick={() => onPageChange(page + 1)}
          disabled={currentPage === lastPage}
        >
          <ChevronRight className="h-4 w-4" />
        </Button>
        <Button
          variant="outline"
          size="sm"
          onClick={() => onPageChange(lastPage)}
          disabled={currentPage === lastPage}
        >
          <ChevronsRight className="h-4 w-4" />
        </Button>
      </div>
    </div>
  );
}

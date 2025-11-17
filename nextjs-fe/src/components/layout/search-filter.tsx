'use client';

import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { useState, useCallback, useRef, useEffect } from 'react';

interface SearchFilterProps {
  placeholder?: string;
  onSearch?: (value: string) => void;
  onFilterClick?: () => void;
  showFilter?: boolean;
}

export function SearchFilter({
  placeholder = 'Search...',
  onSearch,
  onFilterClick,
  showFilter = true,
}: SearchFilterProps) {
  const [searchValue, setSearchValue] = useState('');
  const timeoutRef = useRef<NodeJS.Timeout | null>(null);

  useEffect(() => {
    return () => {
      if (timeoutRef.current) {
        clearTimeout(timeoutRef.current);
      }
    };
  }, []);

  const handleSearch = useCallback(
    (value: string) => {
      setSearchValue(value);
      if (timeoutRef.current) {
        clearTimeout(timeoutRef.current);
      }
      timeoutRef.current = setTimeout(() => {
        onSearch?.(value);
      }, 300);
    },
    [onSearch]
  );

  const handleClear = () => {
    setSearchValue('');
    onSearch?.('');
  };

  return (
    <div className="flex items-center gap-2">
      <div className="relative flex-1 max-w-sm">
        <svg className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
          <circle cx="11" cy="11" r="8" />
          <line x1="21" y1="21" x2="16.65" y2="16.65" />
        </svg>
        <Input
          type="search"
          placeholder={placeholder}
          value={searchValue}
          onChange={(e) => handleSearch(e.target.value)}
          className="pl-10"
        />
        {searchValue && (
          <button
            onClick={handleClear}
            className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300"
          >
            <svg className="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <line x1="18" y1="6" x2="6" y2="18" />
              <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
          </button>
        )}
      </div>

      {showFilter && (
        <Button
          variant="outline"
          onClick={onFilterClick}
          className="gap-2"
        >
          <svg
            className="h-4 w-4"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
          >
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
          </svg>
          Filter
        </Button>
      )}
    </div>
  );
}

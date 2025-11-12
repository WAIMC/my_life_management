'use client';

import { ChevronRight } from 'lucide-react';
import { Button } from '@/components/ui/button';

interface BreadcrumbItem {
  label: string;
  path: string;
}

interface BreadcrumbProps {
  items: BreadcrumbItem[];
  onNavigate: (path: string) => void;
}

export const Breadcrumb = ({ items, onNavigate }: BreadcrumbProps) => {
  const displayItems = items.length > 0 ? items : [{ label: 'Trang chủ', path: '/' }];

  return (
    <nav className="flex items-center space-x-1 border-b border-border bg-background px-4 py-3">
      {displayItems.map((item, index) => (
        <div key={`${item.path}-${index}`} className="flex items-center space-x-1">
          <Button
            variant="ghost"
            size="sm"
            onClick={() => onNavigate(item.path)}
            className="hover:bg-accent"
          >
            <span className="text-sm font-medium">{item.label}</span>
          </Button>
          {index < displayItems.length - 1 && (
            <ChevronRight className="h-4 w-4 text-muted-foreground" />
          )}
        </div>
      ))}
    </nav>
  );
};

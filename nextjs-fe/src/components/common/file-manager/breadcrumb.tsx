'use client';

import { ChevronRight, Home } from 'lucide-react';
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
  return (
    <nav className="flex items-center space-x-1 border-b border-border bg-background px-4 py-3 overflow-x-auto">
      <div className="flex items-center space-x-1 min-w-0">
        <Button
          variant="ghost"
          size="sm"
          onClick={() => onNavigate('/')}
          className="h-8 w-8 p-0"
          title="Trang chủ"
        >
          <Home className="h-4 w-4" />
        </Button>
        
        {items.length > 0 && <ChevronRight className="h-4 w-4 text-muted-foreground flex-shrink-0" />}
        
        {items.map((item, index) => (
          <div key={`${item.path}-${index}`} className="flex items-center space-x-1 whitespace-nowrap">
            <Button
              variant="ghost"
              size="sm"
              onClick={() => onNavigate(item.path)}
              className={`h-8 px-2 hover:bg-accent ${
                index === items.length - 1 ? 'font-semibold text-foreground pointer-events-none' : 'text-muted-foreground'
              }`}
            >
              <span className="text-sm">{item.label}</span>
            </Button>
            {index < items.length - 1 && (
              <ChevronRight className="h-4 w-4 text-muted-foreground flex-shrink-0" />
            )}
          </div>
        ))}
      </div>
    </nav>
  );
};

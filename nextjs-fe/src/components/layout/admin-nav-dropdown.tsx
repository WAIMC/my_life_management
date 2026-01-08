'use client';

import Link from 'next/link';
import { usePathname } from 'next/navigation';
import { ChevronDown, LucideIcon } from 'lucide-react';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
  DropdownMenuGroup,
  DropdownMenuSub,
  DropdownMenuSubContent,
  DropdownMenuSubTrigger,
} from '@/components/ui/dropdown-menu';
import { Button } from '@/components/ui/button';
import { NAVIGATION_MENU, type MenuItem } from '@/shared/config/navigation';
import { cn } from "@/shared/utils";

interface AdminNavDropdownProps {
  currentLabel?: string;
  variant?: 'default' | 'outline' | 'ghost';
  size?: 'default' | 'sm' | 'lg';
  className?: string;
}

export function AdminNavDropdown({
  currentLabel = 'Navigate to...',
  variant = 'outline',
  size = 'default',
  className,
}: AdminNavDropdownProps) {
  const pathname = usePathname();

  const renderMenuItem = (item: MenuItem) => {
    const isActive = pathname === item.href || pathname.startsWith(item.href + '/');
    const Icon = item.icon;

    if (item.children && item.children.length > 0) {
      return (
        <DropdownMenuSub key={item.label}>
          <DropdownMenuSubTrigger>
            <Icon className="mr-2 h-4 w-4" />
            <span>{item.label}</span>
          </DropdownMenuSubTrigger>
          <DropdownMenuSubContent>
            {item.children.map((child) => {
              const ChildIcon = child.icon;
              const isChildActive = pathname === child.href || pathname.startsWith(child.href + '/');
              
              return (
                <DropdownMenuItem key={child.href} asChild disabled={child.disabled}>
                  <Link
                    href={child.href || '#'}
                    className={cn(
                      'cursor-pointer',
                      isChildActive && 'bg-accent text-accent-foreground'
                    )}
                  >
                    <ChildIcon className="mr-2 h-4 w-4" />
                    <span>{child.label}</span>
                    {child.badge && (
                      <span className="ml-auto text-xs">{child.badge}</span>
                    )}
                  </Link>
                </DropdownMenuItem>
              );
            })}
          </DropdownMenuSubContent>
        </DropdownMenuSub>
      );
    }

    return (
      <DropdownMenuItem key={item.href} asChild disabled={item.disabled}>
        <Link
          href={item.href || '#'}
          className={cn(
            'cursor-pointer',
            isActive && 'bg-accent text-accent-foreground'
          )}
        >
          <Icon className="mr-2 h-4 w-4" />
          <span>{item.label}</span>
          {item.badge && (
            <span className="ml-auto text-xs">{item.badge}</span>
          )}
        </Link>
      </DropdownMenuItem>
    );
  };

  return (
    <DropdownMenu>
      <DropdownMenuTrigger asChild>
        <Button variant={variant} size={size} className={cn('gap-2', className)}>
          {currentLabel}
          <ChevronDown className="h-4 w-4" />
        </Button>
      </DropdownMenuTrigger>
      <DropdownMenuContent align="start" className="w-56">
        <DropdownMenuLabel>Admin Dashboard</DropdownMenuLabel>
        <DropdownMenuSeparator />
        <DropdownMenuGroup>
          {NAVIGATION_MENU.map((item) => renderMenuItem(item))}
        </DropdownMenuGroup>
      </DropdownMenuContent>
    </DropdownMenu>
  );
}

/**
 * Layout Component Types
 */
import { ReactNode } from 'react';

/**
 * Breadcrumb Item Type
 */
export interface BreadcrumbItemType {
  label: string;
  href?: string;
  isActive?: boolean;
}

/**
 * Admin Layout Props
 */
export interface AdminLayoutProps {
  children: ReactNode;
  className?: string;
}

/**
 * Admin Nav Dropdown Props
 */
export interface AdminNavDropdownProps {
  currentLabel?: string;
  variant?: 'default' | 'outline' | 'ghost';
  size?: 'default' | 'sm' | 'lg';
  className?: string;
}

/**
 * Content Props
 */
export interface ContentProps {
  children: ReactNode;
  className?: string;
  padded?: boolean;
  fullWidth?: boolean;
}

/**
 * Page Header Props
 */
export interface PageHeaderProps {
  title: string;
  description?: string;
  breadcrumbs?: BreadcrumbItemType[];
  action?: ReactNode;
  showBackButton?: boolean;
  onBackClick?: () => void;
  className?: string;
}

/**
 * Search Filter Props
 */
export interface SearchFilterProps {
  placeholder?: string;
  onSearch?: (value: string) => void;
  onFilterClick?: () => void;
  showFilter?: boolean;
  debounceMs?: number;
  className?: string;
}

/**
 * Notification Type for Header
 */
export interface Notification {
  id: string;
  title: string;
  message: string;
  read: boolean;
  timestamp: Date;
}

import { LucideIcon } from 'lucide-react';

export interface MenuItem {
  label: string;
  icon: LucideIcon;
  href?: string;
  children?: MenuItem[];
  badge?: string | number;
  disabled?: boolean;
}

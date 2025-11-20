import { 
  LayoutDashboard, 
  Users, 
  FileText, 
  Settings,
  LucideIcon,
  FolderOpen
} from 'lucide-react';

export interface MenuItem {
  label: string;
  icon: LucideIcon;
  href?: string;
  children?: MenuItem[];
  badge?: string | number;
  disabled?: boolean;
}

export const NAVIGATION_MENU: MenuItem[] = [
  {
    label: 'Dashboard',
    icon: LayoutDashboard,
    href: '/admin',
  },
  {
    label: 'Users',
    icon: Users,
    href: '/admin/users',
  },
  {
    label: 'Posts',
    icon: FileText,
    href: '/admin/posts',
  },
  {
    label: 'File Manager',
    icon: FolderOpen,
    href: '/admin/file-manager',
  },
  {
    label: 'Settings',
    icon: Settings,
    children: [
      { 
        label: 'General', 
        icon: Settings,
        href: '/admin/settings/general' 
      },
      { 
        label: 'Security', 
        icon: Settings,
        href: '/admin/settings/security' 
      },
      { 
        label: 'Notifications', 
        icon: Settings,
        href: '/admin/settings/notifications' 
      },
    ],
  },
];

// Route permissions mapping (for future use)
export const ROUTE_PERMISSIONS: Record<string, string[]> = {
  '/admin': ['admin', 'user'],
  '/admin/users': ['admin'],
  '/admin/posts': ['admin', 'editor'],
  '/admin/file-manager': ['admin', 'editor', 'user'],
  '/admin/settings': ['admin'],
};

// Helper function to check if a route is active
export function isRouteActive(currentPath: string, targetPath?: string): boolean {
  if (!targetPath) return false;
  if (currentPath === targetPath) return true;
  // Check if current path starts with target path (for nested routes)
  return currentPath.startsWith(targetPath + '/');
}

// Helper function to find active menu item
export function findActiveMenuItem(
  menuItems: MenuItem[],
  currentPath: string
): MenuItem | null {
  for (const item of menuItems) {
    if (item.href && isRouteActive(currentPath, item.href)) {
      return item;
    }
    if (item.children) {
      const activeChild = findActiveMenuItem(item.children, currentPath);
      if (activeChild) return activeChild;
    }
  }
  return null;
}

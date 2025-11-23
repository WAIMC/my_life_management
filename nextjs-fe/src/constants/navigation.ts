import { 
  LayoutDashboard, 
  Users, 
  FileText, 
  Settings,
  LucideIcon,
  FolderOpen,
  Database,
  Package,
  Shield,
  Building2,
  Code,
  Sparkles,
  Languages,
  MessageSquare,
  Key,
  FileCheck,
  UserCog,
  Grid3x3,
  Briefcase,
  Layers,
  Image,
  Sliders,
  Share2,
  Link,
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
    label: 'File Manager',
    icon: FolderOpen,
    href: '/admin/file-manager',
  },
  {
    label: 'Master Data',
    icon: Database,
    children: [
      {
        label: 'Admins',
        icon: UserCog,
        href: '/admin/admins',
      },
      {
        label: 'Roles',
        icon: Shield,
        href: '/admin/roles',
      },
      {
        label: 'Departments',
        icon: Building2,
        href: '/admin/departments',
      },
      {
        label: 'APIs',
        icon: Code,
        href: '/admin/apis',
      },
      {
        label: 'Features',
        icon: Sparkles,
        href: '/admin/features',
      },
      {
        label: 'Languages',
        icon: Languages,
        href: '/admin/languages',
      },
      {
        label: 'Translations',
        icon: MessageSquare,
        href: '/admin/translations',
      },
      {
        label: 'Tokens',
        icon: Key,
        href: '/admin/tokens',
      },
      {
        label: 'Policy Departments',
        icon: FileCheck,
        href: '/admin/policy-departments',
      },
      {
        label: 'Original Translators',
        icon: UserCog,
        href: '/admin/original-translators',
      },
    ],
  },
  {
    label: 'Content Management',
    icon: Package,
    children: [
      {
        label: 'Users',
        icon: Users,
        href: '/admin/users',
      },
      {
        label: 'Categories',
        icon: Grid3x3,
        href: '/admin/categories',
      },
      {
        label: 'Skills',
        icon: Briefcase,
        href: '/admin/skills',
      },
      {
        label: 'Skill Descriptions',
        icon: Layers,
        href: '/admin/skill-descriptions',
      },
      {
        label: 'Banners',
        icon: Image,
        href: '/admin/banners',
      },
      {
        label: 'Sliders',
        icon: Sliders,
        href: '/admin/sliders',
      },
      {
        label: 'Social Links',
        icon: Share2,
        href: '/admin/socials',
      },
      {
        label: 'Setting Links',
        icon: Link,
        href: '/admin/setting-links',
      },
    ],
  },
  {
    label: 'Posts',
    icon: FileText,
    href: '/admin/posts',
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

# Admin Dashboard - Documentation

Complete guide for the refactored Next.js + shadcn UI admin dashboard.

## Table of Contents

- [Overview](#overview)
- [Components](#components)
- [Navigation Configuration](#navigation-configuration)
- [Dark Mode](#dark-mode)
- [Error Pages](#error-pages)
- [Customization](#customization)
- [Best Practices](#best-practices)

## Overview

The admin dashboard provides a modern, responsive interface with comprehensive features:

✅ **Dark mode** support with theme toggle  
✅ **Responsive design** for mobile, tablet, and desktop  
✅ **Notification system** with real-time updates  
✅ **Customizable navigation** with dropdown menus  
✅ **Error pages** with helpful guidance  
✅ **Type-safe** with TypeScript  
✅ **Accessible** with ARIA labels and keyboard navigation

## Components

### AdminLayout

Main layout wrapper that combines Sidebar, Header, and Content.

**Location:** [src/components/layout/admin-layout.tsx](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/components/layout/admin-layout.tsx)

**Usage:**

```tsx
import { AdminLayout } from "@/components/layout";

export default function AdminPage() {
  return (
    <AdminLayout>
      <h1>Dashboard Content</h1>
    </AdminLayout>
  );
}
```

**Features:**

- Authentication check with redirect to login
- Loading state during auth verification
- Responsive layout with mobile support
- Dark mode compatible

---

### Sidebar

Navigation sidebar with menu items, submenus, and logout button.

**Location:** [src/components/layout/sidebar.tsx](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/components/layout/sidebar.tsx)

**Features:**

- Active route highlighting
- Expandable submenus with smooth animations
- Mobile toggle with overlay
- Logout button at bottom
- Icons from lucide-react
- Performance optimized with `useMemo`

**Customization:**
Edit navigation menu in [src/constants/navigation.ts](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/constants/navigation.ts)

---

### Header

Top bar with search, notifications, dark mode toggle, and user menu.

**Location:** [src/components/layout/header.tsx](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/components/layout/header.tsx)

**Features:**

- **Search input** with keyboard shortcut hint (⌘K)
- **Dark mode toggle** with Sun/Moon icon
- **Notification dropdown** with:
  - Unread count badge
  - Mark as read functionality
  - Mark all as read button
  - Timestamp formatting with `date-fns`
- **User dropdown menu** with profile and settings links
- Responsive design (mobile/desktop)

**Keyboard Shortcuts:**

- `⌘K` or `Ctrl+K` - Focus search (planned)

---

### PageHeader

Page-level header with title, breadcrumbs, and action buttons.

**Location:** [src/components/layout/page-header.tsx](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/components/layout/page-header.tsx)

**Usage:**

```tsx
import { PageHeader } from "@/components/layout";
import { Button } from "@/components/ui/button";
import { Plus } from "lucide-react";

<PageHeader
  title="Users"
  description="Manage system users and permissions"
  breadcrumbs={[{ label: "Users", isActive: true }]}
  action={
    <Button>
      <Plus className="h-4 w-4 mr-2" />
      Add User
    </Button>
  }
  showBackButton
  onBackClick={() => router.back()}
/>;
```

**Features:**

- Breadcrumb navigation with home icon
- Optional back button
- Action button area for CTAs
- Skeleton loading state available (`PageHeaderSkeleton`)
- Text truncation for long titles

---

### SearchFilter

Search input with debounce and filter button.

**Location:** [src/components/layout/search-filter.tsx](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/components/layout/search-filter.tsx)

**Usage:**

```tsx
import { SearchFilter } from "@/components/layout";

<SearchFilter
  placeholder="Search users..."
  onSearch={(value) => console.log("Search:", value)}
  onFilterClick={() => setShowFilters(true)}
  showFilter
  debounceMs={300}
/>;
```

**Features:**

- Debounced search (300ms default)
- Clear button when input has value
- Loading indicator during search
- `Escape` key to clear search
- Optional filter button

---

### Content

Main content area wrapper with scroll-to-top functionality.

**Location:** [src/components/layout/content.tsx](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/components/layout/content.tsx)

**Usage:**

```tsx
import { Content } from "@/components/layout";

<Content padded fullWidth={false}>
  {/* Your content */}
</Content>;
```

**Props:**

- `padded` - Apply default padding (default: `true`)
- `fullWidth` - Remove max-width constraint (default: `false`)
- `className` - Additional CSS classes

**Features:**

- Auto-hide scroll-to-top button (appears after 300px scroll)
- Smooth scroll behavior
- Responsive padding
- Dark mode support

---

## Navigation Configuration

Navigation menu is centralized in [src/constants/navigation.ts](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/constants/navigation.ts)

### Adding Menu Items

```typescript
import { Users, FileText } from "lucide-react";

export const NAVIGATION_MENU: MenuItem[] = [
  {
    label: "Users",
    icon: Users,
    href: "/admin/users",
  },
  {
    label: "Posts",
    icon: FileText,
    href: "/admin/posts",
    badge: "5", // Optional badge
  },
  // Submenu example
  {
    label: "Settings",
    icon: Settings,
    children: [
      {
        label: "General",
        icon: Settings,
        href: "/admin/settings/general",
      },
    ],
  },
];
```

### Route Permissions

Configure route permissions in the same file:

```typescript
export const ROUTE_PERMISSIONS: Record<string, string[]> = {
  "/admin": ["admin", "user"],
  "/admin/users": ["admin"],
  "/admin/posts": ["admin", "editor"],
};
```

---

## Dark Mode

Dark mode is implemented using `next-themes` with system preference detection.

### Setup

Already configured in:

- [src/app/providers.tsx](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/app/providers.tsx) - ThemeProvider
- [src/app/layout.tsx](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/app/layout.tsx) - suppressHydrationWarning

### Toggle Implementation

The dark mode toggle is in the Header component:

```tsx
import { useTheme } from "next-themes";
import { Sun, Moon } from "lucide-react";

const { theme, setTheme } = useTheme();

<Button onClick={() => setTheme(theme === "dark" ? "light" : "dark")}>
  {theme === "dark" ? <Sun /> : <Moon />}
</Button>;
```

### Dark Mode Classes

Use Tailwind CSS dark mode classes:

```tsx
<div className="bg-white dark:bg-slate-950">
  <p className="text-slate-900 dark:text-white">Text</p>
</div>
```

**Common patterns:**

- Background: `bg-white dark:bg-slate-950`
- Text: `text-slate-900 dark:text-white`
- Borders: `border-slate-200 dark:border-slate-800`
- Subtle text: `text-slate-600 dark:text-slate-400`

---

## Error Pages

Custom error pages with consistent design and helpful actions.

### 404 - Not Found

**Location:** [src/app/not-found.tsx](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/app/not-found.tsx)

**Features:**

- Back to Dashboard button
- Go Back button (browser history)
- Support contact link
- Dark mode support

### 500 - Internal Server Error

**Location:** [src/app/error.tsx](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/app/error.tsx)

**Features:**

- Try Again button to reset error
- Error details toggle (dev mode only)
- Stack trace display (dev mode)
- Support contact link

### 401 - Unauthorized

**Location:** [src/app/unauthorized.tsx](file:///home/vinhdv/projects/my_life_management/nextjs-fe/src/app/unauthorized.tsx)

**Features:**

- Sign In button
- Go Home button
- Admin contact information
- Permission request link

---

## Customization

### Colors & Themes

All components use Tailwind CSS utility classes. To change colors:

1. **Primary color:** Update blue references

   ```tsx
   // Change from blue to purple
   className="bg-blue-600" → className="bg-purple-600"
   ```

2. **Dark mode backgrounds:**
   ```tsx
   // Adjust darkness
   className="dark:bg-slate-950" → className="dark:bg-slate-900"
   ```

### Icons

All icons use `lucide-react`. To change icons:

```tsx
import { YourIcon } from "lucide-react";

<YourIcon className="h-5 w-5" />;
```

Browse icons: [lucide.dev](https://lucide.dev)

### Responsive Breakpoints

Tailwind breakpoints used:

- **Mobile:** default (< 640px)
- **Tablet:** `md:` (≥ 768px)
- **Desktop:** `lg:` (≥ 1024px)

Example:

```tsx
<div className="flex-col md:flex-row lg:gap-6">
```

---

## Best Practices

### 1. Component Organization

```
src/
├── components/
│   ├── layout/          # Layout components
│   ├── ui/              # shadcn UI components
│   └── [feature]/       # Feature-specific components
├── constants/           # Configuration & constants
├── lib/                 # Utilities & helpers
└── app/                 # Next.js app router pages
```

### 2. Consistent Spacing

Use consistent padding/margin scales:

- Small: `p-2` (8px), `p-4` (16px)
- Medium: `p-6` (24px)
- Large: `p-8` (32px)

### 3. Accessibility

Always include:

- `aria-label` for icon-only buttons
- `aria-expanded` for toggles
- `role` attributes for navigation
- Keyboard navigation support

Example:

```tsx
<Button size="icon" aria-label="Toggle menu" aria-expanded={isOpen}>
  <Menu />
</Button>
```

### 4. Performance

- Use `useMemo` for expensive computations
- Use `useCallback` for event handlers passed to children
- Import icons individually: `import { Icon } from 'lucide-react'`

### 5. Dark Mode Testing

Always test components in both light and dark mode:

1. Click dark mode toggle in header
2. Verify all text is readable
3. Check contrast ratios
4. Test hover states

---

## Examples

### Complete Page Example

```tsx
import { AdminLayout, PageHeader, SearchFilter } from "@/components/layout";
import { Button } from "@/components/ui/button";
import { Plus } from "lucide-react";
import { useState } from "react";

export default function UsersPage() {
  const [searchQuery, setSearchQuery] = useState("");

  return (
    <AdminLayout>
      <PageHeader
        title="Users"
        description="Manage system users and permissions"
        breadcrumbs={[{ label: "Users", isActive: true }]}
        action={
          <Button>
            <Plus className="mr-2 h-4 w-4" />
            Add User
          </Button>
        }
      />

      <div className="space-y-6">
        <SearchFilter
          placeholder="Search users..."
          onSearch={setSearchQuery}
          showFilter
        />

        {/* Your content here */}
        <div className="rounded-lg border border-slate-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-950">
          {/* User table or grid */}
        </div>
      </div>
    </AdminLayout>
  );
}
```

---

## Troubleshooting

### Dark mode not working

1. Verify ThemeProvider is in providers.tsx
2. Check `suppressHydrationWarning` in layout.tsx
3. Ensure dark mode classes are applied

### Sidebar not showing on mobile

1. Check z-index values
2. Verify mobile toggle button works
3. Check overlay click handler

### Icons not displaying

1. Verify lucide-react is installed: `pnpm add lucide-react`
2. Import icons correctly: `import { Icon } from 'lucide-react'`
3. Check icon name spelling

---

## Support

For issues or questions:

- Check this documentation
- Review component source code
- Contact development team

---

**Last Updated:** 2025-11-20  
**Version:** 1.0.0

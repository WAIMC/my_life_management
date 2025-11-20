# Quick Reference Guide - Admin Dashboard

## 🚀 Quick Start

## 📁 Main Files

| File | Purpose |
|------|---------|
| `src/components/layout/sidebar.tsx` | Main navigation menu |
| `src/components/layout/header.tsx` | Top header with search & notifications |
| `src/components/layout/admin-layout.tsx` | Main layout wrapper |
| `src/components/layout/page-header.tsx` | Page title + breadcrumb |
| `src/components/layout/search-filter.tsx` | Search component |
| `src/lib/notification.ts` | Toast notification API |
| `src/app/admin/page.tsx` | Dashboard page |
| `src/app/not-found.tsx` | 404 error page |
| `src/app/error.tsx` | 500 error page |

## 🎨 Create New Admin Page

```tsx
'use client';

import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { SearchFilter } from '@/components/layout/search-filter';
import { Button } from '@/components/ui/button';

export default function MyNewPage() {
  return (
    <AdminLayout>
      <PageHeader
        title="My Page Title"
        description="Page description"
        breadcrumbs={[
          { label: 'Admin', href: '/admin' },
          { label: 'My Page', isActive: true }
        ]}
        action={<Button>Create New</Button>}
      />
      
      <div className="mt-6">
        <SearchFilter placeholder="Search..." />
        {/* Your content */}
      </div>
    </AdminLayout>
  );
}
```

## 🔔 Notifications API

```tsx
import { notification } from '@/lib/notification';

// Success
notification.success('Done!');
notification.success('User created!', { duration: 3000 });

// Error
notification.error('Failed!');
notification.error('Something went wrong', { 
  position: 'top-center' 
});

// Loading
const id = notification.loading('Processing...');
setTimeout(() => notification.dismiss(id), 2000);

// Promise
notification.promise(
  fetchData(),
  {
    loading: 'Loading data...',
    success: 'Data loaded!',
    error: 'Failed to load data'
  }
);

// Custom
notification.custom('Custom message');

// Dismiss all
notification.dismiss();
```

## 🎯 Customization

### Change Sidebar Menu Items
Edit `src/components/layout/sidebar.tsx` - `menuItems` array

### Change Colors
- Primary (blue): Replace `blue-*` classes
- Secondary (slate): Replace `slate-*` classes  
- Success (green): Replace `green-*` classes
- Error (red): Replace `red-*` classes

### Add New Shadcn Component
```bash
npx shadcn@latest add component-name
```

### Access Redux State
```tsx
import { useSelector, useDispatch } from 'react-redux';

const auth = useSelector(state => state.auth);
const dispatch = useDispatch();
```

## 📋 Page Structure

```tsx
<AdminLayout>
  {/* Header with breadcrumb */}
  <PageHeader title="..." description="..." />
  
  {/* Search and filters */}
  <div className="mt-6">
    <SearchFilter />
  </div>
  
  {/* Main content */}
  <div className="mt-8">
    {/* Your content */}
  </div>
</AdminLayout>
```

## 🎨 Common UI Components

```tsx
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Avatar, AvatarImage, AvatarFallback } from '@/components/ui/avatar';
import { 
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { 
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
```

## 🌙 Dark Mode

Dark mode is supported automatically! Classes follow pattern:
```tsx
className="
  bg-white dark:bg-slate-950
  text-slate-900 dark:text-white
  border-slate-200 dark:border-slate-800
"
```

## 📱 Responsive Breakpoints

```
Mobile:   default (0px - 639px)
Tablet:   md: (768px - 1023px)
Desktop:  lg: (1024px+)
```

Example:
```tsx
className="w-full md:w-1/2 lg:w-1/3"
```

## 🔍 Common Patterns

### Stats Card
```tsx
<Card className="p-6">
  <p className="text-sm font-medium text-slate-600">Total</p>
  <p className="text-3xl font-bold mt-2">1,234</p>
  <p className="text-sm text-green-600 mt-4">+12% from last month</p>
</Card>
```

### Table Row
```tsx
<tr className="hover:bg-slate-50 dark:hover:bg-slate-800">
  <td className="px-6 py-4 text-sm font-medium">Name</td>
  <td className="px-6 py-4 text-sm text-slate-600">Value</td>
  <td className="px-6 py-4 text-right gap-2">
    <Button variant="ghost" size="sm">Edit</Button>
    <Button variant="ghost" size="sm" className="text-red-600">Delete</Button>
  </td>
</tr>
```

### Status Badge
```tsx
<Badge variant={status === 'Active' ? 'default' : 'secondary'}>
  {status}
</Badge>
```

## 🚀 Performance Tips

1. **Use `'use client'` only when needed**
2. **Memoize expensive computations** - `useMemo`
3. **Debounce search input** - Already done in SearchFilter
4. **Lazy load heavy components** - Use `dynamic()`
5. **Optimize images** - Use Next.js Image component

## 🐛 Troubleshooting

### Build fails
```bash
pnpm run build
# Check error messages above
```

### Dev server slow
```bash
# Clear cache
rm -rf .next
pnpm run dev
```

### Circular dependency issues
- Stored are initialized in `providers.tsx`
- API instance gets store lazily
- This is already fixed! ✅

## 📚 Documentation Files

- `ADMIN_DASHBOARD_SETUP.md` - Detailed setup guide
- `ADMIN_SETUP_COMPLETE.md` - Overview & next steps
- `CHECKLIST.md` - What's been completed
- `QUICK_REFERENCE.md` - This file

## 🔗 Useful Links

- [Next.js Docs](https://nextjs.org/docs)
- [React Docs](https://react.dev)
- [Tailwind CSS](https://tailwindcss.com)
- [Shadcn/UI](https://ui.shadcn.com)
- [Redux](https://redux.js.org)
- [React Hot Toast](https://hot-toast.vercel.app)

---

**Status**: ✅ Ready to use
**Last Updated**: November 11, 2025

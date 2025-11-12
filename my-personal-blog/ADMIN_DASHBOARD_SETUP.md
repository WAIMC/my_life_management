# Admin Dashboard Setup Guide

## Tổng Quan

Dashboard admin đã được thiết lập với các component layout hoàn chỉnh, bao gồm Sidebar, Header, PageHeader, Search/Filter, Breadcrumb, Notification system và Error pages.

## Cấu Trúc

```
src/
├── components/
│   └── layout/
│       ├── index.ts               # Export tất cả layout components
│       ├── sidebar.tsx            # Sidebar navigation component
│       ├── header.tsx             # Header/Topbar component
│       ├── page-header.tsx        # Page header với breadcrumb
│       ├── search-filter.tsx      # Search và filter component
│       ├── content.tsx            # Main content wrapper
│       └── admin-layout.tsx       # Admin layout wrapper
├── lib/
│   └── notification.ts            # Notification service
├── app/
│   ├── providers.tsx              # Redux + Toast providers
│   ├── not-found.tsx              # 404 error page
│   ├── error.tsx                  # 500 error page
│   ├── unauthorized.tsx           # 401 error page
│   └── admin/
│       ├── page.tsx               # Dashboard page
│       ├── users/
│       │   └── page.tsx           # Users management page
│       └── posts/
│           └── page.tsx           # Posts management page
```

## Các Component

### 1. **Sidebar** (`sidebar.tsx`)
- Navigation menu với các item chính
- Hỗ trợ menu con (dropdown)
- Active state tracking dựa trên pathname
- Responsive design (mobile/desktop)
- Logout button ở dưới cùng

**Sử dụng:**
```tsx
import { Sidebar } from '@/components/layout';

export default function App() {
  return <Sidebar />;
}
```

### 2. **Header/Topbar** (`header.tsx`)
- Search input
- Notification bell icon với badge
- User dropdown menu
- Responsive design
- Dark mode support

**Features:**
- Notification list với mark as read
- User profile menu
- Quick search

### 3. **PageHeader** (`page-header.tsx`)
- Page title và description
- Breadcrumb navigation
- Action button area
- Customizable layout

**Sử dụng:**
```tsx
<PageHeader
  title="Users Management"
  description="Manage all users"
  breadcrumbs={[
    { label: 'Admin', href: '/admin' },
    { label: 'Users', isActive: true }
  ]}
  action={<Button>Add User</Button>}
/>
```

### 4. **SearchFilter** (`search-filter.tsx`)
- Search input với debounce
- Filter button
- Clear button
- Responsive design

**Sử dụng:**
```tsx
<SearchFilter
  placeholder="Search..."
  onSearch={(value) => console.log(value)}
  showFilter={true}
/>
```

### 5. **AdminLayout** (`admin-layout.tsx`)
- Wrapper chính cho dashboard
- Kết hợp Sidebar + Header + Content
- Responsive layout
- Dark mode support

**Sử dụng:**
```tsx
<AdminLayout>
  <PageHeader title="My Page" />
  {/* Content here */}
</AdminLayout>
```

## Notification System

### Sử dụng Notification Service

```tsx
import { notification } from '@/lib/notification';

// Success notification
notification.success('User created successfully!');

// Error notification
notification.error('Failed to create user');

// Loading notification
const toastId = notification.loading('Loading...');

// Promise-based
notification.promise(
  fetchData(),
  {
    loading: 'Loading...',
    success: 'Data loaded!',
    error: 'Failed to load data'
  }
);

// Dismiss notification
notification.dismiss(toastId);
```

## Error Pages

### 404 Not Found
```
/src/app/not-found.tsx
```
- Hiển thị khi route không tìm thấy
- Back to Dashboard link

### 500 Internal Server Error
```
/src/app/error.tsx
```
- Error boundary component
- Try Again button
- Error message display

### 401 Unauthorized
```
/src/app/unauthorized.tsx
```
- Access denied page
- Sign In link

## Navigation Setup

Sidebar menu có thể được custom bằng cách chỉnh sửa mảng `menuItems` trong `sidebar.tsx`:

```tsx
const menuItems: MenuItem[] = [
  {
    label: 'Dashboard',
    icon: <LayoutDashboard className="h-5 w-5" />,
    href: '/admin',
  },
  {
    label: 'Settings',
    icon: <Settings className="h-5 w-5" />,
    children: [
      { label: 'General', href: '/admin/settings/general' },
      { label: 'Security', href: '/admin/settings/security' },
    ],
  },
];
```

## Dark Mode

Tất cả component hỗ trợ dark mode với Tailwind CSS classes:
- `dark:bg-slate-900`
- `dark:text-white`
- `dark:border-slate-800`

## Customization

### Colors
Chỉnh sửa Tailwind color classes trong các component files

### Icons
Project sử dụng `lucide-react` for icons

### Responsive Breakpoints
- Mobile: default
- Tablet: `md:`
- Desktop: `lg:`

## Getting Started

1. **Import AdminLayout**
```tsx
import { AdminLayout } from '@/components/layout';
```

2. **Wrap your page**
```tsx
export default function MyPage() {
  return (
    <AdminLayout>
      <PageHeader title="My Page" />
      {/* Your content */}
    </AdminLayout>
  );
}
```

3. **Use Notifications**
```tsx
import { notification } from '@/lib/notification';

notification.success('Done!');
```

## Dependencies

- `next`: 16.0.1
- `react`: 19.2.0
- `tailwindcss`: 4
- `shadcn/ui`: Custom components
- `lucide-react`: Icons
- `react-hot-toast`: Toast notifications
- `react-redux` & `redux-saga`: State management

## Tiếp Theo

1. **Custom menu items** - Chỉnh sửa sidebar menu theo nhu cầu
2. **Add more pages** - Tạo thêm admin pages sử dụng AdminLayout
3. **Integrate API** - Kết nối với backend API
4. **User authentication** - Thêm auth logic
5. **Data tables** - Integrate advanced table library (React Table, TanStack Table)
6. **Charts** - Thêm charting library (Recharts, Chart.js)
7. **Forms** - Sử dụng React Hook Form + validation

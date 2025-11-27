'use client';

import { AdminLayout } from '@/components/layout/admin-layout';
import { PageHeader } from '@/components/layout/page-header';
import { SearchFilter } from '@/components/layout/search-filter';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';

export default function AdminDashboard() {
  const handleSearch = (value: string) => {
  };

  return (
    <AdminLayout>
      {/* Page Header */}
      <PageHeader
        title="Dashboard"
        description="Welcome back! Here's what's happening with your business today."
        breadcrumbs={[{ label: 'Admin', href: '/admin' }, { label: 'Dashboard', isActive: true }]}
        action={
          <Button className="gap-2">
            <svg className="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
              <line x1="12" y1="5" x2="12" y2="19" />
              <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Create New
          </Button>
        }
      />

      {/* Search and Filter */}
      <div className="mt-6 flex items-center justify-between">
        <SearchFilter
          placeholder="Search..."
          onSearch={handleSearch}
        />
      </div>

      {/* Stats Grid */}
      <div className="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        {[
          { label: 'Total Users', value: '1,234', change: '+12%' },
          { label: 'Revenue', value: '$45,231', change: '+8%' },
          { label: 'Total Orders', value: '3,421', change: '+23%' },
          { label: 'Conversion Rate', value: '3.8%', change: '+1.2%' },
        ].map((stat) => (
          <Card key={stat.label} className="p-6">
            <div className="flex items-start justify-between">
              <div>
                <p className="text-sm font-medium text-slate-600 dark:text-slate-400">
                  {stat.label}
                </p>
                <p className="mt-2 text-3xl font-bold text-slate-900 dark:text-white">
                  {stat.value}
                </p>
              </div>
              <div className="rounded-lg bg-green-100 p-2 dark:bg-green-900/20">
                <svg className="h-5 w-5 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2">
                  <polyline points="23 6 13.5 15.5 8.5 10.5 1 17" />
                  <polyline points="17 6 23 6 23 12" />
                </svg>
              </div>
            </div>
            <p className="mt-4 text-sm text-green-600 dark:text-green-400">
              {stat.change} from last month
            </p>
          </Card>
        ))}
      </div>

      {/* Main Content Area */}
      <div className="mt-8 grid gap-6 lg:grid-cols-3">
        {/* Chart Placeholder */}
        <Card className="col-span-1 lg:col-span-2 p-6">
          <h3 className="text-lg font-semibold text-slate-900 dark:text-white">
            Sales Overview
          </h3>
          <div className="mt-6 h-64 flex items-center justify-center bg-slate-100 dark:bg-slate-800 rounded-lg">
            <p className="text-slate-500 dark:text-slate-400">
              Chart placeholder - Integrate your preferred charting library here
            </p>
          </div>
        </Card>

        {/* Recent Activity */}
        <Card className="p-6">
          <h3 className="text-lg font-semibold text-slate-900 dark:text-white">
            Recent Activity
          </h3>
          <div className="mt-4 space-y-4">
            {[1, 2, 3, 4, 5].map((i) => (
              <div key={i} className="flex items-start gap-3 pb-4 border-b border-slate-200 last:border-0 dark:border-slate-800">
                <div className="mt-1 h-2 w-2 rounded-full bg-blue-600 flex-shrink-0" />
                <div className="flex-1 min-w-0">
                  <p className="text-sm font-medium text-slate-900 dark:text-white truncate">
                    Activity {i}
                  </p>
                  <p className="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    {5 - i} hours ago
                  </p>
                </div>
              </div>
            ))}
          </div>
        </Card>
      </div>
    </AdminLayout>
  );
}

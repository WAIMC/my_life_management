"use client";

import React from "react";
import Link from "next/link";
import { Home, BarChart2, Settings } from "lucide-react";
import { usePathname } from "next/navigation";
import { menuItems } from "./menu";

type Props = {
  collapsed?: boolean;
  compact?: boolean;
  onNavigate?: () => void;
};

export default function Sidebar({ collapsed = false, compact = false, onNavigate }: Props) {
  const pathname = usePathname() || "/";
  const containerRef = React.useRef<HTMLElement | null>(null);

  const iconFor = (key: string) => {
    switch (key) {
      case "home":
        return <Home size={16} />;
      case "bar":
        return <BarChart2 size={16} />;
      case "settings":
        return <Settings size={16} />;
      default:
        return <Home size={16} />;
    }
  };

  return (
    <aside
      ref={containerRef}
      className={`h-full bg-white dark:bg-slate-900 border-r sidebar-transition ${compact ? 'sidebar-collapsed' : 'sidebar-expanded'} ${collapsed ? "-translate-x-full md:translate-x-0" : "translate-x-0"}`}
      data-sidebar
    >
      <div className="p-4 border-b">
        <div className="text-xl font-bold">Brand</div>
      </div>

      <nav className="p-4 space-y-1">
        {menuItems.map((m) => {
          const active = pathname === m.href || pathname.startsWith(m.href + "/");
          return (
            <Link
              href={m.href}
              onClick={onNavigate}
              key={m.href}
              className={`flex items-center gap-3 p-2 rounded hover:bg-slate-100 dark:hover:bg-slate-800 ${
                active ? "bg-slate-100 dark:bg-slate-800 font-medium" : ""
              }`}
            >
              {iconFor(m.icon)}
              {!compact && <span>{m.title}</span>}
            </Link>
          );
        })}
      </nav>
    </aside>
  );
}

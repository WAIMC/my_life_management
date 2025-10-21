"use client";

import React from "react";
import Header from "./Header";
import Sidebar from "./Sidebar";
import Footer from "./Footer";
import Breadcrumbs from "./Breadcrumbs";

type Props = {
  children: React.ReactNode;
};

export default function MasterLayout({ children }: Props) {
  const [collapsed, setCollapsed] = React.useState(false);
  const toggleRef = React.useRef<HTMLButtonElement | null>(null);

  // persist collapsed state
  React.useEffect(() => {
    try {
      const saved = localStorage.getItem("mlm:sidebarCollapsed");
      if (saved) setCollapsed(saved === "true");
    } catch {
      /* ignore */
    }
  }, []);

  React.useEffect(() => {
    try {
      localStorage.setItem("mlm:sidebarCollapsed", collapsed ? "true" : "false");
    } catch {
      /* ignore */
    }
  }, [collapsed]);

  // close on ESC when mobile sidebar is open
  React.useEffect(() => {
    const onKey = (e: KeyboardEvent) => {
      if (e.key === "Escape") setCollapsed(false);
    };
    window.addEventListener("keydown", onKey);
    return () => window.removeEventListener("keydown", onKey);
  }, []);

  // focus trap when sidebar open (mobile)
  React.useEffect(() => {
    if (!collapsed) return;
    const container = document.querySelector('[data-sidebar]') as HTMLElement | null;
    if (!container) return;

    const focusable = Array.from(
      container.querySelectorAll<HTMLElement>(
        'a[href], button:not([disabled]), input, textarea, select, [tabindex]:not([tabindex="-1"])'
      )
    ).filter(Boolean);

    const first = focusable[0];
    const last = focusable[focusable.length - 1];
    first?.focus();

    const onKey = (e: KeyboardEvent) => {
      if (e.key !== 'Tab') return;
      if (e.shiftKey) {
        if (document.activeElement === first) {
          e.preventDefault();
          last?.focus();
        }
      } else {
        if (document.activeElement === last) {
          e.preventDefault();
          first?.focus();
        }
      }
    };

    document.addEventListener('keydown', onKey);
    // capture current toggle ref for cleanup
    const currentToggle = toggleRef.current;
    return () => {
      document.removeEventListener('keydown', onKey);
      // return focus to toggle
      currentToggle?.focus();
    };
  }, [collapsed]);

  return (
    // Use distinct root classes so we don't accidentally apply sidebar-specific
    // rules (like `width`) to the root container. `.sidebar-expanded` is used
    // by the sidebar itself to set its width; applying that class to the root
    // made the entire layout narrow. Use `with-sidebar-expanded/compact` here.
    <div className={`min-h-screen flex flex-col bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 ${collapsed ? 'with-sidebar-compact' : 'with-sidebar-expanded'}`}>
      <Header onToggleSidebar={() => setCollapsed((c) => !c)} toggleRef={toggleRef} />

      <div className="flex flex-1">
        {/* Sidebar for desktop */}
        <div className={`hidden md:block`}>
          <Sidebar compact={collapsed} collapsed={false} onNavigate={() => setCollapsed(false)} />
        </div>

        {/* Offcanvas sidebar for small screens */}
        <div className={`md:hidden absolute z-40 inset-0 pointer-events-auto`}> 
          <div
            className={`absolute inset-0 overlay-fade ${collapsed ? 'show' : ''}`}
            onClick={() => setCollapsed(false)}
          />
          <div className={`absolute left-0 top-0 bottom-0 z-50 sidebar-slide ${collapsed ? 'show' : ''}`}>
            <Sidebar compact={false} collapsed={false} onNavigate={() => setCollapsed(false)} />
          </div>
        </div>

        <main className="flex-1 p-4">
          <div className="max-w-7xl mx-auto main-shift">
            <Breadcrumbs />
            {children}
          </div>
        </main>
      </div>

      <Footer />
    </div>
  );
}

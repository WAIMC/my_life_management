"use client";

import React from "react";
import { Sun, Moon, Menu, LogOut } from "lucide-react";
import { useTheme } from "next-themes";
import { Avatar, AvatarImage, AvatarFallback } from "@/components/ui/avatar";
import { useAppDispatch, useAppSelector } from '@/store/hooks';
import { selectCurrentAccount } from '@/store/selectors/accountSelectors';
import { logout as logoutAction } from '@/store/slices/accountSlice';

type Props = {
  onToggleSidebar?: () => void;
  toggleRef?: React.RefObject<HTMLButtonElement | null>;
};

export default function Header({ onToggleSidebar, toggleRef }: Props) {
  const { theme, setTheme } = useTheme();
  const [mounted, setMounted] = React.useState(false);
  React.useEffect(() => setMounted(true), []);

  return (
    <header className="w-full flex items-center justify-between px-4 py-3 border-b bg-white/50 dark:bg-slate-900/50 backdrop-blur">
      <div className="flex items-center gap-3">
        <button
          aria-label="Toggle sidebar"
          onClick={onToggleSidebar}
          ref={toggleRef}
          className="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800"
        >
          <Menu size={18} />
        </button>
        <div className="text-lg font-semibold">Admin Dashboard</div>
      </div>

      <div className="flex items-center gap-3">
        <button
          aria-label="Toggle theme"
          onClick={() => setTheme(theme === "dark" ? "light" : "dark")}
          className="p-2 rounded-md hover:bg-slate-100 dark:hover:bg-slate-800"
        >
          {mounted ? (theme === "dark" ? <Sun size={16} /> : <Moon size={16} />) : <span className="inline-block w-4 h-4" />}
        </button>

        <div className="relative">
          <UserMenu mounted={mounted} />
        </div>
      </div>
    </header>
  );
}

function UserMenu({ mounted }: { mounted?: boolean }) {
  const [open, setOpen] = React.useState(false);
  const dispatch = useAppDispatch();
  const current = useAppSelector(selectCurrentAccount);

  const handleLogout = () => {
    // dispatch a logout action (placeholder: clears account state)
    dispatch(logoutAction());
    setOpen(false);
  };

  return (
    <div>
      <button
        onClick={() => setOpen((o) => !o)}
        aria-haspopup="true"
        aria-expanded={open}
        className="flex items-center gap-2 p-1 rounded hover:bg-slate-100 dark:hover:bg-slate-800"
      >
        <Avatar>
          <AvatarImage className="h-auto w-auto" src={current?.avatar || '/avatar.png'} alt={current?.user_name || 'User avatar'} />
          <AvatarFallback>{(current?.user_name || 'U').charAt(0).toUpperCase()}</AvatarFallback>
        </Avatar>
        <div className="hidden sm:flex flex-col text-sm text-left">
          <span className="font-medium">{mounted ? (current?.user_name ?? 'Guest') : ' '}</span>
          <span className="text-xs text-slate-500 dark:text-slate-400">{mounted ? (current?.email ?? '') : ''}</span>
        </div>
      </button>

      <div
        role="menu"
        className={`origin-top-right absolute right-0 mt-2 w-48 bg-white dark:bg-slate-800 border rounded shadow z-50 transform transition-all duration-150 ${open ? 'opacity-100 scale-100' : 'opacity-0 scale-95 pointer-events-none'}`}
      >
        <div className="p-2">
          <div className="mb-2 text-sm">
            <div className="font-medium">{current?.user_name ?? 'Guest'}</div>
            <div className="text-xs text-slate-500 dark:text-slate-400">{current?.email ?? ''}</div>
          </div>
          <button onClick={handleLogout} className="w-full text-left p-2 flex items-center gap-2 hover:bg-slate-50 dark:hover:bg-slate-700 rounded">
            <LogOut size={14} /> <span>Log out</span>
          </button>
        </div>
      </div>
    </div>
  );
}

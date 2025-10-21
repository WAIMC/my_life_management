import React from "react";

export default function Footer() {
  return (
    <footer className="w-full border-t py-3 px-4 text-sm text-slate-600 dark:text-slate-400 bg-white/50 dark:bg-slate-900/50">
      <div className="max-w-7xl mx-auto">© {new Date().getFullYear()} My Company. All rights reserved.</div>
    </footer>
  );
}

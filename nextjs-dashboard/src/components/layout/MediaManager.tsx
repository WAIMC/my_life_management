"use client";

import React from "react";
import TruncatedText from "@/components/ui/TruncatedText";

type Props = {
  compact?: boolean;
};

export default function MediaManager({ compact = false }: Props) {
  // minimal placeholder UI for media management
  return (
    <div className={`hidden lg:flex flex-col w-64 border-l bg-white dark:bg-slate-900 p-3 gap-3 ${compact ? 'opacity-80' : ''}`}>
      <div className="text-sm font-semibold">Media Manager</div>
      <div className="flex-1 overflow-auto">
        <div className="text-xs text-slate-500">Recent uploads</div>
        <ul className="mt-2 space-y-2">
          <li className="flex items-center gap-2 p-2 rounded hover:bg-slate-100 dark:hover:bg-slate-800 overflow-hidden">
            <div className="w-10 h-10 bg-slate-200 dark:bg-slate-700 rounded flex-shrink-0" />
            <div className="flex-1 min-w-0">
              <TruncatedText text={'image-01.jpg'} className="text-sm" maxWidthClass="max-w-[16rem] sm:max-w-[18rem]" />
            </div>
            <div className="text-xs text-slate-400">12 KB</div>
          </li>
          <li className="flex items-center gap-2 p-2 rounded hover:bg-slate-100 dark:hover:bg-slate-800">
            <div className="w-10 h-10 bg-slate-200 dark:bg-slate-700 rounded" />
            <div className="flex-1 min-w-0">
              <TruncatedText text={'screenshot.png'} className="text-sm" maxWidthClass="max-w-[16rem] sm:max-w-[18rem]" />
            </div>
            <div className="text-xs text-slate-400">256 KB</div>
          </li>
        </ul>
      </div>

      <div className="pt-2 border-t">
        <button className="w-full bg-slate-100 dark:bg-slate-800 py-2 rounded text-sm">Upload</button>
      </div>
    </div>
  );
}

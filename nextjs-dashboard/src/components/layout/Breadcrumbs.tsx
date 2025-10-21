"use client";

import React from "react";
import Link from "next/link";
import { usePathname } from "next/navigation";

export default function Breadcrumbs() {
  const pathname = usePathname() || "/";
  const parts = pathname.split("/").filter(Boolean);

  return (
    <nav className="text-sm text-slate-500 mb-4" aria-label="Breadcrumb">
      <ol className="flex items-center gap-2">
        <li>
          <Link href="/dashboard" className="hover:underline">
            Home
          </Link>
        </li>
        {parts.map((p, idx) => {
          const href = "/" + parts.slice(0, idx + 1).join("/");
          const name = p.replace(/-/g, " ");
          return (
            <li key={href} className="flex items-center gap-2">
              <span className="text-slate-300">/</span>
              <Link href={href} className="hover:underline capitalize">
                {name}
              </Link>
            </li>
          );
        })}
      </ol>
    </nav>
  );
}

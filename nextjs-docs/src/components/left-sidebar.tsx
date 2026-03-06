"use client";

import { Entry } from "@/types/docs";
import Link from "next/link";
import { usePathname } from "next/navigation";
import { cn } from "@/lib/utils";
import { ChevronLeft } from "lucide-react";

interface LeftSidebarProps {
  entries: Entry[];
  categorySlug: string;
}

export default function LeftSidebar({
  entries,
  categorySlug,
}: LeftSidebarProps) {
  const pathname = usePathname();
  const segments = pathname.split("/");
  const activeEntrySlug = segments[segments.length - 1];

  return (
    <div className="p-6">
      <Link
        href="/docs"
        className="flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground mb-6"
      >
        <ChevronLeft className="h-4 w-4" />
        Back to Home
      </Link>

      <div className="space-y-4">
        <h4 className="text-sm font-semibold text-foreground px-2 mb-2">
          Entries
        </h4>
        <nav className="flex flex-col gap-1 pr-4">
          {entries.map((entry) => {
            const isActive = entry.slug === activeEntrySlug;
            return (
              <Link
                key={entry.slug}
                href={`/docs/${categorySlug}/${entry.slug}`}
                className={cn(
                  "block px-3 py-2 text-sm transition-colors rounded-md",
                  isActive
                    ? "bg-accent font-medium text-blue-600 dark:text-blue-400"
                    : "text-muted-foreground hover:bg-accent hover:text-foreground"
                )}
              >
                <span className="block leading-relaxed whitespace-normal break-words">
                  {entry.name}
                </span>
              </Link>
            );
          })}
        </nav>
      </div>
    </div>
  );
}

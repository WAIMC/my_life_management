"use client";

import { Description } from "@/types/docs";
import { slugify, cn } from "@/lib/utils";
import { useEffect, useState, useMemo } from "react";
import { useScrollSpy } from "@/hooks/use-scroll-spy";

interface RightTocProps {
  descriptions: Description[];
}

export default function RightToc({ descriptions }: RightTocProps) {
  const tocItems = useMemo<{ id: string; title: string }[]>(
    () =>
      descriptions.map((desc) => ({
        id: slugify(desc.title),
        title: desc.title,
      })),
    [descriptions]
  );

  const ids = useMemo<string[]>(
    () => tocItems.map((item: { id: string }) => item.id),
    [tocItems]
  );

  const activeId = useScrollSpy(ids, {
    rootMargin: "-20px 0px -80% 0px", // Trigger when top of section is near the top
  });

  const handleClick = (e: React.MouseEvent<HTMLAnchorElement>, id: string) => {
    e.preventDefault();
    const element = document.getElementById(id);
    if (element) {
      element.scrollIntoView({ behavior: "smooth", block: "start" });
      window.history.pushState(null, "", `#${id}`);
    }
  };

  return (
    <div className="py-2 pl-4 pr-6">
      <h4 className="text-sm font-semibold text-foreground mb-4">
        On This Page
      </h4>
      <nav className="flex flex-col gap-3">
        {tocItems.map((item: { id: string; title: string }) => {
          const isActive = activeId === item.id;

          return (
            <a
              key={item.id}
              href={`#${item.id}`}
              onClick={(e) => handleClick(e, item.id)}
              className={cn(
                "group text-[13px] leading-relaxed transition-colors py-1 break-words whitespace-normal block",
                isActive
                  ? "font-medium text-blue-600 dark:text-blue-400"
                  : "text-muted-foreground hover:text-foreground"
              )}
            >
              {item.title}
            </a>
          );
        })}
      </nav>
    </div>
  );
}

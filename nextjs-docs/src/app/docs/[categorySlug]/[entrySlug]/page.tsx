import { api } from "@/lib/api";
import { Entry, EntryDetail } from "@/types/docs";
import MainContent from "@/components/main-content";
import RightToc from "@/components/right-toc";
import Link from "next/link";
import { ChevronLeft, ChevronRight } from "lucide-react";

export default async function EntryDetailPage({
  params,
}: {
  params: Promise<{ entrySlug: string; categorySlug: string }>;
}) {
  const { entrySlug, categorySlug } = await params;
  const entry = (await api.getEntryDetail(entrySlug)) as EntryDetail;
  const entries = (await api.getEntriesByCategory(categorySlug)) as Entry[];

  // Find current index for pagination
  const currentIndex = entries.findIndex((e) => e.slug === entrySlug);
  const prevEntry = currentIndex > 0 ? entries[currentIndex - 1] : null;
  const nextEntry = currentIndex < entries.length - 1 ? entries[currentIndex + 1] : null;

  return (
    <div className="flex w-full items-start">
      {/* Main Content - Centered */}
      <div className="flex-1 min-w-0 py-8 px-6 md:px-12 lg:px-16">
        <div className="max-w-4xl mx-auto">
          <MainContent entry={entry} />
          
          {/* Pagination Buttons */}
          <div className="flex flex-col sm:flex-row items-center justify-between pt-8 mt-12 border-t border-border gap-4">
            {prevEntry ? (
              <Link
                href={`/docs/${categorySlug}/${prevEntry.slug}`}
                className="group flex flex-col items-start gap-1 p-4 rounded-lg border border-border/40 hover:border-border hover:bg-accent transition-all w-full sm:max-w-[48%]"
              >
                <span className="text-xs text-muted-foreground flex items-center gap-1 group-hover:text-foreground transition-colors">
                  <ChevronLeft className="h-3 w-3" /> Previous
                </span>
                <span className="text-sm font-medium text-foreground truncate w-full">
                  {prevEntry.name}
                </span>
              </Link>
            ) : (
              <div className="hidden sm:block" />
            )}

            {nextEntry && (
              <Link
                href={`/docs/${categorySlug}/${nextEntry.slug}`}
                className="group flex flex-col items-end gap-1 p-4 rounded-lg border border-border/40 hover:border-border hover:bg-accent transition-all text-right w-full sm:max-w-[48%]"
              >
                <span className="text-xs text-muted-foreground flex items-center gap-1 group-hover:text-foreground transition-colors">
                  Next <ChevronRight className="h-3 w-3" />
                </span>
                <span className="text-sm font-medium text-foreground truncate w-full">
                  {nextEntry.name}
                </span>
              </Link>
            )}
          </div>
        </div>
      </div>

      {/* Right Sidebar - TOC - Sticky with isolated scroll */}
      <aside className="hidden xl:block w-72 lg:w-80 shrink-0 sticky top-16 h-[calc(100vh-4rem)] overflow-y-auto overscroll-contain scrollbar-hide py-8">
        <RightToc descriptions={entry.descriptions} />
      </aside>
    </div>
  );
}

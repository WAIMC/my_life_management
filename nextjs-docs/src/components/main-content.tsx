"use client";

import { EntryDetail } from "@/types/docs";
import { slugify } from "@/lib/utils";
import { ContentRenderer } from "./content-renderer";

interface MainContentProps {
  entry: EntryDetail;
}

export default function MainContent({ entry }: MainContentProps) {
  return (
    <article className="prose prose-slate dark:prose-invert max-w-none">
      <h1 className="text-4xl font-bold mb-8">{entry.name}</h1>

      {entry.descriptions.map((desc) => {
        const slug = slugify(desc.title);

        return (
          <section key={desc.id} id={slug} className="mb-12 scroll-mt-8">
            <h2 className="text-2xl font-semibold mb-4 border-b pb-2">
              {desc.title}
            </h2>

            {desc.summary && (
              <p className="text-lg text-muted-foreground mb-6">
                {desc.summary}
              </p>
            )}

            <ContentRenderer content={desc.article} />
          </section>
        );
      })}
    </article>
  );
}

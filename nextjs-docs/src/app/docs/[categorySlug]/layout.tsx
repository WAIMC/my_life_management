'use client';

import { useEffect, useState } from "react";
import { useParams } from "next/navigation";
import { api } from "@/lib/api";
import { Entry } from "@/types/docs";
import DocLayoutClient from "@/components/doc-layout-client";

export default function CategoryLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  const params = useParams();
  const categorySlug = params?.categorySlug as string;
  const [entries, setEntries] = useState<Entry[]>([]);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    if (categorySlug) {
      const loadEntries = async () => {
        try {
          const data = await api.getEntriesByCategory(categorySlug) as Entry[];
          setEntries(data);
        } catch (error) {
          console.error('Failed to load entries:', error);
        } finally {
          setIsLoading(false);
        }
      };

      loadEntries();
    }
  }, [categorySlug]);

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
      </div>
    );
  }

  return (
    <DocLayoutClient entries={entries} categorySlug={categorySlug}>
      {children}
    </DocLayoutClient>
  );
}

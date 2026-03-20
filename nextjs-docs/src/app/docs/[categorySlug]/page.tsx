'use client';

import { useEffect, useState } from "react";
import { useParams, useRouter } from "next/navigation";
import { api } from "@/lib/api";
import { Entry } from "@/types/docs";

export default function CategoryPage() {
  const params = useParams();
  const router = useRouter();
  const categorySlug = params?.categorySlug as string;
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    if (categorySlug) {
      const loadAndRedirect = async () => {
        try {
          const entries = (await api.getEntriesByCategory(categorySlug)) as Entry[];

          if (!entries || entries.length === 0) {
            router.push('/docs');
            return;
          }

          // Redirect to first entry
          const firstEntry = entries[0];
          if (firstEntry) {
            router.push(`/docs/${categorySlug}/${firstEntry.slug}`);
          }
        } catch (error) {
          console.error('Failed to load entries:', error);
          router.push('/docs');
        } finally {
          setIsLoading(false);
        }
      };

      loadAndRedirect();
    }
  }, [categorySlug, router]);

  if (isLoading) {
    return (
      <div className="min-h-screen flex items-center justify-center">
        <div className="h-8 w-8 animate-spin rounded-full border-4 border-primary border-t-transparent"></div>
      </div>
    );
  }

  return null;
}

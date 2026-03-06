import { api } from "@/lib/api";
import { notFound } from "next/navigation";
import { Entry } from "@/types/docs";

interface CategoryPageProps {
  params: Promise<{ categorySlug: string }>;
}

export default async function CategoryPage({ params }: CategoryPageProps) {
  const { categorySlug } = await params;
  const entries = (await api.getEntriesByCategory(categorySlug)) as Entry[];

  if (!entries || entries.length === 0) {
    notFound();
  }

  // Redirect to first entry
  const firstEntry = entries[0];
  if (firstEntry) {
    const { redirect } = await import('next/navigation');
    redirect(`/docs/${categorySlug}/${firstEntry.slug}`);
  }

  return null;
}

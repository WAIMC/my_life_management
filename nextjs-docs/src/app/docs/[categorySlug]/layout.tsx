import { api } from "@/lib/api";
import { Entry } from "@/types/docs";
import DocLayoutClient from "@/components/doc-layout-client";

export default async function CategoryLayout({
  children,
  params,
}: {
  children: React.ReactNode;
  params: Promise<{ categorySlug: string }>;
}) {
  const { categorySlug } = await params;
  const entries = (await api.getEntriesByCategory(categorySlug)) as Entry[];

  return (
    <DocLayoutClient entries={entries} categorySlug={categorySlug}>
      {children}
    </DocLayoutClient>
  );
}

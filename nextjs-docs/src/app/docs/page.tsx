import { api } from "@/lib/api";
import { Category } from "@/types/docs";
import SearchBar from "@/components/search-bar";
import HexagonGrid from "@/components/hexagon-grid";

export default async function DocsPage() {
  const categories = await api.getCategories() as Category[];

  return (
    <div className="min-h-screen relative overflow-x-hidden flex flex-col items-center bg-[#f8fbff] bg-gradient-to-br from-blue-50/70 via-white to-purple-50/70">
      {/* 
        The top bar / search section.
        Fixed at the top, centered horizontally, and floating above content.
      */}
      <div className="fixed top-10 left-1/2 -translate-x-1/2 z-50 w-full max-w-2xl px-6">
        <SearchBar />
      </div>

      {/* Hexagon Grid Container - spans full width */}
      {/* Added pt-32 to push the grid down so it's not hidden behind the fixed search bar */}
      <div className="w-full flex-1 relative z-10 pt-32 pb-10 overflow-clip">
        <HexagonGrid categories={categories} />
      </div>
    </div>
  );
}

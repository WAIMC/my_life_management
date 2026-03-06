// For client-side (browser): use NEXT_PUBLIC_API_URL or localhost  
// For server-side (container): use internal nginx
const isServer = typeof window === 'undefined';
const API_BASE_URL = isServer 
  ? 'http://ml-nginx/api'
  : (process.env.NEXT_PUBLIC_API_URL || 'http://localhost/api');

export async function fetchApi<T>(endpoint: string): Promise<T> {
  const response = await fetch(`${API_BASE_URL}${endpoint}`, {
    headers: {
      'Content-Type': 'application/json',
    },
    cache: 'no-store',
  });

  if (!response.ok) {
    throw new Error(`API call failed: ${response.statusText}`);
  }

  const json = await response.json();
  // API response structure: { data: { data: [...] } }
  return json.data?.data || json.data;
}

export const api = {
  async getCategories() {
    return fetchApi('/docs/categories');
  },

  async getEntriesByCategory(categorySlug: string) {
    return fetchApi(`/docs/categories/${categorySlug}/entries`);
  },

  async getEntryDetail(entrySlug: string) {
    return fetchApi(`/docs/entries/${entrySlug}`);
  },

  async search(query: string) {
    return fetchApi(`/docs/search?q=${encodeURIComponent(query)}`);
  },
};

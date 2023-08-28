export interface CategoryList {
  id: number | string;
  parent_id: number;
  name: string;
  slug: string;
  description: string;
  status: number;
  is_display: boolean;
  rank_order: number;
  created_at: string;
  updated_at: string;
}

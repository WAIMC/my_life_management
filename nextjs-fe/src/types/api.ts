/**
 * API Response Types
 */

export interface ApiResponse<T> {
  success: boolean;
  data: T;
  message?: string;
}

export interface ApiErrorResponse {
  success: false;
  message: string;
  errors?: Record<string, string[]>;
  status_code: number;
}

export interface PaginatedResponse<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  from: number;
  last_page: number;
  last_page_url: string;
  links: PaginationLink[];
  next_page_url: string | null;
  path: string;
  per_page: number;
  prev_page_url: string | null;
  to: number;
  total: number;
}

export interface PaginationLink {
  url: string | null;
  label: string;
  active: boolean;
}

/**
 * Common Query Parameters
 */

export interface ListQueryParams {
  page?: number;
  per_page?: number;
  sort_by?: string;
  sort_order?: 'asc' | 'desc';
  from_date?: string; // Format: d/m/Y
  to_date?: string; // Format: d/m/Y
}

/**
 * Master Data Models
 */

export interface AdminMst {
  id: number;
  email: string;
  user_name: string;
  password?: string;
  first_name: string;
  last_name: string;
  address?: string;
  phone_number?: string;
  birth?: string | null;
  gender: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  avatar?: string;
  updated_at: string;
  created_at?: string;
  // Relationships (when included)
  roles?: RoleMst[];
  departments?: DepartmentMst[];
}

export interface RoleMst {
  id: number;
  name: string;
  permission: string;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
  // Relationships
  apis?: ApiMst[];
  features?: FeatureMst[];
}

export interface DepartmentMst {
  id: number;
  code: string;
  name: string;
  status: number;
  is_delete: boolean;
  updated_at: string;
}

export interface FeatureMst {
  id: number;
  name: string;
  group_name?: string;
  description?: string;
  status: number;
  is_delete: boolean;
  updated_at: string;
}

export interface ApiMst {
  id: number;
  type: number;
  name: string;
  path: string;
  is_active: boolean;
  feature_mst_id: number;
  is_delete: boolean;
  updated_at: string;
  // Relationships
  feature?: FeatureMst;
}





export interface TokenMst {
  id: number;
  account_id: number;
  device_name: string;
  ip_address: string;
  expired_at: string;
  updated_at: string;
}

export interface PolicyDepartmentMst {
  id: number;
  table_name: string;
  row_id: number;
  is_delete: boolean;
  updated_at: string;
  // Relationships
  departments?: DepartmentMst[];
}



/**
 * Management Data Models
 */

export interface BannerMgmt {
  id: number;
  title: string;
  slug?: string;
  description?: string;
  link?: string;
  image?: string;
  position?: string;
  status: number;
  is_delete: boolean;
  updated_at: string;
}

export interface CategoryMgmt {
  id: number;
  parent_id?: number;
  name: string;
  slug?: string;
  description?: string;
  status: number;
  is_display?: boolean;
  rank_order: number;
  is_delete: boolean;
  updated_at: string;
  // Relationships
  skills?: SkillMgmt[];
}

export interface SkillMgmt {
  id: number;
  parent_id?: number;
  name: string;
  slug?: string;
  status: number;
  is_display?: boolean;
  rank_order: number;
  is_delete: boolean;
  updated_at: string;
  // Relationships
  skill_descriptions?: SkillDescriptionMgmt[];
  categories?: CategoryMgmt[];
}

export interface SkillDescriptionMgmt {
  id: number;
  parent_id?: number;
  title: string;
  summary?: string;
  article?: string;
  status: number;
  is_display: boolean;
  rank_order: number;
  skill_mgmt_id: number;
  is_delete: boolean;
  updated_at: string;
}

export interface SliderMgmt {
  id: number;
  title: string;
  slug?: string;
  link?: string;
  image?: string;
  status: number;
  is_delete: boolean;
  updated_at: string;
}

export interface SocialMgmt {
  id: number;
  name: string;
  slug?: string;
  link: string;
  image?: string;
  status: number;
  is_display: boolean;
  rank_order: number;
  is_delete: boolean;
  updated_at: string;
}

export interface UserMgmt {
  id: number;
  email: string;
  user_name: string;
  password?: string;
  first_name: string;
  last_name: string;
  address?: string;
  phone_number?: string;
  birth?: string | null;
  gender: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  avatar?: string;
  updated_at: string;
}
}

export interface SettingLinkMgmt {
  id: number;
  name: string;
  url: string;
  description?: string;
  rank_order: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}

/**
 * Junction Table Models
 */

export interface AdminRoleMst {
  admin_mst_id: number;
  role_mst_id: number;
}

export interface AdminDepartmentMst {
  admin_mst_id: number;
  department_mst_id: number;
}

export interface ApiRoleMst {
  api_mst_id: number;
  role_mst_id: number;
}

export interface DepartmentManagementMst {
  department_mst_id: number;
  policy_department_mst_id: number;
}



export interface CategorySkillMgmt {
  category_mgmt_id: number;
  skill_mgmt_id: number;
}

/**
 * Junction Table Update Requests
 */

export interface UpdateJunctionRequest<T> {
  delete?: T[];
  insert?: T[];
}

/**
 * Authentication Types
 */

export interface LoginRequest {
  user_name: string;
  password: string;
}

export interface LoginResponse {
  auth_type: string;
  ttl: number;
  access_token: string;
  _cookie?: string;
}

export interface AuthUser {
  id: number;
  email: string;
  user_name: string;
  first_name: string;
  last_name: string;
  avatar?: string;
  roles?: RoleMst[];
  departments?: DepartmentMst[];
}

/**
 * File Upload Types
 */

export interface UploadResponse {
  url: string;
  filename: string;
  size: number;
  mime_type: string;
}

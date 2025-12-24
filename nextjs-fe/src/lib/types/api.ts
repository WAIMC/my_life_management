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
  password?: string; // Only for create/update
  first_name: string;
  last_name: string;
  address?: string;
  phone_number?: string;
  birth?: string; // Format: d/m/Y
  gender: number; // 1: Male, 2: Female, 3: Other
  status: number; // 1: Active, 2: Inactive
  is_active: boolean;
  is_delete: boolean;
  avatar?: string;
  email_verified_at?: string;
  updated_at: string;
  created_at?: string;
  // Relationships (when included)
  roles?: RoleMst[];
  departments?: DepartmentMst[];
}

export interface RoleMst {
  id: number;
  name: string;
  description?: string;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
  // Relationships
  apis?: ApiMst[];
  features?: FeatureMst[];
}

export interface DepartmentMst {
  id: number;
  name: string;
  description?: string;
  parent_id?: number; // For hierarchical departments
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}

export interface FeatureMst {
  id: number;
  name: string;
  description?: string;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}

export interface ApiMst {
  id: number;
  uri: string; // e.g., "/api/admin-mst"
  method: string; // GET, POST, PUT, DELETE
  description?: string;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}





export interface TokenMst {
  id: number;
  admin_mst_id: number;
  token: string;
  type: string;
  expires_at: string;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}

export interface PolicyDepartmentMst {
  id: number;
  name: string;
  description?: string;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}



/**
 * Management Data Models
 */

export interface BannerMgmt {
  id: number;
  title: string;
  description?: string;
  image_url: string;
  link_url?: string;
  rank_order: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}

export interface CategoryMgmt {
  id: number;
  name: string;
  description?: string;
  icon?: string;
  rank_order: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
  // Relationships
  skills?: SkillMgmt[];
}

export interface SkillMgmt {
  id: number;
  name: string;
  description?: string;
  icon?: string;
  rank_order: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
  // Relationships
  skill_descriptions?: SkillDescriptionMgmt[];
  categories?: CategoryMgmt[];
}

export interface SkillDescriptionMgmt {
  id: number;
  skill_mgmt_id: number;
  description: string;
  rank_order: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}

export interface SliderMgmt {
  id: number;
  title: string;
  description?: string;
  image_url: string;
  link_url?: string;
  rank_order: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  updated_at: string;
}

export interface SocialMgmt {
  id: number;
  platform: string; // e.g., "facebook", "twitter", "linkedin"
  url: string;
  icon?: string;
  rank_order: number;
  status: number;
  is_active: boolean;
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
  birth?: string;
  gender: number;
  status: number;
  is_active: boolean;
  is_delete: boolean;
  avatar?: string;
  updated_at: string;
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

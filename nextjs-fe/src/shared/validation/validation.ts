import { z } from 'zod';
import { Gender, Status, AdminStatus, CategoryStatus } from '@/shared/enums';
import { ValidationRules } from './validation-rules';

// Base validation schemas matching Laravel backend
export const emailValidation = z.string()
  .min(1, 'Email is required')
  .max(ValidationRules.EMAIL_MAX, `Email cannot exceed ${ValidationRules.EMAIL_MAX} characters`)
  .email('Invalid email address');

export const usernameValidation = z.string()
  .min(ValidationRules.USERNAME_MIN, `Username must be at least ${ValidationRules.USERNAME_MIN} characters`)
  .max(ValidationRules.USERNAME_MAX, `Username cannot exceed ${ValidationRules.USERNAME_MAX} characters`);

export const passwordValidation = z.string()
  .min(ValidationRules.PASSWORD_MIN, `Password must be at least ${ValidationRules.PASSWORD_MIN} characters`)
  .max(ValidationRules.PASSWORD_MAX, `Password cannot exceed ${ValidationRules.PASSWORD_MAX} characters`);

export const firstNameValidation = z.string()
  .min(1, 'First name is required')
  .max(ValidationRules.NAME_MAX, `First name cannot exceed ${ValidationRules.NAME_MAX} characters`);

export const lastNameValidation = z.string()
  .min(1, 'Last name is required')
  .max(ValidationRules.NAME_MAX, `Last name cannot exceed ${ValidationRules.NAME_MAX} characters`);

export const addressValidation = z.string()
  .max(ValidationRules.ADDRESS_MAX, `Address cannot exceed ${ValidationRules.ADDRESS_MAX} characters`)
  .optional();

export const phoneValidation = z.string()
  .max(ValidationRules.PHONE_MAX, `Phone cannot exceed ${ValidationRules.PHONE_MAX} characters`)
  .optional();

export const avatarValidation = z.string()
  .max(ValidationRules.AVATAR_MAX, `Avatar path cannot exceed ${ValidationRules.AVATAR_MAX} characters`)
  .optional();

export const genderValidation = z.nativeEnum(Gender);
export const statusValidation = z.nativeEnum(Status);
export const adminStatusValidation = z.nativeEnum(AdminStatus);
export const categoryStatusValidation = z.nativeEnum(CategoryStatus);

// Admin schema matching StoreAdminMstRequest
export const adminSchema = z.object({
  email: emailValidation,
  user_name: usernameValidation,
  password: passwordValidation.optional(),
  first_name: firstNameValidation,
  last_name: lastNameValidation,
  address: addressValidation,
  phone_number: phoneValidation,
  birth: z.string().optional(),
  gender: genderValidation,
  status: adminStatusValidation,
  is_active: z.boolean(),
  avatar: avatarValidation,
});

export type AdminFormData = z.infer<typeof adminSchema>;

// User schema
export const userSchema = z.object({
  email: emailValidation,
  user_name: usernameValidation,
  password: passwordValidation.optional(),
  first_name: firstNameValidation,
  last_name: lastNameValidation,
  address: addressValidation,
  phone_number: phoneValidation,
  birth: z.string().optional(),
  gender: genderValidation,
  status: statusValidation,
  is_active: z.boolean(),
  avatar: avatarValidation,
});

export type UserFormData = z.infer<typeof userSchema>;

// Category schema
export const categorySchema = z.object({
  name: z.string().min(1, 'Name is required'),
  description: z.string().optional(),
  slug: z.string().optional(),
  rank_order: z.coerce.number().min(0, 'Order must be 0 or greater'),
  status: categoryStatusValidation,
  is_display: z.boolean().optional(),
});

export type CategoryFormData = z.infer<typeof categorySchema>;

// Skill schema
export const skillSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  slug: z.string().optional(),
  rank_order: z.coerce.number().min(0, 'Order must be 0 or greater'),
  status: statusValidation,
  is_display: z.boolean().optional(),
});

export type SkillFormData = z.infer<typeof skillSchema>;

// Role schema
export const roleSchema = z.object({
  name: z.string().min(1, 'Name is required').max(30, 'Name cannot exceed 30 characters'),
  permission: z.string().min(1, 'Permission is required').max(50, 'Permission cannot exceed 50 characters'),
  is_active: z.boolean(),
});

export type RoleFormData = z.infer<typeof roleSchema>;

// Department schema
export const departmentSchema = z.object({
  code: z.string().min(1, 'Code is required').max(50, 'Code cannot exceed 50 characters'),
  name: z.string().min(1, 'Name is required'),
  status: statusValidation,
});

export type DepartmentFormData = z.infer<typeof departmentSchema>;

// Banner schema
export const bannerSchema = z.object({
  title: z.string().min(1, 'Title is required'),
  slug: z.string().optional(),
  description: z.string().optional(),
  link: z.string().optional(),
  image: z.string().optional(),
  position: z.string().optional(),
  status: statusValidation,
});

export type BannerFormData = z.infer<typeof bannerSchema>;

// Feature schema
export const featureSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  group_name: z.string().optional(),
  description: z.string().optional(),
  status: statusValidation,
});

export type FeatureFormData = z.infer<typeof featureSchema>;

// Slider schema
export const sliderSchema = z.object({
  title: z.string().min(1, 'Title is required'),
  slug: z.string().optional(),
  link: z.string().optional(),
  image: z.string().optional(),
  status: statusValidation,
});

export type SliderFormData = z.infer<typeof sliderSchema>;

// Social schema
export const socialSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  slug: z.string().optional(),
  link: z.string().url('Must be a valid URL'),
  image: z.string().optional(),
  rank_order: z.coerce.number().min(0, 'Order must be 0 or greater'),
  status: statusValidation,
  is_display: z.boolean().optional(),
});

export type SocialFormData = z.infer<typeof socialSchema>;

// Skill Description schema
export const skillDescriptionSchema = z.object({
  skill_mgmt_id: z.coerce.number().min(1, 'Skill is required'),
  title: z.string().min(1, 'Title is required'),
  summary: z.string().optional(),
  article: z.string().optional(),
  rank_order: z.coerce.number().min(0, 'Order must be 0 or greater'),
  status: statusValidation,
  is_display: z.boolean().optional(),
});

export type SkillDescriptionFormData = z.infer<typeof skillDescriptionSchema>;

// API schema
export const apiSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  path: z.string().min(1, 'Path is required'),
  type: z.coerce.number().min(0, 'Type is required'),
  feature_mst_id: z.coerce.number().min(1, 'Feature is required'),
  is_active: z.boolean(),
});

export type ApiFormData = z.infer<typeof apiSchema>;

// Token schema
export const tokenSchema = z.object({
  account_id: z.coerce.number().min(1, 'Account is required'),
  device_name: z.string().min(1, 'Device name is required'),
  ip_address: z.string().optional(),
  expired_at: z.string().optional(),
});

export type TokenFormData = z.infer<typeof tokenSchema>;

// Setting Link schema
export const settingLinkSchema = z.object({
  name: z.string().min(1, 'Name is required'),
  url: z.string().url('Must be a valid URL'),
  description: z.string().optional(),
  rank_order: z.coerce.number().min(0, 'Order must be 0 or greater'),
  status: statusValidation,
  is_active: z.boolean(),
});

export type SettingLinkFormData = z.infer<typeof settingLinkSchema>;

// Policy Department schema
export const policyDepartmentSchema = z.object({
  table_name: z.string().min(1, 'Table name is required'),
  row_id: z.coerce.number().min(1, 'Row ID is required'),
});

export type PolicyDepartmentFormData = z.infer<typeof policyDepartmentSchema>;

// Post schema
export const postSchema = z.object({
  id: z.number().optional(),
  title: z.string().min(1, 'Title is required'),
  slug: z.string().min(1, 'Slug is required'),
  content: z.string().min(1, 'Content is required'),
  excerpt: z.string().optional(),
  featured_image: urlSchema,
  status: statusSchema,
  is_active: booleanSchema,
  category_id: z.number().int().positive('Category is required'),
  author_id: z.number().int().positive().optional(),
  published_at: z.string().optional(),
});

export type PostFormData = z.infer<typeof postSchema>;

// Generic CRUD item schema
export const crudItemSchema = z.object({
  id: z.number().optional(),
  name: z.string().min(1, 'Name is required'),
  description: z.string().optional(),
  status: statusSchema,
  is_active: booleanSchema,
});

export type CrudItemFormData = z.infer<typeof crudItemSchema>;

// Login schema
export const loginSchema = z.object({
  user_name: z.string().min(1, 'Username is required'),
  password: z.string().min(1, 'Password is required'),
});

export type LoginFormData = z.infer<typeof loginSchema>;

// Pagination params schema
export const paginationParamsSchema = z.object({
  page: z.number().int().positive().default(1),
  per_page: z.number().int().positive().max(100).default(20),
  sort_by: z.string().optional(),
  sort_order: z.enum(['asc', 'desc']).default('asc'),
});

export type PaginationParams = z.infer<typeof paginationParamsSchema>;

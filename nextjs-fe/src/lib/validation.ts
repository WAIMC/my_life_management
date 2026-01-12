import { z } from 'zod';
import { Gender, AdminStatus, CategoryStatus, StatusEnum } from '@/shared/enums/enums';
import { ValidationRules } from './validation-rules.js';

// Base validation schemas matching Laravel backend
export const emailValidation = z.string()
  .min(1, { message: 'validation.email.required' })
  .max(ValidationRules.EMAIL_MAX, { message: 'validation.email.maxLength' })
  .email({ message: 'validation.email.invalid' });

export const usernameValidation = z.string()
  .min(ValidationRules.USERNAME_MIN, { message: 'validation.username.minLength' })
  .max(ValidationRules.USERNAME_MAX, { message: 'validation.username.maxLength' });

export const passwordValidation = z.string()
  .min(ValidationRules.PASSWORD_MIN, { message: 'validation.password.minLength' })
  .max(ValidationRules.PASSWORD_MAX, { message: 'validation.password.maxLength' });

export const firstNameValidation = z.string()
  .min(1, { message: 'validation.firstName.required' })
  .max(ValidationRules.NAME_MAX, { message: 'validation.firstName.maxLength' });

export const lastNameValidation = z.string()
  .min(1, { message: 'validation.lastName.required' })
  .max(ValidationRules.NAME_MAX, { message: 'validation.lastName.maxLength' });

export const addressValidation = z.string()
  .max(ValidationRules.ADDRESS_MAX, { message: 'validation.address.maxLength' })
  .optional();

export const phoneValidation = z.string()
  .max(ValidationRules.PHONE_MAX, { message: 'validation.phone.maxLength' })
  .optional();

export const avatarValidation = z.string()
  .max(ValidationRules.AVATAR_MAX, { message: 'validation.avatar.maxLength' })
  .optional();

export const genderValidation = z.nativeEnum(Gender);
export const statusValidation = z.nativeEnum(StatusEnum);
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
  name: z.string().min(1, { message: 'validation.name.required' }),
  description: z.string().optional(),
  slug: z.string().optional(),
  rank_order: z.coerce.number().min(0, { message: 'validation.order.min' }),
  status: categoryStatusValidation,
  is_display: z.boolean().optional(),
});

export type CategoryFormData = z.infer<typeof categorySchema>;

// Skill schema
export const skillSchema = z.object({
  name: z.string().min(1, { message: 'validation.name.required' }),
  slug: z.string().optional(),
  rank_order: z.coerce.number().min(0, { message: 'validation.order.min' }),
  status: statusValidation,
  is_display: z.boolean().optional(),
});

export type SkillFormData = z.infer<typeof skillSchema>;

// Role schema
export const roleSchema = z.object({
  name: z.string().min(1, { message: 'validation.name.required' }).max(30, { message: 'validation.name.maxLength' }),
  permission: z.string().min(1, { message: 'validation.permission.required' }).max(50, { message: 'validation.permission.maxLength' }),
  is_active: z.boolean(),
});

export type RoleFormData = z.infer<typeof roleSchema>;

// Department schema
export const departmentSchema = z.object({
  code: z.string().min(1, { message: 'validation.code.required' }).max(50, { message: 'validation.code.maxLength' }),
  name: z.string().min(1, { message: 'validation.name.required' }),
  status: statusValidation,
});

export type DepartmentFormData = z.infer<typeof departmentSchema>;

// Banner schema
export const bannerSchema = z.object({
  title: z.string().min(1, { message: 'validation.title.required' }),
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
  name: z.string().min(1, { message: 'validation.name.required' }),
  group_name: z.string().optional(),
  description: z.string().optional(),
  status: statusValidation,
});

export type FeatureFormData = z.infer<typeof featureSchema>;

// Slider schema
export const sliderSchema = z.object({
  title: z.string().min(1, { message: 'validation.title.required' }),
  slug: z.string().optional(),
  link: z.string().optional(),
  image: z.string().optional(),
  status: statusValidation,
});

export type SliderFormData = z.infer<typeof sliderSchema>;

// Social schema
export const socialSchema = z.object({
  name: z.string().min(1, { message: 'validation.name.required' }),
  slug: z.string().optional(),
  link: z.string().url({ message: 'validation.url.invalid' }),
  image: z.string().optional(),
  rank_order: z.coerce.number().min(0, { message: 'validation.order.min' }),
  status: statusValidation,
  is_display: z.boolean().optional(),
});

export type SocialFormData = z.infer<typeof socialSchema>;

// Skill Description schema
export const skillDescriptionSchema = z.object({
  skill_mgmt_id: z.coerce.number().min(1, { message: 'validation.skill.required' }),
  title: z.string().min(1, { message: 'validation.title.required' }),
  summary: z.string().optional(),
  article: z.string().optional(),
  rank_order: z.coerce.number().min(0, { message: 'validation.order.min' }),
  status: statusValidation,
  is_display: z.boolean().optional(),
});

export type SkillDescriptionFormData = z.infer<typeof skillDescriptionSchema>;

// API schema
export const apiSchema = z.object({
  name: z.string().min(1, { message: 'validation.name.required' }),
  path: z.string().min(1, { message: 'validation.path.required' }),
  type: z.coerce.number().min(0, { message: 'validation.type.required' }),
  feature_mst_id: z.coerce.number().min(1, { message: 'validation.feature.required' }),
  is_active: z.boolean(),
});

export type ApiFormData = z.infer<typeof apiSchema>;

// Token schema
export const tokenSchema = z.object({
  account_id: z.coerce.number().min(1, { message: 'validation.account.required' }),
  device_name: z.string().min(1, { message: 'validation.deviceName.required' }),
  ip_address: z.string().optional(),
  expired_at: z.string().optional(),
});

export type TokenFormData = z.infer<typeof tokenSchema>;

// Setting Link schema
export const settingLinkSchema = z.object({
  name: z.string().min(1, { message: 'validation.name.required' }),
  url: z.string().url({ message: 'validation.url.invalid' }),
  description: z.string().optional(),
  rank_order: z.coerce.number().min(0, { message: 'validation.order.min' }),
  status: statusValidation,
  is_active: z.boolean(),
});

export type SettingLinkFormData = z.infer<typeof settingLinkSchema>;

// Policy Department schema
export const policyDepartmentSchema = z.object({
  table_name: z.string().min(1, { message: 'validation.tableName.required' }),
  row_id: z.coerce.number().min(1, { message: 'validation.rowId.required' }),
});

export type PolicyDepartmentFormData = z.infer<typeof policyDepartmentSchema>;

// Post schema
export const postSchema = z.object({
  id: z.number().optional(),
  title: z.string().min(1, { message: 'validation.title.required' }),
  slug: z.string().min(1, { message: 'validation.slug.required' }),
  content: z.string().min(1, { message: 'validation.content.required' }),
  excerpt: z.string().optional(),
  featured_image: z.string().url({ message: 'validation.url.invalid' }).optional(),
  status: statusValidation,
  is_active: z.boolean(),
  category_id: z.number().int().positive({ message: 'validation.category.required' }),
  author_id: z.number().int().positive().optional(),
  published_at: z.string().optional(),
});

export type PostFormData = z.infer<typeof postSchema>;

// Generic CRUD item schema
export const crudItemSchema = z.object({
  id: z.number().optional(),
  name: z.string().min(1, { message: 'validation.name.required' }),
  description: z.string().optional(),
  status: statusValidation,
  is_active: z.boolean(),
});

export type CrudItemFormData = z.infer<typeof crudItemSchema>;

// Login schema
export const loginSchema = z.object({
  user_name: z.string().min(1, { message: 'validation.required' }),
  password: z.string().min(1, { message: 'validation.password.required' }),
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

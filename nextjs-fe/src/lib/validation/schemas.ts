import { z } from 'zod';

/**
 * Common Zod Schemas for Validation
 * 
 * These schemas provide runtime type checking and validation
 * for forms and API responses.
 */

// Base schemas
export const emailSchema = z.string().email('Invalid email address');
export const passwordSchema = z.string().min(8, 'Password must be at least 8 characters');
export const phoneSchema = z.string().regex(/^\+?[1-9]\d{1,14}$/, 'Invalid phone number').optional();
export const urlSchema = z.string().url('Invalid URL').optional();

// Status and common enums
export const statusSchema = z.number().int().min(0).max(2);
export const genderSchema = z.number().int().min(0).max(2);
export const booleanSchema = z.boolean();

// User schema
export const userSchema = z.object({
  id: z.number().optional(),
  user_name: z.string().min(3, 'Username must be at least 3 characters'),
  email: emailSchema,
  password: passwordSchema.optional(),
  first_name: z.string().min(1, 'First name is required'),
  last_name: z.string().min(1, 'Last name is required'),
  gender: genderSchema,
  status: statusSchema,
  is_active: booleanSchema,
  phone_number: phoneSchema,
  address: z.string().optional(),
  birth: z.string().optional(),
});

export type UserFormData = z.infer<typeof userSchema>;

// Category schema
export const categorySchema = z.object({
  id: z.number().optional(),
  name: z.string().min(1, 'Name is required'),
  slug: z.string().min(1, 'Slug is required'),
  description: z.string().optional(),
  status: statusSchema,
  is_active: booleanSchema,
  parent_id: z.number().nullable().optional(),
});

export type CategoryFormData = z.infer<typeof categorySchema>;

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

/**
 * Validation Schemas and Utilities
 */

import { z } from 'zod';

/**
 * Common Validation Rules
 */
export const ValidationRules = {
  // Email
  email: z.string().email('Please enter a valid email address'),

  // Password
  password: z
    .string()
    .min(8, 'Password must be at least 8 characters')
    .regex(/[A-Z]/, 'Password must contain at least one uppercase letter')
    .regex(/[a-z]/, 'Password must contain at least one lowercase letter')
    .regex(/[0-9]/, 'Password must contain at least one number'),

  // Username
  username: z
    .string()
    .min(3, 'Username must be at least 3 characters')
    .max(50, 'Username must not exceed 50 characters')
    .regex(/^[a-zA-Z0-9_]+$/, 'Username can only contain letters, numbers, and underscores'),

  // Phone
  phone: z
    .string()
    .regex(/^[+]?[(]?[0-9]{1,4}[)]?[-\s.]?[(]?[0-9]{1,4}[)]?[-\s.]?[0-9]{1,9}$/, 'Please enter a valid phone number'),

  // Required string
  requiredString: (fieldName: string) =>
    z.string().min(1, `${fieldName} is required`),

  // Optional string
  optionalString: z.string().optional(),

  // Number range
  numberRange: (min: number, max: number) =>
    z.number().min(min, `Must be at least ${min}`).max(max, `Must not exceed ${max}`),

  // Date
  date: z.string().refine((date) => !isNaN(Date.parse(date)), 'Invalid date format'),

  // URL
  url: z.string().url('Please enter a valid URL'),

  // Boolean
  boolean: z.boolean(),
};

/**
 * Auth Validation Schemas
 */
export const AuthSchemas = {
  login: z.object({
    email: ValidationRules.email,
    password: z.string().min(1, 'Password is required'),
    remember: z.boolean().optional(),
  }),

  register: z.object({
    email: ValidationRules.email,
    user_name: ValidationRules.username,
    password: ValidationRules.password,
    password_confirmation: z.string(),
    first_name: ValidationRules.requiredString('First name'),
    last_name: ValidationRules.requiredString('Last name'),
    phone_number: ValidationRules.phone.optional(),
  }).refine((data) => data.password === data.password_confirmation, {
    message: 'Passwords do not match',
    path: ['password_confirmation'],
  }),

  forgotPassword: z.object({
    email: ValidationRules.email,
  }),

  resetPassword: z.object({
    token: z.string().min(1, 'Token is required'),
    email: ValidationRules.email,
    password: ValidationRules.password,
    password_confirmation: z.string(),
  }).refine((data) => data.password === data.password_confirmation, {
    message: 'Passwords do not match',
    path: ['password_confirmation'],
  }),
};

/**
 * Account Validation Schema
 */
export const AccountSchema = z.object({
  email: ValidationRules.email,
  user_name: ValidationRules.username,
  password: ValidationRules.password.optional(),
  first_name: ValidationRules.requiredString('First name'),
  last_name: ValidationRules.requiredString('Last name'),
  address: ValidationRules.requiredString('Address'),
  phone_number: ValidationRules.phone,
  birth: ValidationRules.date,
  gender: z.enum(['male', 'female', 'other']),
  status: z.string(),
  is_active: ValidationRules.boolean,
  avatar: ValidationRules.url.optional(),
  limit_access: z.number().optional(),
});

/**
 * User Validation Schema
 */
export const UserSchema = z.object({
  email: ValidationRules.email,
  user_name: ValidationRules.username,
  password: ValidationRules.password.optional(),
  first_name: ValidationRules.requiredString('First name'),
  last_name: ValidationRules.requiredString('Last name'),
  phone_number: ValidationRules.phone.optional(),
  role: z.string().optional(),
  is_active: ValidationRules.boolean.optional(),
});

/**
 * Validation Helper Functions
 */

/**
 * Validate data against schema
 */
export const validate = <T>(schema: z.ZodSchema<T>, data: unknown): { success: boolean; data?: T; errors?: string[] } => {
  try {
    const result = schema.parse(data);
    return { success: true, data: result };
  } catch (error) {
    if (error instanceof z.ZodError) {
      const errors = error.issues.map((err) => err.message);
      return { success: false, errors };
    }
    return { success: false, errors: ['Validation failed'] };
  }
};

/**
 * Safe parse - returns result without throwing
 */
export const safeParse = <T>(schema: z.ZodSchema<T>, data: unknown) => {
  return schema.safeParse(data);
};

/**
 * Get field errors from Zod error
 */
export const getFieldErrors = (error: z.ZodError): Record<string, string> => {
  const fieldErrors: Record<string, string> = {};
  
  error.issues.forEach((err) => {
    const path = err.path.join('.');
    if (path) {
      fieldErrors[path] = err.message;
    }
  });
  
  return fieldErrors;
};

/**
 * Custom validators
 */
export const customValidators = {
  isStrongPassword: (password: string): boolean => {
    return (
      password.length >= 8 &&
      /[A-Z]/.test(password) &&
      /[a-z]/.test(password) &&
      /[0-9]/.test(password) &&
      /[^A-Za-z0-9]/.test(password)
    );
  },

  isValidPhone: (phone: string): boolean => {
    return /^[+]?[(]?[0-9]{1,4}[)]?[-\s.]?[(]?[0-9]{1,4}[)]?[-\s.]?[0-9]{1,9}$/.test(phone);
  },

  isValidEmail: (email: string): boolean => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  },

  isValidUrl: (url: string): boolean => {
    try {
      new URL(url);
      return true;
    } catch {
      return false;
    }
  },
};

// Export types
export type LoginFormData = z.infer<typeof AuthSchemas.login>;
export type RegisterFormData = z.infer<typeof AuthSchemas.register>;
export type ForgotPasswordFormData = z.infer<typeof AuthSchemas.forgotPassword>;
export type ResetPasswordFormData = z.infer<typeof AuthSchemas.resetPassword>;
export type AccountFormData = z.infer<typeof AccountSchema>;
export type UserFormData = z.infer<typeof UserSchema>;

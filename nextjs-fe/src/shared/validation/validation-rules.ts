/**
 * Validation utilities matching Laravel backend
 * Use these helpers across forms to ensure consistency with API
 */

import { CommonVal } from '@/shared/config/constants';

export const ValidationRules = {
  // String lengths (matching Laravel CommonVal)
  MIN_VARCHAR: CommonVal.MIN_VARCHAR,
  MAX_VARCHAR: CommonVal.MAX_VARCHAR,
  MAX_EMAIL: CommonVal.MAX_EMAIL,
  MAX_PHONE_NUMBER: CommonVal.MAX_PHONE_NUMBER,
  MAX_TEXT: CommonVal.MAX_TEXT,

  // Integer ranges
  MIN_INTEGER: CommonVal.MIN_INTEGER,
  MAX_INTEGER: CommonVal.MAX_INTEGER,
  MAX_BIG_INTEGER: CommonVal.MAX_BIG_INTEGER,

  // Date ranges
  MIN_DATE: CommonVal.MIN_DATE,
  MAX_DATE: CommonVal.MAX_DATE,

  // Specific field lengths (from Laravel FormRequests)
  EMAIL_MAX: 30,
  USERNAME_MAX: 50,
  PASSWORD_MAX: 100,
  FIRST_NAME_MAX: 20,
  LAST_NAME_MAX: 20,
  ADDRESS_MAX: 100,
  AVATAR_MAX: 30,
  ROLE_NAME_MAX: 30,
  ROLE_PERMISSION_MAX: 50,
  CODE_MAX: 50,
  ICON_MAX: 50,
} as const;

/**
 * Format error messages consistently
 */
export const ErrorMessages = {
  required: (field: string) => `${field} is required`,
  minLength: (field: string, min: number) => `${field} must be at least ${min} characters`,
  maxLength: (field: string, max: number) => `${field} must be at most ${max} characters`,
  email: 'Invalid email format',
  url: 'Invalid URL format',
  integer: 'Must be a valid integer',
  minValue: (min: number) => `Must be at least ${min}`,
  maxValue: (max: number) => `Must be at most ${max}`,
  invalidEnum: (field: string) => `Invalid ${field} value`,
} as const;

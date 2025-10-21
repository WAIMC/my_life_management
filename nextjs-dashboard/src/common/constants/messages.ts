/**
 * Message Constants for UI feedback
 */

export const MESSAGES = {
  // Success Messages
  SUCCESS: {
    DEFAULT: 'Operation completed successfully',
    CREATED: 'Created successfully',
    UPDATED: 'Updated successfully',
    DELETED: 'Deleted successfully',
    SAVED: 'Saved successfully',
    LOGIN: 'Login successful',
    LOGOUT: 'Logout successful',
    REGISTERED: 'Registration successful',
    EMAIL_VERIFIED: 'Email verified successfully',
    PASSWORD_RESET: 'Password reset successfully',
    PASSWORD_CHANGED: 'Password changed successfully',
  },

  // Error Messages
  ERROR: {
    DEFAULT: 'An error occurred. Please try again.',
    NETWORK: 'Network error. Please check your connection.',
    TIMEOUT: 'Request timeout. Please try again.',
    SERVER: 'Server error. Please try again later.',
    UNAUTHORIZED: 'Unauthorized. Please login again.',
    FORBIDDEN: 'You do not have permission to perform this action.',
    NOT_FOUND: 'Resource not found.',
    VALIDATION: 'Please check your input and try again.',
    INVALID_CREDENTIALS: 'Invalid email or password.',
    SESSION_EXPIRED: 'Your session has expired. Please login again.',
    UNKNOWN: 'Unknown error occurred.',
  },

  // Validation Messages
  VALIDATION: {
    REQUIRED: (field: string) => `${field} is required`,
    EMAIL: 'Please enter a valid email address',
    PASSWORD_MIN: (min: number) => `Password must be at least ${min} characters`,
    PASSWORD_MATCH: 'Passwords do not match',
    PHONE: 'Please enter a valid phone number',
    MIN_LENGTH: (field: string, min: number) => `${field} must be at least ${min} characters`,
    MAX_LENGTH: (field: string, max: number) => `${field} must not exceed ${max} characters`,
    NUMERIC: (field: string) => `${field} must be a number`,
    ALPHANUMERIC: (field: string) => `${field} must contain only letters and numbers`,
  },

  // Confirmation Messages
  CONFIRM: {
    DELETE: 'Are you sure you want to delete this item?',
    LOGOUT: 'Are you sure you want to logout?',
    CANCEL: 'Are you sure you want to cancel? Unsaved changes will be lost.',
    DISCARD: 'Discard changes?',
  },

  // Loading Messages
  LOADING: {
    DEFAULT: 'Loading...',
    FETCHING: 'Fetching data...',
    SAVING: 'Saving...',
    DELETING: 'Deleting...',
    UPLOADING: 'Uploading...',
    PROCESSING: 'Processing...',
  },
} as const;

export type MessageType = 'success' | 'error' | 'warning' | 'info';

export interface ToastMessage {
  type: MessageType;
  message: string;
  duration?: number;
}

import { AxiosError } from 'axios';
import * as CLIENT_URL from '@/shared/config/clientUrl';
import { toast } from 'react-hot-toast/headless';
import { ApiResponse, ApiErrorResponse } from '@/shared/types/api';

// Error codes - should match i18n (/messages/en.json errors section)
const ERR_CODES = {
  E0004: 'E0004', // Network error
  E0005: 'E0005', // Generic error
  E0400: 'E0400', // Bad Request
  E0401: 'E0401', // Unauthorized
  E0403: 'E0403', // Forbidden
  E0404: 'E0404', // Not Found
  E0500: 'E0500', // Internal Server Error
  E0502: 'E0502', // Bad Gateway
  E0503: 'E0503', // Service Unavailable
  E0504: 'E0504', // Gateway Timeout
  E1000: 'E1000', // Connection error
} as const;

// Helper to get error message from various sources
const getErrorMessage = (error: AxiosError<any>): string => {
  // Try to get message from ApiErrorResponse structure
  if (error.response?.data?.message) {
    return error.response.data.message;
  }

  // Fallback to axios error message
  if (error.message) {
    return error.message;
  }

  return ERR_CODES.E0005;
};


export const handleCommonError = (error: AxiosError<any>) => {
  // Network error - no response from server
  if (!error.response) {
    toast.error(ERR_CODES.E0004);
    return Promise.reject(error);
  }

  const statusCode = error?.response?.status || 0;
  const errorMessage = getErrorMessage(error);

  switch (statusCode) {
    case 400: // Bad Request
      toast.error(errorMessage || ERR_CODES.E0400);
      break;

    case 401: // Unauthorized - Already handled in interceptor
      toast.error(errorMessage || ERR_CODES.E0401);
      break;

    case 403: // Forbidden
      toast.error(errorMessage || ERR_CODES.E0403);
      break;

    case 404: // Not Found
      // Don't redirect - Next.js will handle this with not-found.tsx
      // Just show error toast
      toast.error(errorMessage || 'Resource not found');
      break;

    case 500: // Internal Server Error
      toast.error(errorMessage || ERR_CODES.E0500);
      break;

    case 422:
      // Validation errors are handled by the form components
      return Promise.reject(error);

    case 502:
    case 503:
    case 504: // Server errors
      toast.error(errorMessage || ERR_CODES.E0503 || 'Service temporarily unavailable. Please try again later.');
      break;

    case 507: // Insufficient Storage (Google Drive quota)
      toast.error(errorMessage || 'Storage quota exceeded. Please free up space or upgrade your storage.');
      break;

    default:
      // Unknown error
      toast.error(errorMessage || ERR_MESS.E1000);
      break;
  }

  return Promise.reject(error);
};

export default handleCommonError;

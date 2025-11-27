import { AxiosError } from 'axios';
import * as CLIENT_URL from '@/constants/clientUrl';
import { ERR_MESS } from '@/constants/messages';
import { toast } from 'react-hot-toast/headless';
import { ApiResponse } from '@/types/apiType';

// Helper to get error message from various sources
const getErrorMessage = (error: AxiosError<ApiResponse<unknown>>): string => {
  // Try to get message from ApiResponse.error structure (primary)
  if (error.response?.data?.error?.messages) {
    return error.response.data.error.messages;
  }

  // Fallback: try direct messages field (for backwards compatibility)
  if (error.response?.data && 'messages' in error.response.data && typeof error.response.data.messages === 'string') {
    return error.response.data.messages;
  }

  // Fallback to axios error message
  if (error.message) {
    return error.message;
  }

  return ERR_MESS.E0005;
};


export const handleCommonError = (error: AxiosError<ApiResponse<unknown>>) => {
  // Network error - no response from server
  if (!error.response) {
    toast.error(ERR_MESS.E0004);
    return Promise.reject(error);
  }

  const statusCode = error?.response?.status || 0;
  const errorMessage = getErrorMessage(error);

  switch (statusCode) {
    case 400: // Bad Request
      toast.error(errorMessage || ERR_MESS.E0400);
      break;

    case 401: // Unauthorized - Already handled in interceptor
      toast.error(errorMessage || ERR_MESS.E0401);
      break;

    case 403: // Forbidden
      toast.error(errorMessage || ERR_MESS.E0403);
      break;

    case 404: // Not Found
      // Don't redirect - Next.js will handle this with not-found.tsx
      // Just show error toast
      toast.error(errorMessage || 'Resource not found');
      break;

    case 500: // Internal Server Error
      toast.error(errorMessage || ERR_MESS.E0500);
      break;

    case 502:
    case 503:
    case 504: // Server errors
      toast.error(errorMessage || ERR_MESS.E0503 || 'Service temporarily unavailable. Please try again later.');
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

import { AxiosError } from 'axios';
import * as CLIENT_URL from '@/constants/clientUrl';
import { ERR_MESS } from '@/constants/messages';
import { toast } from 'react-hot-toast/headless';
import { ErrorResponse } from '@/types/apiType';

// Helper to get error message from various sources
const getErrorMessage = (error: AxiosError<ErrorResponse>): string => {
  // Try to get message from response data
  if (error.response?.data?.messages) {
    return error.response.data.messages;
  }

  // Fallback to axios error message
  if (error.message) {
    return error.message;
  }

  return ERR_MESS.E0005;
};

export const handleCommonError = (error: AxiosError<ErrorResponse>) => {
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
      if (typeof window !== 'undefined') {
        window.location.href = CLIENT_URL.NOT_FOUND;
      }
      break;

    case 500: // Internal Server Error
      if (typeof window !== 'undefined') {
        window.location.href = CLIENT_URL.SERVER_ERROR;
      }
      break;

    case 502:
    case 503:
    case 504: // Server errors
      if (typeof window !== 'undefined') {
        window.location.href = CLIENT_URL.SERVER_ERROR;
      }
      break;

    default:
      // Unknown error
      toast.error(errorMessage || ERR_MESS.E1000);
      break;
  }

  return Promise.reject(error);
};

export default handleCommonError;

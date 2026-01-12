import { AxiosError } from 'axios';
import { toast } from 'react-hot-toast/headless';
import { HTTP_STATUS } from '@/shared/config/constant';

const getErrorMessage = (error: AxiosError<{ message?: string }>): string => {
  if (error.response?.data?.message) {
    return error.response.data.message;
  }

  if (error.message) {
    return error.message;
  }

  return 'errors.E0005';
};

export const handleCommonError = (error: AxiosError<{ message?: string }>) => {
  if (!error.response) {
    toast.error('errors.E0004');
    return Promise.reject(error);
  }

  const statusCode = error?.response?.status || 0;
  const errorMessage = getErrorMessage(error);

  switch (statusCode) {
    case HTTP_STATUS.BAD_REQUEST:
      toast.error(errorMessage || 'errors.E0400');
      break;

    case HTTP_STATUS.UNAUTHORIZED:
      toast.error(errorMessage || 'errors.E0401');
      break;

    case HTTP_STATUS.FORBIDDEN:
      toast.error(errorMessage || 'errors.E0403');
      break;

    case HTTP_STATUS.NOT_FOUND:
      toast.error(errorMessage || 'errors.E0404');
      break;

    case HTTP_STATUS.INTERNAL_SERVER_ERROR:
      toast.error(errorMessage || 'errors.E0500');
      break;

    case HTTP_STATUS.UNPROCESSABLE_CONTENT:
      return Promise.reject(error);

    case 502: // BAD_GATEWAY
      toast.error(errorMessage || 'errors.E0502');
      break;

    case 503: // SERVICE_UNAVAILABLE
      toast.error(errorMessage || 'errors.E0503');
      break;

    case 504: // GATEWAY_TIMEOUT
      toast.error(errorMessage || 'errors.E0504');
      break;

    default:
      toast.error(errorMessage || 'errors.E1000');
      break;
  }

  return Promise.reject(error);
};

export default handleCommonError;

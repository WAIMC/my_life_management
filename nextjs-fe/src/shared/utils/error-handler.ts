
import { UseFormSetError } from 'react-hook-form';

interface ApiError {
  status: boolean;
  code: number;
  messages: Record<string, string[]>;
}

interface ApiResponseError {
  response?: {
    data?: {
      error?: ApiError;
      message?: string;
    };
    status?: number;
  };
}

/**
 * Handles backend validation errors and sets them to react-hook-form
 * @param error The API error object
 * @param setError The setError function from react-hook-form
 */
export const handleBindErrors = <T extends Record<string, any>>(
  error: any,
  setError: UseFormSetError<T>
) => {
  if (!error?.response?.data?.error) return;

  const apiError = error.response.data.error as ApiError;

  if (apiError.code === 422 && apiError.messages) {
    Object.entries(apiError.messages).forEach(([field, messages]) => {
      setError(field as any, { // Cast to any because the field name is dynamic
        type: 'server',
        message: messages[0], // Display the first error message
      });
    });
  }
};

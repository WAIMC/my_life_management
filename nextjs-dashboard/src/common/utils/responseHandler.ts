import type { ApiResponse } from '@/common/types/api.types';
import { getErrorMessages } from '@/common/api/client';
import { MESSAGES } from '@/common/constants/messages';

/**
 * Extract success message or fallback; optionally show via toast function.
 */
export const handleSuccess = <T = unknown>(response: ApiResponse<T>, toast?: (opts: { type: 'success'|'info'|'warning'|'error'; message: string }) => void) => {
  if (response && response.error && !response.error.status) {
    const msg = (response?.error?.messages as string) || MESSAGES.SUCCESS.DEFAULT;
    if (toast) toast({ type: 'success', message: String(msg) });
    return { ok: true, message: String(msg), data: response.data as T };
  }
  return { ok: false, message: MESSAGES.ERROR.DEFAULT };
};

/**
 * Extract error messages from response or error object and optionally show toast
 */
export const handleError = (respOrError: unknown, toast?: (opts: { type: 'success'|'info'|'warning'|'error'; message: string }) => void) => {
  let messages: string[] = [String(MESSAGES.ERROR.DEFAULT)];

  if (respOrError && typeof respOrError === 'object' && 'error' in (respOrError as any)) {
  messages = getErrorMessages(respOrError as ApiResponse) as string[];
  } else if (respOrError instanceof Error) {
  messages = [String((respOrError as Error).message)];
  } else if (typeof respOrError === 'string') {
  messages = [String(respOrError)];
  }

  const message = messages.join(', ');
  if (toast) toast({ type: 'error', message });
  return { ok: false, message, messages };
};

/**
 * Encoding and Decoding Utilities
 */

/**
 * Encode to Base64
 */
export const encodeBase64 = (str: string): string => {
  if (typeof window === 'undefined') {
    return Buffer.from(str).toString('base64');
  }
  return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g, (match, p1) => {
    return String.fromCharCode(parseInt(p1, 16));
  }));
};

/**
 * Decode from Base64
 */
export const decodeBase64 = (str: string): string => {
  if (typeof window === 'undefined') {
    return Buffer.from(str, 'base64').toString('utf-8');
  }
  return decodeURIComponent(Array.prototype.map.call(atob(str), (c) => {
    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
  }).join(''));
};

/**
 * Encode object to query string
 */
export const encodeQueryString = (params: Record<string, unknown>): string => {
  return Object.keys(params)
    .filter(key => params[key] !== undefined && params[key] !== null)
    .map(key => {
      const value = params[key] as unknown;
      if (Array.isArray(value)) {
        return (value as Array<string | number | boolean>)
          .map(v => `${encodeURIComponent(key)}[]=${encodeURIComponent(String(v))}`)
          .join('&');
      }
      return `${encodeURIComponent(key)}=${encodeURIComponent(String(value))}`;
    })
    .join('&');
};

/**
 * Decode query string to object
 */
export const decodeQueryString = (queryString: string): Record<string, string | string[]> => {
  const params: Record<string, string | string[]> = {};
  const searchParams = new URLSearchParams(queryString);
  
  searchParams.forEach((value, key) => {
    if (key.endsWith('[]')) {
      const arrayKey = key.slice(0, -2);
      const existing = params[arrayKey];
      if (!existing) {
        params[arrayKey] = [value];
      } else if (Array.isArray(existing)) {
        existing.push(value);
      } else {
        params[arrayKey] = [String(existing), value];
      }
    } else {
      params[key] = value;
    }
  });
  
  return params;
};

/**
 * Sanitize string for URL
 */
export const sanitizeUrl = (str: string): string => {
  return str
    .toLowerCase()
    .replace(/[^\w\s-]/g, '')
    .replace(/[\s_-]+/g, '-')
    .replace(/^-+|-+$/g, '');
};

/**
 * Deep clone object
 */
export const deepClone = <T>(obj: T): T => {
  if (obj === null || typeof obj !== 'object') return obj;
  if (obj instanceof Date) return new Date(obj.getTime()) as unknown as T;
  if (Array.isArray(obj)) return obj.map(item => deepClone(item)) as unknown as T;
  if (obj instanceof Object) {
    const clonedObj = {} as Record<string, unknown>;
    for (const key in obj) {
      if (Object.prototype.hasOwnProperty.call(obj, key)) {
  clonedObj[key] = deepClone((obj as unknown as Record<string, unknown>)[key]);
      }
    }
    return clonedObj as unknown as T;
  }
  return obj;
};

/**
 * Remove undefined/null values from object
 */
export const cleanObject = <T extends Record<string, unknown>>(obj: T): Partial<T> => {
  return Object.keys(obj).reduce((acc, key) => {
    const value = obj[key];
    if (value !== undefined && value !== null) {
      acc[key as keyof T] = value as T[keyof T];
    }
    return acc;
  }, {} as Partial<T>);
};

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
export const encodeQueryString = (params: Record<string, any>): string => {
  return Object.keys(params)
    .filter(key => params[key] !== undefined && params[key] !== null)
    .map(key => {
      const value = params[key];
      if (Array.isArray(value)) {
        return value
          .map(v => `${encodeURIComponent(key)}[]=${encodeURIComponent(v)}`)
          .join('&');
      }
      return `${encodeURIComponent(key)}=${encodeURIComponent(value)}`;
    })
    .join('&');
};

/**
 * Decode query string to object
 */
export const decodeQueryString = (queryString: string): Record<string, any> => {
  const params: Record<string, any> = {};
  const searchParams = new URLSearchParams(queryString);
  
  searchParams.forEach((value, key) => {
    if (key.endsWith('[]')) {
      const arrayKey = key.slice(0, -2);
      if (!params[arrayKey]) {
        params[arrayKey] = [];
      }
      params[arrayKey].push(value);
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
  if (obj instanceof Date) return new Date(obj.getTime()) as any;
  if (obj instanceof Array) return obj.map(item => deepClone(item)) as any;
  if (obj instanceof Object) {
    const clonedObj: any = {};
    for (const key in obj) {
      if (obj.hasOwnProperty(key)) {
        clonedObj[key] = deepClone(obj[key]);
      }
    }
    return clonedObj;
  }
  return obj;
};

/**
 * Remove undefined/null values from object
 */
export const cleanObject = <T extends Record<string, any>>(obj: T): Partial<T> => {
  return Object.keys(obj).reduce((acc, key) => {
    const value = obj[key];
    if (value !== undefined && value !== null) {
      acc[key] = value;
    }
    return acc;
  }, {} as any);
};

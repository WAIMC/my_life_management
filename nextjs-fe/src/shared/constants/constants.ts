export const CommonVal = {
  // HTTP Status Codes
  HTTP_OK: 200,
  HTTP_CREATED: 201,
  HTTP_ACCEPTED: 202,
  HTTP_NO_CONTENT: 204,
  HTTP_BAD_REQUEST: 400,
  HTTP_UNAUTHORIZED: 401,
  HTTP_FORBIDDEN: 403,
  HTTP_NOT_FOUND: 404,
  HTTP_METHOD_NOT_ALLOWED: 405,
  HTTP_UNPROCESSABLE_CONTENT: 422,
  HTTP_INTERNAL_SERVER_ERROR: 500,

  // Date Formats (matching backend d/m/Y format)
  DATE_FORMAT: 'dd/MM/yyyy', // Display format - matches Laravel d/m/Y
  DATE_INPUT_FORMAT: 'yyyy-MM-dd', // HTML input[type="date"] format
  DATETIME_FORMAT: 'dd/MM/yyyy HH:mm:ss', // Full datetime display
  DATETIME_INPUT_FORMAT: 'yyyy-MM-dd HH:mm:ss', // Backend datetime format
  TIME_FORMAT: 'HH:mm:ss', // Time only format
  
  // Backend compatibility formats
  BACKEND_DATE_FORMAT: 'd/m/Y', // Laravel format for reference
  BACKEND_DATETIME_FORMAT: 'd/m/Y H:i:s', // Laravel full datetime
  
  // Validation Limits
  MIN_INTEGER: 0,
  MAX_INTEGER: 2147483647,
  MAX_BIG_INTEGER: 9223372036854775807,
  MIN_DATE: new Date('1900-01-01'),
  MAX_DATE: new Date('2100-12-31'),
  MIN_VARCHAR: 0,
  MAX_VARCHAR: 255,
  MAX_EMAIL: 254,
  MAX_PHONE_NUMBER: 20,
  MAX_TEXT: 65535,

  // Business Logic
  MAX_ACCESS_TTL: 60 * 5, // 5 minutes
  MAX_REFRESH_TTL: 60 * 60 * 24 * 3, // 3 days
  LIMIT_ACCESS_FAIL: 5,
  ADMIN_TYPE: 'admin',
} as const;

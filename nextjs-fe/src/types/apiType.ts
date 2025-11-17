export type ErrorResponse = {
  status: boolean;
  code: number;
  messages: string | null;
};

export type ApiResponse<T> = {
  data: T;
  error: ErrorResponse;
};

export type PaginatedResponse<T> = {
  items: T[];
  totalItems: number;
  totalPages: number;
  currentPage: number;
};

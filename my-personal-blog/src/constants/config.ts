export interface ApiResponse<T> {
  data: T;
  error: {
    status: boolean;
    code: number;
    messages: string | null;
  };
}

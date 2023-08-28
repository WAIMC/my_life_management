export interface _Response<T> {
  data: T;
  error?: {
    status?: boolean;
    code?: number;
    message?: { [key: string]: string[] } | string;
  };
  response?: T;
}

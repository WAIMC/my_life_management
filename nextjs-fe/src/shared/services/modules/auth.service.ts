import { authLock } from "@/shared/utils/auth-lock";
import { apiClient } from "@/shared/utils/api-client";
import { ENDPOINTS } from "@/shared/api/endpoints";

export interface AuthResponse {
  expires_at: number;
  user?: any;
}

export const authService = {
  // ... (keep API methods)
  async login(credentials: any): Promise<AuthResponse> {
    const response = await apiClient.post<any>(ENDPOINTS.AUTH.LOGIN, credentials);
    return response.data;
  },

  async logout(): Promise<void> {
    await apiClient.post(ENDPOINTS.AUTH.LOGOUT);
  },

  async refreshToken(): Promise<AuthResponse> {
    const response = await apiClient.post<any>(ENDPOINTS.AUTH.REFRESH);
    return response.data;
  },

  async getMe(): Promise<AuthResponse & { [key: string]: any }> {
    const response = await apiClient.get<any>(ENDPOINTS.AUTH.ME);
    return response.data;
  },

  async acquireRefreshLock(): Promise<boolean> {
    return await authLock.acquire();
  },

  releaseRefreshLock(): void {
    authLock.release();
  },

  isRefreshLocked(): boolean {
    return authLock.isLocked();
  },
};

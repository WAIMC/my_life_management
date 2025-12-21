import { authLock } from "@/lib/auth-lock";
import { apiClient } from "@/lib/api-client";
import { LOGIN, LOGOUT, REFRESH_TOKEN, ME } from "@/constants/auth-urls";

export interface AuthResponse {
  expires_at: number;
  user?: any;
}

export const authService = {
  // ... (keep API methods)
  async login(credentials: any): Promise<AuthResponse> {
    const response = await apiClient.post<any>(LOGIN, credentials);
    return response.data;
  },

  async logout(): Promise<void> {
    await apiClient.post(LOGOUT);
  },

  async refreshToken(): Promise<AuthResponse> {
    const response = await apiClient.post<any>(REFRESH_TOKEN);
    return response.data;
  },

  async getMe(): Promise<AuthResponse & { [key: string]: any }> {
    const response = await apiClient.get<any>(ME);
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

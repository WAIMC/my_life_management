export type LoginPayload = {
  user_name: string;
  password: string;
}

export type AuthState = {
  accessToken: string | null;
  isAuthenticated: boolean;
  redirectUrl?: string | null;
  tabId: string | null; // Current tab ID
  leaderId: string | null; // ID of leader tab managing refresh
  refreshAtTime: number | null; // Time when token should be refreshed (in ms)
}

export type LoginResponseData = {
  auth_type: string;
  ttl: number;
  access_token: string;
}

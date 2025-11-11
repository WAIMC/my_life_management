export type LoginPayload = {
  user_name: string;
  password: string;
}

export type AuthState = {
  accessToken: string | null;
  isAuthenticated: boolean;
}

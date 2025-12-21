export const AUTH_CHANNEL_NAME = 'auth_sync_channel';

export type AuthMessageType = 
  | 'LOGIN_SUCCESS' 
  | 'REFRESH_SUCCESS' 
  | 'LOGOUT' 
  | 'FORCE_REFRESH';

export interface AuthMessage {
  type: AuthMessageType;
  payload?: any;
}

export interface RefreshSuccessPayload {
  expiresAt: number;
}

export interface LoginSuccessPayload {
  expiresAt: number;
  user: any;
}

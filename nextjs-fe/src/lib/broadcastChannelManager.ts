/**
 * BroadcastChannel Manager - Đồng bộ trạng thái đăng nhập giữa các tab cùng origin
 * 
 * Chức năng:
 * - Gửi/nhận tin nhắn giữa các tab
 * - Quản lý leader_id (tab chủ quản)
 * - Đồng bộ access token, refresh_at_time
 */

export interface BroadcastMessage {
  type: 'AUTH_UPDATE' | 'LOGOUT' | 'TAB_FOCUS' | 'TAB_BLUR' | 'AUTH_REQUEST' | 'AUTH_RESPONSE';
  tabId: string;
  leaderId?: string;
  accessToken?: string;
  refreshAtTime?: number;
  timestamp: number;
  // For AUTH_REQUEST/RESPONSE
  requestId?: string;
}

class BroadcastChannelManager {
  private channel: BroadcastChannel | null = null;
  private tabId: string;
  private messageHandlers: Array<(message: BroadcastMessage) => void> = [];
  private isInitialized = false;

  constructor() {
    // Generate unique tab ID stored in sessionStorage (lost on close, kept on reload)
    this.tabId = this.getOrCreateTabId();
    this.setupFocusListeners();
  }

  /**
   * Initialize BroadcastChannel
   */
  initialize() {
    if (this.isInitialized || typeof window === 'undefined') {
      return;
    }

    try {
      this.channel = new BroadcastChannel('auth-sync');
      this.channel.addEventListener('message', (event) => {
        this.handleMessage(event.data as BroadcastMessage);
      });
      this.isInitialized = true;
    } catch (error) {
      console.warn('BroadcastChannel not supported:', error);
    }
  }

  /**
   * Get or create tab ID
   */
  private getOrCreateTabId(): string {
    if (typeof window === 'undefined') {
      return '';
    }

    let tabId = sessionStorage.getItem('__tab_id__');
    if (!tabId) {
      tabId = `tab_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
      sessionStorage.setItem('__tab_id__', tabId);
    }
    return tabId;
  }

  /**
   * Get current tab ID
   */
  getTabId(): string {
    return this.tabId;
  }

  /**
   * Setup focus/blur listeners to track tab focus state
   */
  private setupFocusListeners() {
    if (typeof window === 'undefined') {
      return;
    }

    window.addEventListener('focus', () => {
      this.broadcast({
        type: 'TAB_FOCUS',
        tabId: this.tabId,
        timestamp: Date.now(),
      });
    });

    window.addEventListener('blur', () => {
      this.broadcast({
        type: 'TAB_BLUR',
        tabId: this.tabId,
        timestamp: Date.now(),
      });
    });
  }

  /**
   * Broadcast message to all tabs
   */
  broadcast(message: BroadcastMessage) {
    if (!this.channel) {
      return;
    }
    this.channel.postMessage(message);
  }

  /**
   * Send AUTH_UPDATE message with token and refresh time
   */
  broadcastAuthUpdate(accessToken: string, refreshAtTime: number, leaderId: string) {
    this.broadcast({
      type: 'AUTH_UPDATE',
      tabId: this.tabId,
      leaderId,
      accessToken,
      refreshAtTime,
      timestamp: Date.now(),
    });
  }

  /**
   * Send LOGOUT message
   */
  broadcastLogout(leaderId: string) {
    this.broadcast({
      type: 'LOGOUT',
      tabId: this.tabId,
      leaderId,
      timestamp: Date.now(),
    });
  }

  /**
   * Request auth state from other tabs
   * Returns a promise that resolves when another tab responds
   */
  requestAuthState(timeoutMs: number = 1000): Promise<BroadcastMessage | null> {
    return new Promise((resolve) => {
      const requestId = `req_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
      let resolved = false;

      // Listen for response
      const responseHandler = (message: BroadcastMessage) => {
        if (message.type === 'AUTH_RESPONSE' && message.requestId === requestId && !resolved) {
          resolved = true;
          // Remove this specific handler
          const index = this.messageHandlers.indexOf(responseHandler);
          if (index > -1) {
            this.messageHandlers.splice(index, 1);
          }
          resolve(message);
        }
      };

      this.onMessage(responseHandler);

      // Send request
      this.broadcast({
        type: 'AUTH_REQUEST',
        tabId: this.tabId,
        requestId,
        timestamp: Date.now(),
      });

      // Timeout if no response
      setTimeout(() => {
        if (!resolved) {
          resolved = true;
          const index = this.messageHandlers.indexOf(responseHandler);
          if (index > -1) {
            this.messageHandlers.splice(index, 1);
          }
          resolve(null);
        }
      }, timeoutMs);
    });
  }

  /**
   * Respond to auth state request from another tab
   */
  respondAuthState(requestId: string, accessToken: string, refreshAtTime: number, leaderId: string) {
    this.broadcast({
      type: 'AUTH_RESPONSE',
      tabId: this.tabId,
      requestId,
      leaderId,
      accessToken,
      refreshAtTime,
      timestamp: Date.now(),
    });
  }

  /**
   * Register message handler
   */
  onMessage(handler: (message: BroadcastMessage) => void) {
    this.messageHandlers.push(handler);
  }

  /**
   * Handle received message
   */
  private handleMessage(message: BroadcastMessage) {
    this.messageHandlers.forEach((handler) => {
      handler(message);
    });
  }

  /**
   * Close BroadcastChannel
   */
  close() {
    if (this.channel) {
      this.channel.close();
      this.channel = null;
    }
    this.isInitialized = false;
  }
}

// Singleton instance
const broadcastManager = new BroadcastChannelManager();

export default broadcastManager;

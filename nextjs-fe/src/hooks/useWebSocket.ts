import { useEffect, useState, useRef, useCallback } from 'react';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Ensure Pusher is available globally for Echo
// eslint-disable-next-line @typescript-eslint/no-explicit-any
(globalThis as any).Pusher = Pusher;

interface WebSocketConfig {
  token?: string;
  roomId?: string; // Used to derive channel name: private-upload.status.{roomId}
}

interface WebSocketMessage {
  type: string;
  roomId?: string;
  [key: string]: unknown;
}

export const useWebSocket = ({
  token,
  roomId,
}: WebSocketConfig) => {
  const [isConnected, setIsConnected] = useState(false);
  const [lastMessage, setLastMessage] = useState<WebSocketMessage | null>(null);
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const echoRef = useRef<Echo<any> | null>(null);

  useEffect(() => {
    // Only initialize Echo if roomId is provided
    // This prevents unnecessary WebSocket connections
    if (!roomId) {
      console.log('No roomId provided, skipping WebSocket connection');
      return;
    }

    // Cookie-based auth: Initialize Echo
    // The HttpOnly cookie will be sent automatically

    // Configure Echo with Reverb
    // Reverb uses Pusher protocol
    const echoConfig = {
      broadcaster: 'reverb' as const,
      key: process.env.NEXT_PUBLIC_REVERB_APP_KEY || 'my-app-key',
      wsHost: process.env.NEXT_PUBLIC_REVERB_HOST || 'localhost',
      wsPort: process.env.NEXT_PUBLIC_REVERB_PORT ? parseInt(process.env.NEXT_PUBLIC_REVERB_PORT) : 8080,
      wssPort: process.env.NEXT_PUBLIC_REVERB_PORT ? parseInt(process.env.NEXT_PUBLIC_REVERB_PORT) : 8080,
      forceTLS: (process.env.NEXT_PUBLIC_REVERB_SCHEME || 'http') === 'https',
      enabledTransports: ['ws', 'wss'],
      // Cookie will be sent automatically by browser (path=/api/admin, httpOnly)
      authEndpoint: `${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api'}/admin/broadcasting/auth`,
      auth: {
        headers: token ? {
          Authorization: `Bearer ${token}`,
        } : {},
      },
    };

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    const echo = new Echo(echoConfig as any);

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (echo.connector as any).pusher?.connection.bind('connected', () => {
      console.log('Reverb Connected for room:', roomId);
      setIsConnected(true);
    });

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    (echo.connector as any).pusher?.connection.bind('disconnected', () => {
      console.log('Reverb Disconnected');
      setIsConnected(false);
    });

    echoRef.current = echo;

    return () => {
      echo.disconnect();
    };
  }, [token, roomId]); // Include roomId to reconnect when it changes

  // Subscribe to Room Channel
  useEffect(() => {
    if (!echoRef.current || !roomId || !isConnected) return;

    // Correct channel name mapping based on backend: `upload.status.{roomId}`
    // `private-` prefix is added automatically by `.private()`
    const channelName = `upload.status.${roomId}`;
    
    console.log(`Subscribing to private channel: ${channelName}`);
    
    const channel = echoRef.current.private(channelName);

    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    channel.listen('.upload.status.updated', (event: any) => {
      console.log('Received event:', event);
      // Map event data to legacy WebSocketMessage format for compatibility
      setLastMessage({
        type: 'UPLOAD_STATUS', // Synthetic type
        roomId: event.roomId,
        status: event.status,
        message: event.message,
        fileId: event.fileId,
        url: event.url,
      });
    });

    return () => {
      echoRef.current?.leave(channelName);
    };
  }, [roomId, isConnected]);

  // Deprecated compatibility methods
  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  const sendMessage = useCallback((_data: unknown) => {
     console.warn('sendMessage is not supported with Laravel Echo in this implementation');
  }, []);

  // eslint-disable-next-line @typescript-eslint/no-unused-vars
  const joinRoom = useCallback((_id: string) => {
     // Managed via prop `roomId` now
     console.warn('joinRoom is handled via props in this implementation');
  }, []);

  return { isConnected, lastMessage, sendMessage, joinRoom };
};

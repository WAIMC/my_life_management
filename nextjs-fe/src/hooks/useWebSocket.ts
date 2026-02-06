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
    if (!token) return;

    // Configure Echo with Reverb
    // Reverb uses Pusher protocol
    const echo = new Echo({
      broadcaster: 'reverb',
      key: process.env.NEXT_PUBLIC_REVERB_APP_KEY || 'my-app-key',
      wsHost: process.env.NEXT_PUBLIC_REVERB_HOST || 'localhost',
      wsPort: process.env.NEXT_PUBLIC_REVERB_PORT ? parseInt(process.env.NEXT_PUBLIC_REVERB_PORT) : 8080,
      wssPort: process.env.NEXT_PUBLIC_REVERB_PORT ? parseInt(process.env.NEXT_PUBLIC_REVERB_PORT) : 8080,
      forceTLS: (process.env.NEXT_PUBLIC_REVERB_SCHEME || 'http') === 'https',
      enabledTransports: ['ws', 'wss'],
      authEndpoint: `${process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000'}/api/broadcasting/auth`,
      auth: {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    });

    echo.connector.pusher.connection.bind('connected', () => {
      console.log('Reverb Connected');
      setIsConnected(true);
    });

    echo.connector.pusher.connection.bind('disconnected', () => {
      console.log('Reverb Disconnected');
      setIsConnected(false);
    });

    echoRef.current = echo;

    return () => {
      echo.disconnect();
    };
  }, [token]);

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

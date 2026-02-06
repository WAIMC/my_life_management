'use client';

import { useEffect, useRef, useCallback } from 'react';
import { useWebSocket } from '@/hooks/useWebSocket';
import { notification } from '@/shared/utils/notification';

interface HeavyUploadNotificationProps {
  roomId: string;
  fileName: string;
  onComplete: () => void;
}

/**
 * Component to handle WebSocket notification for heavy file upload
 * Automatically connects to room, listens for status updates, and disconnects
 * Uses cookie-based auth (HttpOnly cookie sent automatically)
 * 
 * Improvements:
 * - Proper cleanup to prevent memory leaks
 * - Timeout fallback if no response after 15 minutes
 * - Prevent duplicate notifications with better state management
 */
export function HeavyUploadNotification({ roomId, fileName, onComplete }: HeavyUploadNotificationProps) {
  // Pass roomId only - token will be sent via HttpOnly cookie automatically
  const { isConnected, lastMessage } = useWebSocket({ roomId });
  const hasNotified = useRef(false);
  const timeoutRef = useRef<NodeJS.Timeout | null>(null);

  console.log('[HeavyUploadNotification] Component mounted for:', { roomId, fileName });

  // Memoized complete handler to prevent re-creating
  const handleComplete = useCallback((status: 'success' | 'error' | 'timeout', message: string) => {
    if (hasNotified.current) return;
    hasNotified.current = true;

    // Clear timeout if exists
    if (timeoutRef.current) {
      clearTimeout(timeoutRef.current);
      timeoutRef.current = null;
    }

    // Show notification
    if (status === 'success') {
      notification.success(`✓ ${fileName}: ${message}`);
    } else if (status === 'error') {
      notification.error(`✗ ${fileName}: ${message}`);
    } else {
      notification.warning(`⚠ ${fileName}: ${message}`);
    }

    // Cleanup
    onComplete();
  }, [fileName, onComplete]);

  // Listen for WebSocket messages
  useEffect(() => {
    if (!lastMessage) return;

    // Check if this is an upload status message for our room
    if (lastMessage.type === 'UPLOAD_STATUS' && lastMessage.roomId === roomId) {
      const { status, message } = lastMessage;

      if (status === 2) {
        // COMPLETED
        handleComplete('success', message as string);
      } else if (status === 3) {
        // FAILED
        handleComplete('error', message as string);
      }
    }
  }, [lastMessage, roomId, handleComplete]);

  // Timeout fallback - if no response after 15 minutes, show warning
  useEffect(() => {
    timeoutRef.current = setTimeout(() => {
      handleComplete('timeout', 'Upload taking longer than expected. Check status later.');
    }, 15 * 60 * 1000); // 15 minutes

    return () => {
      if (timeoutRef.current) {
        clearTimeout(timeoutRef.current);
      }
    };
  }, [handleComplete]);

  // Log connection status for debugging
  useEffect(() => {
    if (isConnected) {
      console.log(`[HeavyUploadNotification] Connected to room: ${roomId}`);
    }
    
    return () => {
      console.log(`[HeavyUploadNotification] Cleanup for room: ${roomId}`);
    };
  }, [isConnected, roomId]);

  return null; // This is a headless component
}

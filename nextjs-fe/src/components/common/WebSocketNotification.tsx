'use client';
import { useAuth } from '@/providers/use-auth';
import { useWebSocket } from '@/hooks/useWebSocket';
import { useEffect } from 'react';
import toast from 'react-hot-toast';

export function WebSocketNotification() {
  const { user } = useAuth();
  
  // Create the room ID based on user ID
  // Convention: "{userId}_noti_upload_file"
  const roomId = user?.id ? `${user.id}_noti_upload_file` : undefined;

  // We don't pass token here because we rely on Cookies (HttpOnly) being sent automatically
  // or we would need to retrieve it. For now, assuming Cookie auth.
  const { isConnected, lastMessage, joinRoom } = useWebSocket({});

  // Join room when connected and user is available
  useEffect(() => {
    if (isConnected && roomId) {
      console.log(`Joining room: ${roomId}`);
      // Join via message if server supports it, or we rely on query param in useWebSocket
      // Our useWebSocket hook connects with query params. 
      // Modify useWebSocket to allow updating URL or sending join message.
      // Current useWebSocket doesn't dynamic update URL easily without reconnect.
      // But we have `joinRoom` function that sends a message.
      joinRoom(roomId);
    }
  }, [isConnected, roomId, joinRoom]);

  // Handle Messages
  useEffect(() => {
    if (lastMessage && lastMessage.type === 'UPLOAD_STATUS') {
      const message = (lastMessage.message as string);
      // 2 = Completed, 3 = Failed
      if (lastMessage.status === 2 || lastMessage.status === 'completed') {
        toast.success(message || 'File upload completed successfully!', {
           duration: 5000,
        });
      } else if (lastMessage.status === 3 || lastMessage.status === 'failed') {
        toast.error(message || 'File upload failed.', {
           duration: 5000,
        });
      }
    }
  }, [lastMessage]);

  return null; // Render nothing
}

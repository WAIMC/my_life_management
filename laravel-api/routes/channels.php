<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
  return (int) $user->id === (int) $id;
});

Broadcast::channel('upload.status.{roomId}', function ($user, $roomId) {
  // Logic to authorize user for this room.
  // Room Format: "{uuid}_{userId}_upload_file"
  // Extract userId from middle part
  
  $parts = explode('_', $roomId);
  // Expected format: [uuid, userId, 'upload', 'file']
  if (count($parts) >= 4) {
    $userId = $parts[1];
    
    // Get user ID from AdminMiddleware attributes
    // Note: $user here is authenticated via AdminMiddleware's JWT
    // AdminMiddleware stores 'current_admin_id' in request attributes
    // But in broadcast auth context, we need to extract from JWT again
    // So we compare with the userId embedded in roomId
    
    // Since AdminMiddleware already authenticated this user,
    // we just need to verify the roomId belongs to them
    return (int)$userId === (int)$user->id;
  }
  
  return false;
});

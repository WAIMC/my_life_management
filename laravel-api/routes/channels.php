<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
  return (int) $user->id === (int) $id;
});

Broadcast::channel('upload.status.{roomId}', function ($user, $roomId) {
  // Logic to authorize user for this room.
  // Ensure roomId starts with userId to verify ownership
  // Room Format: "{userId}_noti_upload_file"

  $parts = explode('_', $roomId);
  if (count($parts) > 0 && (int)$parts[0] === (int)$user->id) {
    return true;
  }
  return false;
});

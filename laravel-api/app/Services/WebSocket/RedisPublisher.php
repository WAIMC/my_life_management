<?php

namespace App\Services\WebSocket;

use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class RedisPublisher
{
  /**
   * Publish a message to a Redis channel.
   * 
   * @param string $channel
   * @param array $data
   * @return void
   */
  public function publish(string $channel, array $data): void
  {
    try {
      Redis::publish($channel, json_encode($data));
      Log::info("Published to Redis channel {$channel}", $data);
    } catch (\Exception $e) {
      Log::error("Failed to publish to Redis channel {$channel}", [
        'error' => $e->getMessage(),
        'data' => $data
      ]);
    }
  }
}

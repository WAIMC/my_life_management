<?php

namespace App\Services;

abstract class SingletonService
{
  /**
   * @var static|null
   */
  protected static ?self $instance = null;

  // The constructor is private to prevent initiation with outer code.
  private function __construct()
  {
    // Optional: Perform any initialization tasks here
  }

  // Prevent the instance from being cloned.
  private function __clone()
  {
    // Prevent creating multiple instances
  }

  // Prevent from being unserialized.
  public function __wakeup()
  {
    throw new \Exception('Cannot unserialize singleton instance.');
  }

  /**
   * Get the singleton instance.
   *
   * @return static
   */
  public static function getInstance(): self
  {
    if (static::$instance === null) {
      static::$instance = new static();
    }

    return static::$instance;
  }
}

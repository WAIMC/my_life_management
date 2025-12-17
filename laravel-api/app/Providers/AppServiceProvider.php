<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    // Bind interfaces to implementations
    $this->app->bind(
      \App\Interfaces\Management\MediaFileInterface::class,
      \App\Repositories\Management\MediaFileRepository::class
    );

    $this->app->resolving(\Illuminate\Console\Command::class, function ($command, $app) {
      $command->setLaravel($app);
    });
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    //
  }
}

<?php

declare(strict_types=1);

namespace App\Providers;

use App\Interfaces\Master\AdminInterface;
use App\Repositories\Master\AdminRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public $bindings = [
        AdminInterface::class => AdminRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        foreach ($this->bindings as $repositoryInterface => $repository) {
            $this->app->bind($repositoryInterface, $repository);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {}
}

<?php

declare(strict_types=1);

namespace App\Providers;

use App\Interfaces\Management\CategoryInterface;
use App\Interfaces\Master\AdminDepartmentInterface;
use App\Interfaces\Master\AdminInterface;
use App\Interfaces\Master\AdminRoleInterface;
use App\Interfaces\Master\ApiInterface;
use App\Interfaces\Master\ApiRoleInterface;
use App\Interfaces\Master\DepartmentManagementInterface;
use App\Repositories\Management\CategoryRepository;
use App\Repositories\Master\AdminDepartmentRepository;
use App\Repositories\Master\AdminRepository;
use App\Repositories\Master\AdminRoleRepository;
use App\Repositories\Master\ApiRepository;
use App\Repositories\Master\ApiRoleRepository;
use App\Repositories\Master\DepartmentManagementRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public $bindings = [
        // Master
        AdminDepartmentInterface::class => AdminDepartmentRepository::class,
        AdminInterface::class => AdminRepository::class,
        AdminRoleInterface::class => AdminRoleRepository::class,
        ApiInterface::class => ApiRepository::class,
        ApiRoleInterface::class => ApiRoleRepository::class,
        DepartmentManagementInterface::class => DepartmentManagementRepository::class,

        // Management
        CategoryInterface::class => CategoryRepository::class,
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
    {
    }
}

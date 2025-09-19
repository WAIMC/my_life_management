<?php

declare(strict_types=1);

namespace App\Providers;

use App\Interfaces\Management\CategoryMgmtInterface;
use App\Interfaces\Master\AdminDepartmentMstInterface;
use App\Interfaces\Master\AdminMstInterface;
use App\Interfaces\Master\AdminRoleMstInterface;
use App\Interfaces\Master\ApiMstInterface;
use App\Interfaces\Master\ApiRoleMstInterface;
use App\Interfaces\Master\DepartmentMstInterface;
use App\Interfaces\Master\DepartmentManagementMstInterface;
use App\Interfaces\Master\FeatureMstInterface;
use App\Interfaces\Master\PolicyDepartmentMstInterface;
use App\Interfaces\Master\RoleMstInterface;
use App\Repositories\Management\CategoryMgmtRepository;
use App\Repositories\Master\AdminDepartmentMstRepository;
use App\Repositories\Master\AdminMstRepository;
use App\Repositories\Master\AdminRoleMstRepository;
use App\Repositories\Master\ApiMstRepository;
use App\Repositories\Master\ApiRoleMstRepository;
use App\Repositories\Master\DepartmentManagementMstRepository;
use App\Repositories\Master\DepartmentMstRepository;
use App\Repositories\Master\FeatureMstRepository;
use App\Repositories\Master\PolicyDepartmentMstRepository;
use App\Repositories\Master\RoleMstRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public $bindings = [
        // Master
        AdminDepartmentMstInterface::class => AdminDepartmentMstRepository::class,
        AdminMstInterface::class => AdminMstRepository::class,
        AdminRoleMstInterface::class => AdminRoleMstRepository::class,
        ApiMstInterface::class => ApiMstRepository::class,
        ApiRoleMstInterface::class => ApiRoleMstRepository::class,
        DepartmentManagementMstInterface::class => DepartmentManagementMstRepository::class,
        DepartmentMstInterface::class => DepartmentMstRepository::class,
        FeatureMstInterface::class => FeatureMstRepository::class,
        PolicyDepartmentMstInterface::class => PolicyDepartmentMstRepository::class,
        RoleMstInterface::class => RoleMstRepository::class,

        // Management
        CategoryMgmtInterface::class => CategoryMgmtRepository::class,
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

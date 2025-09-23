<?php

declare(strict_types=1);

namespace App\Providers;

use App\Interfaces\History\Management\BannerMgmtHistInterface;
use App\Interfaces\History\Management\CategoryMgmtHistInterface;
use App\Interfaces\History\Master\AdminMstHistInterface;
use App\Interfaces\History\Master\ApiMstHistInterface;
use App\Interfaces\History\Master\DepartmentMstHistInterface;
use App\Interfaces\History\Master\FeatureMstHistInterface;
use App\Interfaces\History\Master\LanguageMstHistInterface;
use App\Interfaces\History\Master\OriginalTranslatorMstHistInterface;
use App\Interfaces\History\Master\PolicyDepartmentMstHistInterface;
use App\Interfaces\Management\BannerMgmtInterface;
use App\Interfaces\Management\CategoryMgmtInterface;
use App\Interfaces\Management\CategorySkillMgmtInterface;
use App\Interfaces\Management\ProductMgmtInterface;
use App\Interfaces\Master\AdminDepartmentMstInterface;
use App\Interfaces\Master\AdminMstInterface;
use App\Interfaces\Master\AdminRoleMstInterface;
use App\Interfaces\Master\ApiMstInterface;
use App\Interfaces\Master\ApiRoleMstInterface;
use App\Interfaces\Master\DepartmentManagementMstInterface;
use App\Interfaces\Master\DepartmentMstInterface;
use App\Interfaces\Master\FeatureMstInterface;
use App\Interfaces\Master\LanguageMstInterface;
use App\Interfaces\Master\OriginalTranslatorMstInterface;
use App\Interfaces\Master\PolicyDepartmentMstInterface;
use App\Interfaces\Master\RoleMstInterface;
use App\Repositories\History\Management\BannerMgmtHistRepository;
use App\Repositories\History\Management\CategoryMgmtHistRepository;
use App\Repositories\History\Master\AdminMstHistRepository;
use App\Repositories\History\Master\ApiMstHistRepository;
use App\Repositories\History\Master\DepartmentMstHistRepository;
use App\Repositories\History\Master\FeatureMstHistRepository;
use App\Repositories\History\Master\LanguageMstHistRepository;
use App\Repositories\History\Master\OriginalTranslatorMstHistRepository;
use App\Repositories\History\Master\PolicyDepartmentMstHistRepository;
use App\Repositories\Management\BannerMgmtRepository;
use App\Repositories\Management\CategoryMgmtRepository;
use App\Repositories\Management\CategorySkillMgmtRepository;
use App\Repositories\Management\ProductMgmtRepository;
use App\Repositories\Master\AdminDepartmentMstRepository;
use App\Repositories\Master\AdminMstRepository;
use App\Repositories\Master\AdminRoleMstRepository;
use App\Repositories\Master\ApiMstRepository;
use App\Repositories\Master\ApiRoleMstRepository;
use App\Repositories\Master\DepartmentManagementMstRepository;
use App\Repositories\Master\DepartmentMstRepository;
use App\Repositories\Master\FeatureMstRepository;
use App\Repositories\Master\LanguageMstRepository;
use App\Repositories\Master\OriginalTranslatorMstRepository;
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
        LanguageMstInterface::class => LanguageMstRepository::class,
        PolicyDepartmentMstInterface::class => PolicyDepartmentMstRepository::class,
        RoleMstInterface::class => RoleMstRepository::class,
        OriginalTranslatorMstInterface::class => OriginalTranslatorMstRepository::class,

        // Management
        CategoryMgmtInterface::class => CategoryMgmtRepository::class,
        BannerMgmtInterface::class => BannerMgmtRepository::class,
        CategorySkillMgmtInterface::class => CategorySkillMgmtRepository::class,
        ProductMgmtInterface::class => ProductMgmtRepository::class,

        // Master History
        AdminMstHistInterface::class => AdminMstHistRepository::class,
        ApiMstHistInterface::class => ApiMstHistRepository::class,
        DepartmentMstHistInterface::class => DepartmentMstHistRepository::class,
        FeatureMstHistInterface::class => FeatureMstHistRepository::class,
        LanguageMstHistInterface::class => LanguageMstHistRepository::class,
        OriginalTranslatorMstHistInterface::class => OriginalTranslatorMstHistRepository::class,
        PolicyDepartmentMstHistInterface::class => PolicyDepartmentMstHistRepository::class,

        // Management history
        BannerMgmtHistInterface::class => BannerMgmtHistRepository::class,
        CategoryMgmtHistInterface::class => CategoryMgmtHistRepository::class,
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

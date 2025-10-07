<?php

namespace App\Console\Commands;

use App\Enums\TypeOfMethod;
use App\Models\Master\AdminMst;
use App\Models\Master\AdminDepartmentMst;
use App\Models\Master\AdminRoleMst;
use App\Models\Master\ApiMst;
use App\Models\Master\ApiRoleMst;
use App\Models\Master\DepartmentMst;
use App\Models\Master\FeatureMst;
use App\Models\Master\RoleMst;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SyncApiPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-api-permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var string
     */
    protected string $prefixApi = 'api/admin/';

    /**
     * @var string
     */
    protected string $root = 'root';

    /**
     * @var string
     */
    protected string $adminGroup = 'admin';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        DB::transaction(function () {
            $adminAccount = AdminMst::firstOrCreate(
                ['email' => 'root@gmail.com'],
                [
                    'user_name' => $this->root,
                    'password' => bcrypt('12345678'),
                    'first_name' => 'first',
                    'last_name' => 'last',
                    'status' => 1,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $rootRole = RoleMst::firstOrCreate(
                ['name' => $this->root],
                [
                    'permission' => $this->root,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Use updateOrInsert instead of firstOrCreate to handle composite primary keys
            AdminRoleMst::updateOrInsert(
                [
                    'admin_mst_id' => $adminAccount->id,
                    'role_mst_id' => $rootRole->id
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // URI rule: {version api}/{group}/{feature-name}/{action}/{id}
            foreach (Route::getRoutes() as $route) {

                $this->info('***************************');
                $this->info('Uri: ' . $route->uri());

                if (Str::startsWith($route->uri(), $this->prefixApi)) {
                    $this->info('In progress ...');
                    $uriParts = explode('/', $route->uri());
                    if (count($uriParts) >= 4) {
                        $featureSlug = $uriParts[2];
                        $action = $uriParts[3];
                        $featureName = str_replace('-', ' ', $featureSlug);

                        // Update feature
                        $feature = FeatureMst::firstOrCreate(
                            ['name' => $featureName],
                            [
                                'group_name' => $this->adminGroup,
                                'description' => '',
                                'status' => 1,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );

                        // Update API
                        $apiPath = $route->uri();

                        // Based on the error message, both name and path might be limited to 50 chars in the actual database
                        // Even though migration shows path should be 100 chars, let's restrict both to 45 to be very safe
                        $apiName = substr($action . ' ' . $featureName, 0, 45);
                        $apiPathTrimmed = substr($apiPath, 0, 45);

                        $this->info('API Name: ' . $apiName);
                        $this->info('API Path: ' . $apiPathTrimmed);
                        $this->info('route : ' . $route->methods()[0]);

                        $api = ApiMst::firstOrCreate(
                            [
                                'path' => $apiPathTrimmed
                            ],
                            [
                                'type' => TypeOfMethod::fromName($route->methods()[0]),
                                'name' => $apiName,
                                'path' => $apiPathTrimmed,
                                'is_active' => true,
                                'feature_mst_id' => $feature->id,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );

                        // Link to admin role - Use updateOrInsert for composite primary key
                        ApiRoleMst::updateOrInsert(
                            [
                                'api_mst_id' => $api->id,
                                'role_mst_id' => $rootRole->id,
                            ],
                            [
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }

                    $this->info('Done.');
                } else {
                    $this->info('Continue');
                }
            }

            $department = DepartmentMst::firstOrCreate(
                ['name' => $this->root],
                [
                    'code' => '1',
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            // Use updateOrInsert instead of firstOrCreate to handle composite primary keys
            AdminDepartmentMst::updateOrInsert(
                [
                    'admin_mst_id' => $adminAccount->id,
                    'department_mst_id' => $department->id,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $this->info('Permissions updated successfully!');
        });
    }
}

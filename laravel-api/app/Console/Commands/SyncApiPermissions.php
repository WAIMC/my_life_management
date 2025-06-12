<?php

namespace App\Console\Commands;

use App\Models\Master\Admin;
use App\Models\Master\AdminDepartment;
use App\Models\Master\AdminRole;
use App\Models\Master\Api;
use App\Models\Master\ApiRole;
use App\Models\Master\Department;
use App\Models\Master\Feature;
use App\Models\Master\Role;
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
      $adminAccount = Admin::firstOrCreate(
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

      $rootRole = Role::firstOrCreate(
        ['name' => $this->root],
        [
          'permission' => $this->root,
          'is_active' => true,
          'created_at' => now(),
          'updated_at' => now(),
        ]
      );

      AdminRole::firstOrCreate(
        [
          'admin_id' => $adminAccount->id,
          'role_id' => $rootRole->id
        ],
        [
          'admin_id' => $adminAccount->id,
          'role_id' => $rootRole->id,
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
            $feature = Feature::firstOrCreate(
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
            $api = Api::firstOrCreate(
              [
                'path' => $apiPath
              ],
              [
                'type' => array_search($route->methods()[0], Api::TYPE_OF_METHOD),
                'name' => $action . ' ' . $featureName,
                'path' => $apiPath,
                'is_active' => true,
                'feature_id' => $feature->id,
                'created_at' => now(),
                'updated_at' => now(),
              ]
            );

            // Link to admin role
            ApiRole::firstOrCreate(
              [
                'api_id' => $api->id,
                'role_id' => $rootRole->id,
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

      $department = Department::firstOrCreate(
        ['name' => $this->root],
        [
          'code' => '1',
          'status' => 1,
          'created_at' => now(),
          'updated_at' => now(),
        ]
      );

      AdminDepartment::firstOrCreate(
        [
          'admin_id' => $adminAccount->id,
          'department_id' => $department->id,
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

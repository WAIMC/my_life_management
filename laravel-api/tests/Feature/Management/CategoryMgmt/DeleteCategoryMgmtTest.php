<?php

namespace Tests\Feature\Management\CategoryMgmt;

use App\Constants\CommonVal;
use App\Models\Management\CategoryMgmt;
use App\Models\Master\AdminMst;
use App\Models\Master\ApiMst;
use App\Models\Master\FeatureMst;
use App\Models\Master\RoleMst;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Enums\ActionType;

class DeleteCategoryMgmtTest extends TestCase
{
  use RefreshDatabase;

  private string $baseUrl = 'api/admin/category-mgmt/delete';

  /**
   * Helper to get authenticated cookies with 'root' role
   */
  private function getAuthCookies(AdminMst $admin): array
  {
    $rootRole = RoleMst::where('name', 'root')->first();
    if (!$rootRole) {
      $rootRole = RoleMst::create(['name' => 'root', 'permission' => '{}', 'is_active' => 1, 'is_delete' => 0]);
    }

    $this->grantAccessTo($rootRole, 'DELETE', 'api/admin/category-mgmt/delete/{id}');

    if (!DB::table('admin_role_mst')
      ->where('admin_mst_id', $admin->id)
      ->where('role_mst_id', $rootRole->id)
      ->exists()) {
      DB::table('admin_role_mst')->insert([
        'admin_mst_id' => $admin->id,
        'role_mst_id' => $rootRole->id,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    $response = $this->postJson('/api/admin/credential/login', [
      'user_name' => $admin->user_name,
      'password' => 'password',
    ]);

    $cookies = [];
    foreach ($response->headers->getCookies() as $cookie) {
      $cookies[$cookie->getName()] = $cookie->getValue();
    }
    return $cookies;
  }

  private function grantAccessTo(RoleMst $role, string $method, string $path)
  {
    $typeMap = ['GET' => 0, 'POST' => 1, 'PUT' => 2, 'PATCH' => 3, 'DELETE' => 4];
    $type = $typeMap[strtoupper($method)] ?? 0;

    $feature = FeatureMst::firstOrCreate([
      'name' => 'System Features',
      'group_name' => 'System',
      'description' => 'Auto generated',
      'status' => 1,
      'is_delete' => 0
    ]);

    $api = ApiMst::firstOrCreate(
      ['path' => $path, 'type' => $type],
      [
        'name' => substr("Endp $method $path", 0, 50),
        'is_active' => 1,
        'feature_mst_id' => $feature->id,
        'is_delete' => 0
      ]
    );

    DB::table('api_role_mst')->insertOrIgnore([
      'api_mst_id' => $api->id,
      'role_mst_id' => $role->id,
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }

  /**
   * Test [CAT_MEM_DEL_001] Unauthenticated
   */
  public function test_CAT_MEM_DEL_001_unauthenticated()
  {
    $response = $this->deleteJson($this->baseUrl . '/1', []);
    $response->assertStatus(401);
  }

  /**
   * Test [CAT_MEM_DEL_002] Invalid Structure
   */
  public function test_CAT_MEM_DEL_002_invalid_structure()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    // Payload missing 'ids'
    $response = $this->call('DELETE', $this->baseUrl . '/1', [], $cookies);
    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $this->assertArrayHasKey('ids', $response->json('error.messages'));
  }

  /**
   * Test [CAT_MEM_DEL_003] Success
   */
  public function test_CAT_MEM_DEL_003_success()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $category1 = CategoryMgmt::factory()->create(['name' => 'Cat 1']);
    $category2 = CategoryMgmt::factory()->create(['name' => 'Cat 2']);

    $payload = [
      'ids' => [$category1->id, $category2->id]
    ];

    $response = $this->call('DELETE', $this->baseUrl . '/' . $category1->id, $payload, $cookies);

    if ($response->status() !== 200) {
      $response->dump();
    }
    $response->assertStatus(200);

    // Verify Soft Delete
    $this->assertDatabaseHas('category_mgmt', [
      'id' => $category1->id,
      'is_delete' => 1,
    ]);
    $this->assertDatabaseHas('category_mgmt', [
      'id' => $category2->id,
      'is_delete' => 1,
    ]);

    // Verify History
    $this->assertDatabaseHas('category_mgmt_hist', [
      'category_mgmt_id' => $category1->id,
      'action' => ActionType::DELETE->value,
    ]);
    $this->assertDatabaseHas('category_mgmt_hist', [
      'category_mgmt_id' => $category2->id,
      'action' => ActionType::DELETE->value,
    ]);
  }
}

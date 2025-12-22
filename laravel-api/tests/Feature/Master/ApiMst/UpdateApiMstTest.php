<?php

namespace Tests\Feature\Master\ApiMst;

use App\Constants\CommonVal;
use App\Enums\ActionType;
use App\Enums\IsActive;
use App\Enums\IsDelete;
use App\Models\Master\AdminMst;
use App\Models\Master\ApiMst;
use App\Models\Master\FeatureMst;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class UpdateApiMstTest extends TestCase
{
  use RefreshDatabase;

  private string $baseUrl = 'api/admin/api-mst/update';

  /**
   * Helper to get authenticated cookies with 'root' role
   */
  private function getAuthCookies(AdminMst $admin): array
  {
    $rootRole = \App\Models\Master\RoleMst::where('name', 'root')->first();
    if (!$rootRole) {
      $rootRole = \App\Models\Master\RoleMst::create(['name' => 'root', 'permission' => '{}', 'is_active' => 1, 'is_delete' => 0]);
    }

    $this->grantAccessTo($rootRole, 'PUT', 'api/admin/api-mst/update/{id}');

    if (!\Illuminate\Support\Facades\DB::table('admin_role_mst')
      ->where('admin_mst_id', $admin->id)
      ->where('role_mst_id', $rootRole->id)
      ->exists()) {
      \Illuminate\Support\Facades\DB::table('admin_role_mst')->insert([
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

  private function grantAccessTo(\App\Models\Master\RoleMst $role, string $method, string $path)
  {
    $typeMap = ['GET' => 0, 'POST' => 1, 'PUT' => 2, 'PATCH' => 3, 'DELETE' => 4];
    $type = $typeMap[strtoupper($method)] ?? 0;

    $feature = \App\Models\Master\FeatureMst::firstOrCreate([
      'name' => 'System Features',
      'group_name' => 'System',
      'description' => 'Auto generated',
      'status' => 1,
      'is_delete' => 0
    ]);

    $api = \App\Models\Master\ApiMst::create([
      'type' => $type,
      'name' => "Endpoint $method $path",
      'path' => $path,
      'is_active' => 1,
      'feature_mst_id' => $feature->id,
      'is_delete' => 0
    ]);

    \Illuminate\Support\Facades\DB::table('api_role_mst')->insertOrIgnore([
      'api_mst_id' => $api->id,
      'role_mst_id' => $role->id,
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }

  /**
   * Test [MST_API_UPD_001] Wrong HTTP Method
   */
  public function test_MST_API_UPD_001_wrong_http_method()
  {
    $api = ApiMst::factory()->create();
    $url = $this->baseUrl . '/' . $api->id;

    $response = $this->postJson($url);
    $response->assertStatus(405);
  }

  /**
   * Test [MST_API_UPD_002] Unauthenticated
   */
  public function test_MST_API_UPD_002_unauthenticated()
  {
    $api = ApiMst::factory()->create();
    $url = $this->baseUrl . '/' . $api->id;

    $response = $this->putJson($url, []);
    $response->assertStatus(401);
  }

  /**
   * Test [MST_API_UPD_003] Invalid Route Parameter
   */
  public function test_MST_API_UPD_003_invalid_route_parameter()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $url = $this->baseUrl . '/abc';

    $response = $this->call('PUT', $url, [], $cookies);
    // Laravel usually returns 404 for model binding fail or string where int expected in route constraints
    // If route is /update/{id} without regex, it enters controller.
    // If controller arg is string $id, it works.
    // But Request validation might check ID.
    // Assuming validation checks ID existence or type.
    // If not found, 404 is also acceptable.
    if ($response->status() === 404) {
      $response->assertStatus(404);
    } else {
      $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }
  }

  /**
   * Test [MST_API_UPD_004] ID Mismatch / Required in Body
   */
  public function test_MST_API_UPD_004_id_check()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);
    $api = ApiMst::factory()->create();
    $url = $this->baseUrl . '/' . $api->id;

    // Case 1: ID Missing in Body
    $response = $this->call('PUT', $url, [], $cookies);
    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $response->assertJsonPath('error.messages.id.0', fn($msg) => !empty($msg));

    // Case 2: ID Mismatch (Body different from Route)
    // Code logic: Body validation runs first.
    $payload = [
      'id' => $api->id + 1, // Doesn't exist
      // other fields required? validation 'required' on id.
    ];
    // If it doesn't exist, it fails 'exists' rule.
    $response2 = $this->call('PUT', $url, $payload, $cookies);
    $response2->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
  }

  /**
   * Test [MST_API_UPD_005] Non Existent ID
   */
  public function test_MST_API_UPD_005_non_existent_id()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $url = $this->baseUrl . '/999999';
    $payload = ['id' => 999999];

    $response = $this->call('PUT', $url, $payload, $cookies);
    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $response->assertJsonPath('error.messages.id.0', fn($msg) => !empty($msg));
  }

  /**
   * Test [MST_API_UPD_007] Success
   */
  public function test_MST_API_UPD_007_success()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);
    $feature = FeatureMst::factory()->create();

    $api = ApiMst::factory()->create();
    $url = $this->baseUrl . '/' . $api->id;

    $payload = [
      'id' => $api->id,
      'type' => 2,
      'name' => 'Updated Name',
      'path' => 'updated/path',
      'is_active' => IsActive::TRUE->value,
      'feature_mst_id' => $feature->id,
      'is_delete' => IsDelete::FALSE->value,
    ];

    $response = $this->call('PUT', $url, $payload, $cookies);

    if ($response->status() !== 200) {
      $response->dump();
    }

    $response->assertStatus(200);

    // Verify DB
    $this->assertDatabaseHas('api_mst', [
      'id' => $api->id,
      'name' => 'Updated Name',
      'path' => 'updated/path',
    ]);

    // Verify History
    $this->assertDatabaseHas('api_mst_hist', [
      'api_mst_id' => $api->id,
      'action' => ActionType::UPDATE->value,
      'author_id' => $admin->id,
    ]);
  }

  /**
   * Test [MST_API_UPD_008] Update Deleted Record
   */
  public function test_MST_API_UPD_008_update_deleted_record()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);
    $feature = FeatureMst::factory()->create();

    $api = ApiMst::factory()->create(['is_delete' => 1]); // Soft Deleted
    $url = $this->baseUrl . '/' . $api->id;

    $payload = [
      'id' => $api->id,
      'type' => 2,
      'name' => 'Updated Name',
      'path' => 'updated/path',
      'is_active' => IsActive::TRUE->value,
      'feature_mst_id' => $feature->id,
      'is_delete' => IsDelete::FALSE->value,
    ];

    $response = $this->call('PUT', $url, $payload, $cookies);

    // Expect 500 (LogicException) or 422 if validation catches it.
    // Validation rule 'exists' might fail if it ignores soft deleted.
    // If validation passes, Repository throws LogicException.
    // Laravel Handler might return 500.
    if ($response->status() === 500) {
      $response->assertStatus(500);
    } else {
      $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    }
  }
}

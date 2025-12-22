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

class StoreApiMstTest extends TestCase
{
  use RefreshDatabase;

  private string $storeUrl = 'api/admin/api-mst/store';

  /**
   * Helper to get authenticated cookies with 'root' role
   *
   * @param AdminMst $admin
   * @return array
   */
  private function getAuthCookies(AdminMst $admin): array
  {
    $rootRole = \App\Models\Master\RoleMst::where('name', 'root')->first();
    if (!$rootRole) {
      $rootRole = \App\Models\Master\RoleMst::create(['name' => 'root', 'permission' => '{}', 'is_active' => 1, 'is_delete' => 0]);
    }

    // Grant access to STORE endpoint
    $this->grantAccessTo($rootRole, 'POST', 'api/admin/api-mst/store');

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

  /**
   * Grant access to a specific route for a role
   */
  private function grantAccessTo(\App\Models\Master\RoleMst $role, string $method, string $path)
  {
    // Method mapping based on View: 0=GET, 1=POST, 2=PUT, 3=PATCH, 4=DELETE
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
   * Test [MST_API_STR_001] Wrong HTTP Method
   */
  public function test_MST_API_STR_001_wrong_http_method()
  {
    $response = $this->getJson($this->storeUrl);
    $response->assertStatus(405);

    $response = $this->putJson($this->storeUrl);
    $response->assertStatus(405);

    $response = $this->deleteJson($this->storeUrl);
    $response->assertStatus(405);
  }

  /**
   * Test [MST_API_STR_002] Unauthenticated
   */
  public function test_MST_API_STR_002_unauthenticated()
  {
    $response = $this->postJson($this->storeUrl, []);
    $response->assertStatus(401);
  }

  /**
   * Test [MST_API_STR_003] Required Fields Missing
   */
  public function test_MST_API_STR_003_required_fields_missing()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $response = $this->call('POST', $this->storeUrl, [], $cookies);

    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    // Required fields: type, name, path, is_active, feature_mst_id, is_delete
    $response->assertJsonPath('error.messages.type.0', fn($msg) => !empty($msg));
    $response->assertJsonPath('error.messages.name.0', fn($msg) => !empty($msg));
    $response->assertJsonPath('error.messages.path.0', fn($msg) => !empty($msg));
    $response->assertJsonPath('error.messages.is_active.0', fn($msg) => !empty($msg));
    $response->assertJsonPath('error.messages.feature_mst_id.0', fn($msg) => !empty($msg));
    $response->assertJsonPath('error.messages.is_delete.0', fn($msg) => !empty($msg));
  }

  /**
   * Test [MST_API_STR_004] Max Length Checks
   */
  public function test_MST_API_STR_004_max_length_fields()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $payload = [
      'name' => str_repeat('a', 51), // Max 50
      'path' => str_repeat('a', 101), // Max 100
    ];

    $response = $this->call('POST', $this->storeUrl, $payload, $cookies);

    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $response->assertJsonPath('error.messages.name.0', fn($msg) => !empty($msg));
    $response->assertJsonPath('error.messages.path.0', fn($msg) => !empty($msg));
  }

  /**
   * Test [MST_API_STR_006] Invalid Enum Fields
   */
  public function test_MST_API_STR_006_invalid_enum_fields()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $payload = [
      'is_active' => 99,
      'is_delete' => 99,
    ];

    $response = $this->call('POST', $this->storeUrl, $payload, $cookies);

    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $response->assertJsonPath('error.messages.is_active.0', fn($msg) => !empty($msg));
    $response->assertJsonPath('error.messages.is_delete.0', fn($msg) => !empty($msg));
  }

  /**
   * Test [MST_API_STR_007] Feature Mst Id Validation
   */
  public function test_MST_API_STR_007_feature_mst_id_validation()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    // Case 1: Non-existent ID
    $response = $this->call('POST', $this->storeUrl, ['feature_mst_id' => 999999], $cookies);
    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $response->assertJsonPath('error.messages.feature_mst_id.0', fn($msg) => !empty($msg));

    // Case 2: Invalid Type (String)
    $response2 = $this->call('POST', $this->storeUrl, ['feature_mst_id' => 'abc'], $cookies);
    $response2->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $response2->assertJsonPath('error.messages.feature_mst_id.0', fn($msg) => !empty($msg));
  }

  /**
   * Test [MST_API_STR_010] Success
   */
  public function test_MST_API_STR_010_success()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);
    $feature = FeatureMst::factory()->create();

    $payload = [
      'type' => 1,
      'name' => 'New Api Endpoint',
      'path' => 'api/test/new',
      'is_active' => IsActive::TRUE->value,
      'feature_mst_id' => $feature->id,
      'is_delete' => IsDelete::FALSE->value,
    ];

    $response = $this->call('POST', $this->storeUrl, $payload, $cookies);

    if ($response->status() !== 200) {
      $response->dump();
    }

    $response->assertStatus(200);

    // Verify DB
    $this->assertDatabaseHas('api_mst', [
      'name' => 'New Api Endpoint',
      'path' => 'api/test/new',
      'feature_mst_id' => $feature->id,
    ]);

    // Verify History
    $api = ApiMst::where('path', 'api/test/new')->first();
    $this->assertNotNull($api);

    $this->assertDatabaseHas('api_mst_hist', [
      'api_mst_id' => $api->id,
      'action' => ActionType::CREATE->value,
      'author_id' => $admin->id,
    ]);
  }
}

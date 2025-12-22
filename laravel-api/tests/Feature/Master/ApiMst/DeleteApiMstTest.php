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

class DeleteApiMstTest extends TestCase
{
  use RefreshDatabase;

  private string $baseUrl = 'api/admin/api-mst/delete';

  /**
   * Helper to get authenticated cookies with 'root' role
   */
  private function getAuthCookies(AdminMst $admin): array
  {
    $rootRole = \App\Models\Master\RoleMst::where('name', 'root')->first();
    if (!$rootRole) {
      $rootRole = \App\Models\Master\RoleMst::create(['name' => 'root', 'permission' => '{}', 'is_active' => 1, 'is_delete' => 0]);
    }

    $this->grantAccessTo($rootRole, 'DELETE', 'api/admin/api-mst/delete/{id}');

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
   * Test [MST_API_DEL_001] Wrong HTTP Method
   */
  public function test_MST_API_DEL_001_wrong_http_method()
  {
    $api = ApiMst::factory()->create();
    $url = $this->baseUrl . '/' . $api->id;

    $response = $this->getJson($url);
    $response->assertStatus(405);
  }

  /**
   * Test [MST_API_DEL_002] Unauthenticated
   */
  public function test_MST_API_DEL_002_unauthenticated()
  {
    $api = ApiMst::factory()->create();
    $url = $this->baseUrl . '/' . $api->id;

    $response = $this->deleteJson($url, []);
    $response->assertStatus(401);
  }

  /**
   * Test [MST_API_DEL_004] Missing ids
   */
  public function test_MST_API_DEL_004_missing_ids()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);
    $api = ApiMst::factory()->create();
    $url = $this->baseUrl . '/' . $api->id;

    $response = $this->call('DELETE', $url, [], $cookies);
    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $response->assertJsonPath('error.messages.ids.0', fn($msg) => !empty($msg));
  }

  /**
   * Test [MST_API_DEL_005] ids not array
   */
  public function test_MST_API_DEL_005_ids_not_array()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);
    $api = ApiMst::factory()->create();
    $url = $this->baseUrl . '/' . $api->id;

    $payload = ['ids' => 'abc'];

    $response = $this->call('DELETE', $url, $payload, $cookies);
    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $response->assertJsonPath('error.messages.ids.0', fn($msg) => !empty($msg));
  }

  /**
   * Test [MST_API_DEL_006] ids non-existent
   */
  public function test_MST_API_DEL_006_ids_non_existent()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);
    $api = ApiMst::factory()->create();
    $url = $this->baseUrl . '/' . $api->id;

    $payload = ['ids' => [999999]];

    $response = $this->call('DELETE', $url, $payload, $cookies);

    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);

    // Check if error message for key 'ids.0' exists
    // Access array directly
    $errors = $response->json('error.messages');
    $this->assertArrayHasKey('ids.0', $errors);
  }

  /**
   * Test [MST_API_DEL_008] Success
   */
  public function test_MST_API_DEL_008_success()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $api1 = ApiMst::factory()->create();
    $api2 = ApiMst::factory()->create();

    $url = $this->baseUrl . '/' . $api1->id;

    $payload = [
      'ids' => [$api1->id, $api2->id],
    ];

    $response = $this->call('DELETE', $url, $payload, $cookies);

    if ($response->status() !== 200) {
      $response->dump();
    }

    $response->assertStatus(200);

    // Verify DB (Soft Delete)
    $this->assertDatabaseHas('api_mst', [
      'id' => $api1->id,
      'is_delete' => IsDelete::TRUE->value,
    ]);
    $this->assertDatabaseHas('api_mst', [
      'id' => $api2->id,
      'is_delete' => IsDelete::TRUE->value,
    ]);

    // Verify History
    $this->assertDatabaseHas('api_mst_hist', [
      'api_mst_id' => $api1->id,
      'action' => ActionType::DELETE->value,
      'author_id' => $admin->id,
    ]);
    $this->assertDatabaseHas('api_mst_hist', [
      'api_mst_id' => $api2->id,
      'action' => ActionType::DELETE->value,
      'author_id' => $admin->id,
    ]);
  }
}

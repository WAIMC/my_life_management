<?php

namespace Tests\Feature\Management\BannerMgmt;

use App\Constants\CommonVal;
use App\Models\Management\BannerMgmt;
use App\Models\Master\AdminMst;
use App\Models\Master\ApiMst;
use App\Models\Master\FeatureMst;
use App\Models\Master\RoleMst;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DeleteBannerMgmtTest extends TestCase
{
  use RefreshDatabase;

  private string $baseUrl = 'api/admin/banner-mgmt/delete';

  /**
   * Helper to get authenticated cookies with 'root' role
   */
  private function getAuthCookies(AdminMst $admin): array
  {
    $rootRole = RoleMst::where('name', 'root')->first();
    if (!$rootRole) {
      $rootRole = RoleMst::create(['name' => 'root', 'permission' => '{}', 'is_active' => 1, 'is_delete' => 0]);
    }

    // Grant access to DELETE endpoint
    $this->grantAccessTo($rootRole, 'DELETE', 'api/admin/banner-mgmt/delete/{id}');

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
        'name' => "Endpoint $method $path",
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
   * Test [MST_BNR_DEL_001] Unauthenticated
   */
  public function test_MST_BNR_DEL_001_unauthenticated()
  {
    $response = $this->deleteJson($this->baseUrl . '/1', []);
    $response->assertStatus(401);
  }

  /**
   * Test [MST_BNR_DEL_002] Invalid - Not Array or Missing
   */
  public function test_MST_BNR_DEL_002_invalid_structure()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    // Payload missing ids
    $response = $this->call('DELETE', $this->baseUrl . '/1', [], $cookies);
    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $this->assertArrayHasKey('ids', $response->json('error.messages'));
  }

  /**
   * Test [MST_BNR_DEL_004] Success
   */
  public function test_MST_BNR_DEL_004_success()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);
    $banners = BannerMgmt::factory()->count(2)->create();
    $ids = $banners->pluck('id')->toArray();

    // Using DELETE generally accepts query params or body.
    // For array, it is usually body.
    // Route is `delete/{id}` but service accepts batch `ids` array.
    // If route has {id}, typically it handles one ID, but documentation/tests often imply support for batch via body or similar.
    // Let's check logic: Controller calls `bannerMgmt->delete($request->all())`.
    // Service `delete` checks `ids` array.
    // So I can pass `ids` in body. The `{id}` in route might be ignored or used if `ids` missing?
    // Service: if `!isset(ids)` -> `executeDelete([ids])???` No, `executeDelete($payload['ids'] ?? [])` -> empty.
    // Wait! Service says:
    // if (!isset($payload['ids'])) $this->bannerMgmt->executeDelete($payload['ids'] ?? []);
    // null ?? [] = []. So it deletes nothing if `ids` not set.
    // This implies the standard usage is sending `ids` in body.
    // The URL param `{id}` might be vestigial or for single delete if Request handled it, but Request validates `ids` array.
    // `DeleteBannerMgmtRequest.php` rules: `ids => required|array`.
    // So I MUST pass `ids`. The `{id}` in URL is just to satisfy Route parameter likely.

    $payload = ['ids' => $ids];
    $response = $this->call('DELETE', $this->baseUrl . '/' . $ids[0], $payload, $cookies);
    $response->assertStatus(200);

    foreach ($banners as $banner) {
      $this->assertDatabaseHas('banner_mgmt', [
        'id' => $banner->id,
        'is_delete' => 1,
      ]);

      $hist = DB::table('banner_mgmt_hist')->get();
      // dump($hist); // Debug

      $this->assertDatabaseHas('banner_mgmt_hist', [
        'banner_mgmt_id' => $banner->id,
        'action' => \App\Enums\ActionType::DELETE->value,
        'author_id' => $admin->id,
      ]);
    }
  }
}

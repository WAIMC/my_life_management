<?php

namespace Tests\Feature\Master\TokenMst;

use App\Models\Master\AdminMst;
use App\Models\Master\ApiMst;
use App\Models\Master\FeatureMst;
use App\Models\Master\RoleMst;
use App\Models\Master\TokenMst;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class DeleteTokenMstTest extends TestCase
{
  use RefreshDatabase;

  private string $baseUrl = 'api/admin/token-mst/delete';

  protected function setUp(): void
  {
    parent::setUp();
    Redis::flushdb();
  }

  private function getAuthCookies(AdminMst $admin): array
  {
    $rootRole = RoleMst::where('name', 'root')->first();
    if (!$rootRole) {
      $rootRole = RoleMst::create(['name' => 'root', 'permission' => '{}', 'is_active' => 1, 'is_delete' => 0]);
    }

    $this->grantAccessTo($rootRole, 'DELETE', $this->baseUrl . '/{id}');

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

  private function createToken(): TokenMst
  {
    return TokenMst::create([
      'account_id' => 1,
      'device_name' => 'Device ' . uniqid(),
      'ip_address' => '127.0.0.1',
      'token_hash' => 'hash123',
    ]);
  }

  // ========== ROUTE/VALIDATION TESTS ==========

  public function test_TOK_DEL_S001_delete_single()
  {
    $admin = AdminMst::factory()->create();
    $token = $this->createToken();
    $cookies = $this->getAuthCookies($admin);

    $payload = ['ids' => [$token->id]];

    $response = $this->call('DELETE', $this->baseUrl . '/' . $token->id, $payload, $cookies);
    $response->assertStatus(200);

    $this->assertDatabaseMissing('token_mst', ['id' => $token->id]);
  }
}

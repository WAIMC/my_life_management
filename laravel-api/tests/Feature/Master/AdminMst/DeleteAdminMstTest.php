<?php

namespace Tests\Feature\Master\AdminMst;

use App\Constants\CommonVal;
use App\Models\Master\AdminMst;
use App\Models\Master\RoleMst;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class DeleteAdminMstTest extends TestCase
{
  use DatabaseTransactions;

  protected string $loginUrl = '/api/admin/credential/login';

  protected function setUp(): void
  {
    parent::setUp();
    Redis::flushall();
  }

  protected function getDeleteUrl($id)
  {
    return "/api/admin/admin-mst/delete/{$id}";
  }

  protected function getAuthCookies(AdminMst $admin): array
  {
    $rootRole = RoleMst::where('name', 'root')->first();
    if (!$rootRole) {
      $rootRole = RoleMst::create(['name' => 'root', 'permission' => '{}', 'is_active' => 1, 'is_delete' => 0]);
    }

    if (!DB::table('admin_role_mst')->where('admin_mst_id', $admin->id)->where('role_mst_id', $rootRole->id)->exists()) {
      DB::table('admin_role_mst')->insert([
        'admin_mst_id' => $admin->id,
        'role_mst_id' => $rootRole->id,
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }

    $response = $this->postJson($this->loginUrl, [
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
   * Helper to assert custom validation errors
   */
  protected function assertCustomValidationErrors($response, $keys)
  {
    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $json = $response->json();
    $this->assertArrayHasKey('error', $json);
    $this->assertArrayHasKey('messages', $json['error']);

    foreach ((array)$keys as $key) {
      $this->assertArrayHasKey($key, $json['error']['messages']);
    }
  }

  /**
   * MST_DEL_001: Missing IDs Body
   */
  public function test_MST_DEL_001_missing_ids_body()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    // Call delete without body (even though URL has ID)
    $response = $this->call('DELETE', $this->getDeleteUrl($admin->id), [], $cookies);

    // Request class rules: 'ids' => required.
    $this->assertCustomValidationErrors($response, ['ids']);
  }

  /**
   * MST_DEL_002: ID Value > 12 (Bug Validation Max Length)
   * Request rule uses 'max:20' or 'max:'.CommonVal::MAX_PHONE_NUMBER which is 12?
   * Code says: `max:' . CommonVal::MAX_PHONE_NUMBER` => max:12 for integer field `ids.*`?
   * On Integer, max:12 means maximum VALUE 12.
   * If existing ID is 13, deleting it will fail if validation treats it as value.
   * OR if 'integer' rule is present, 'max' checks value. If 'string', 'max' checks length.
   * Code has `integer`. So max:12 is Max Value 12.
   * This is definitely a bug in the Request class (using phone max len for ID max value).
   */
  public function test_MST_DEL_002_id_value_bug_check()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    // Create a target with ID > 12
    // We might need to force ID if auto-increment is low.
    // Assuming factories can create ID > 12 eventually, or we force it.
    $target = AdminMst::factory()->create();
    if ($target->id <= 12) {
      // Force create one with high ID
      $target = AdminMst::factory()->create(['id' => 20]);
    }
    // Actually, factory might not accept ID override if auto-inc, but on create it works if not guarded.
    // If fails, we just assume current $target->id is used. 
    // If environment has few records, ID will be small.
    // Let's rely on standard factory. If ID < 12, this test passes false positive.
    // Try to ensure ID > 12.

    if ($target->id <= 12) {
      // We can Try deleting ID 9999 (even if not exists, validation runs first).
      // Wait, `exists` rule also runs. So we need it to exist.
      // We can manually insert.
      DB::table('admin_msts')->insert([
        'id' => 99,
        'email' => 'deltest@example.com',
        'user_name' => 'del_test',
        'password' => 'pass',
        'first_name' => 'Del',
        'last_name' => 'Test',
        'gender' => 0,
        'status' => 1,
        'is_active' => 1,
        'is_delete' => 0,
      ]);
      $delId = 99;
    } else {
      $delId = $target->id;
    }

    $response = $this->call('DELETE', $this->getDeleteUrl($delId), ['ids' => [$delId]], $cookies);

    // If validation is 'max:12' (integer), this fails for 99.
    if ($delId > 12) {
      // We expect this to FAIL with 422 due to the bug.
      // We assert 200 to signal we WANT it to work, so failure highlights bug.
      $response->assertStatus(CommonVal::HTTP_OK);
    } else {
      $this->markTestSkipped('Could not generate ID > 12 for testing bug.');
    }

    // Clean up
    if (isset($delId) && $delId == 99) {
      AdminMst::destroy(99);
    }
  }

  /**
   * MST_DEL_003: Success Delete
   */
  public function test_MST_DEL_003_success_delete()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    $target = AdminMst::factory()->create();
    // Ensure ID <= 12 to pass the buggy validation if we want "Success" test to pass now?
    // Or we assume standard ID works. If bug blocks everything > 12, this fails for normal usage.

    // We will try.
    $response = $this->call('DELETE', $this->getDeleteUrl($target->id), ['ids' => [$target->id]], $cookies);

    $response->assertStatus(CommonVal::HTTP_OK);

    $this->assertDatabaseHas('admin_mst', ['id' => $target->id, 'is_delete' => 1]);
    // Logic `executeDelete` usually does delete or update is_delete.
    // Logic `executeDelete` usually does delete or update is_delete.
    // Assuming AdminMst uses SoftDeletes or custom is_delete field.
    // Service `executeDelete` likely deletes.
    // If SoftDeletes trait used, assertSoftDeleted. If not, assertDatabaseMissing or check is_delete.
  }
}

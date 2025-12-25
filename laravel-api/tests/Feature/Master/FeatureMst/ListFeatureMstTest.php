<?php

namespace Tests\Feature\Master\FeatureMst;

use App\Constants\CommonVal;
use App\Enums\IsDelete;
use App\Enums\StatusEnum;
use App\Models\Master\AdminMst;
use App\Models\Master\FeatureMst;
use App\Models\Master\RoleMst;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class ListFeatureMstTest extends TestCase
{
  use DatabaseTransactions;

  protected string $listUrl = '/api/admin/feature-mst/list';
  protected string $loginUrl = '/api/admin/credential/login';

  protected function setUp(): void
  {
    parent::setUp();
    Redis::flushall();
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
   * FTR_LST_001: Unauthenticated
   */
  public function test_FTR_LST_001_unauthenticated()
  {
    $response = $this->getJson($this->listUrl);
    $response->assertStatus(CommonVal::HTTP_UNAUTHORIZED);
  }

  /**
   * FTR_LST_002: Invalid Date Format
   */
  public function test_FTR_LST_002_invalid_date_format()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    $response = $this->call('GET', $this->listUrl, ['from_date' => '2020-01-01'], $cookies);

    $this->assertCustomValidationErrors($response, ['from_date']);
  }

  /**
   * FTR_LST_003: To Date Before From Date
   */
  public function test_FTR_LST_003_to_date_before_from_date()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    $params = [
      'from_date' => '02/01/2020',
      'to_date' => '01/01/2020',
    ];

    $response = $this->call('GET', $this->listUrl, $params, $cookies);

    $this->assertCustomValidationErrors($response, ['to_date']);
  }

  /**
   * FTR_LST_004: Filter By Name
   */
  public function test_FTR_LST_004_filter_by_name()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    $target = FeatureMst::factory()->create(['name' => 'target_feature']);
    FeatureMst::factory()->create(['name' => 'other_feature']);

    $response = $this->call('GET', $this->listUrl, ['name' => 'target'], $cookies);

    $response->assertStatus(CommonVal::HTTP_OK);
    $items = $response->json('data.data');

    $this->assertTrue(collect($items)->contains('id', $target->id));
    $this->assertFalse(collect($items)->contains('name', 'other_feature'));
  }

  /**
   * FTR_LST_005: Filter By Status (Invalid)
   */
  public function test_FTR_LST_005_filter_by_status_invalid()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    $response = $this->call('GET', $this->listUrl, ['status' => 100], $cookies);

    $this->assertCustomValidationErrors($response, ['status']);
  }

  /**
   * FTR_LST_006: Valid Status Filter
   */
  public function test_FTR_LST_006_valid_status_filter()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    $active = FeatureMst::factory()->create(['status' => StatusEnum::ACTIVE->value]);
    $inactive = FeatureMst::factory()->create(['status' => StatusEnum::INACTIVE->value]);

    $response = $this->call('GET', $this->listUrl, ['status' => StatusEnum::ACTIVE->value], $cookies);

    $response->assertStatus(CommonVal::HTTP_OK);
    $items = $response->json('data.data');

    $this->assertTrue(collect($items)->contains('id', $active->id));
    $this->assertFalse(collect($items)->contains('id', $inactive->id));
  }

  /**
   * FTR_LST_007: Success No Filter
   */
  public function test_FTR_LST_007_success_no_filter()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    $response = $this->call('GET', $this->listUrl, [], $cookies);

    $response->assertStatus(CommonVal::HTTP_OK);
    $response->assertJsonStructure(['data']);
  }
}

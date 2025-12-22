<?php

namespace Tests\Feature\Management\CategorySkillMgmt;

use App\Constants\CommonVal;
use App\Models\Management\CategoryMgmt;
use App\Models\Management\CategorySkillMgmt;
use App\Models\Management\SkillMgmt;
use App\Models\Master\AdminMst;
use App\Models\Master\ApiMst;
use App\Models\Master\FeatureMst;
use App\Models\Master\RoleMst;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ListCategorySkillMgmtTest extends TestCase
{
  use RefreshDatabase;

  private string $baseUrl = 'api/admin/category-skill-mgmt/list';

  /**
   * Helper to get authenticated cookies with 'root' role
   */
  private function getAuthCookies(AdminMst $admin): array
  {
    $rootRole = RoleMst::where('name', 'root')->first();
    if (!$rootRole) {
      $rootRole = RoleMst::create(['name' => 'root', 'permission' => '{}', 'is_active' => 1, 'is_delete' => 0]);
    }

    $this->grantAccessTo($rootRole, 'GET', $this->baseUrl);

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
   * Test [CAT_SKL_LST_001] Unauthenticated
   */
  public function test_CAT_SKL_LST_001_unauthenticated()
  {
    $response = $this->getJson($this->baseUrl);
    $response->assertStatus(401);
  }

  /**
   * Test [CAT_SKL_LST_002] Success
   */
  public function test_CAT_SKL_LST_002_success()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $category = CategoryMgmt::factory()->create();
    $skill = SkillMgmt::factory()->create();

    $pivot = CategorySkillMgmt::create([
      'category_mgmt_id' => $category->id,
      'skill_mgmt_id' => $skill->id,
    ]);

    $response = $this->call('GET', $this->baseUrl, [], $cookies);

    if ($response->status() !== 200) {
      $response->dump();
    }
    $response->assertStatus(200);
    $data = $response->json('data.data');
    $this->assertCount(1, $data);
    $this->assertEquals($category->id, $data[0]['category']['id']);
    $this->assertEquals($skill->id, $data[0]['skill']['id']);
  }

  /**
   * Test [CAT_SKL_LST_003] Filter By Category
   */
  public function test_CAT_SKL_LST_003_filter_by_category()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $category1 = CategoryMgmt::factory()->create();
    $category2 = CategoryMgmt::factory()->create();
    $skill = SkillMgmt::factory()->create();

    CategorySkillMgmt::create(['category_mgmt_id' => $category1->id, 'skill_mgmt_id' => $skill->id]);
    CategorySkillMgmt::create(['category_mgmt_id' => $category2->id, 'skill_mgmt_id' => $skill->id]);

    $response = $this->call('GET', $this->baseUrl, ['category_mgmt_id' => $category1->id], $cookies);
    $response->assertStatus(200);
    $data = $response->json('data.data');
    $this->assertCount(1, $data);
    $this->assertEquals($category1->id, $data[0]['category']['id']);
  }
}

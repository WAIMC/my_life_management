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

class UpdateCategorySkillMgmtTest extends TestCase
{
  use RefreshDatabase;

  private string $baseUrl = 'api/admin/category-skill-mgmt/update';

  /**
   * Helper to get authenticated cookies with 'root' role
   */
  private function getAuthCookies(AdminMst $admin): array
  {
    $rootRole = RoleMst::where('name', 'root')->first();
    if (!$rootRole) {
      $rootRole = RoleMst::create(['name' => 'root', 'permission' => '{}', 'is_active' => 1, 'is_delete' => 0]);
    }

    $this->grantAccessTo($rootRole, 'PUT', $this->baseUrl);

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
   * Test [CAT_SKL_UPD_001] Unauthenticated
   */
  public function test_CAT_SKL_UPD_001_unauthenticated()
  {
    $response = $this->putJson($this->baseUrl, []);
    $response->assertStatus(401);
  }

  /**
   * Test [CAT_SKL_UPD_002] Invalid Data
   */
  public function test_CAT_SKL_UPD_002_invalid_data()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    // Missing required fields in insert
    $payload = [
      'insert' => [
        ['category_mgmt_id' => 1] // missing skill_mgmt_id
      ]
    ];

    $response = $this->call('PUT', $this->baseUrl, $payload, $cookies);
    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $this->assertArrayHasKey('insert.0.skill_mgmt_id', $response->json('error.messages'));
  }

  /**
   * Test [CAT_SKL_UPD_003] Success Insert
   */
  public function test_CAT_SKL_UPD_003_success_insert()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $category = CategoryMgmt::factory()->create();
    $skill = SkillMgmt::factory()->create();

    $payload = [
      'insert' => [
        [
          'category_mgmt_id' => $category->id,
          'skill_mgmt_id' => $skill->id,
        ]
      ]
    ];

    $response = $this->call('PUT', $this->baseUrl, $payload, $cookies);

    if ($response->status() !== 200) {
      $response->dump();
    }
    $response->assertStatus(200);

    $this->assertDatabaseHas('category_skill_mgmt', [
      'category_mgmt_id' => $category->id,
      'skill_mgmt_id' => $skill->id,
    ]);
  }

  /**
   * Test [CAT_SKL_UPD_004] Success Delete
   */
  public function test_CAT_SKL_UPD_004_success_delete()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $category = CategoryMgmt::factory()->create();
    $skill = SkillMgmt::factory()->create();

    // Create existence
    CategorySkillMgmt::create([
      'category_mgmt_id' => $category->id,
      'skill_mgmt_id' => $skill->id,
    ]);

    $payload = [
      'delete' => [
        [
          'category_mgmt_id' => $category->id,
          'skill_mgmt_id' => $skill->id,
        ]
      ]
    ];

    $response = $this->call('PUT', $this->baseUrl, $payload, $cookies);
    $response->assertStatus(200);

    $this->assertDatabaseMissing('category_skill_mgmt', [
      'category_mgmt_id' => $category->id,
      'skill_mgmt_id' => $skill->id,
    ]);
  }

  /**
   * Test [CAT_SKL_UPD_005] Success Mixed Insert and Delete
   */
  public function test_CAT_SKL_UPD_005_success_mixed()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $cat1 = CategoryMgmt::factory()->create();
    $skill1 = SkillMgmt::factory()->create();

    $cat2 = CategoryMgmt::factory()->create();
    $skill2 = SkillMgmt::factory()->create();

    // Exist: (cat1, skill1)
    CategorySkillMgmt::create([
      'category_mgmt_id' => $cat1->id,
      'skill_mgmt_id' => $skill1->id,
    ]);

    // Operation: Delete (cat1, skill1), Insert (cat2, skill2)
    $payload = [
      'delete' => [
        [
          'category_mgmt_id' => $cat1->id,
          'skill_mgmt_id' => $skill1->id,
        ]
      ],
      'insert' => [
        [
          'category_mgmt_id' => $cat2->id,
          'skill_mgmt_id' => $skill2->id,
        ]
      ]
    ];

    $response = $this->call('PUT', $this->baseUrl, $payload, $cookies);
    $response->assertStatus(200);

    $this->assertDatabaseMissing('category_skill_mgmt', [
      'category_mgmt_id' => $cat1->id,
      'skill_mgmt_id' => $skill1->id,
    ]);

    $this->assertDatabaseHas('category_skill_mgmt', [
      'category_mgmt_id' => $cat2->id,
      'skill_mgmt_id' => $skill2->id,
    ]);
  }
}

<?php

namespace Tests\Feature\Master\ApiMst;

use App\Constants\CommonVal;
use App\Models\Master\AdminMst;
use App\Models\Master\ApiMst;
use App\Models\Master\FeatureMst;
use Database\Factories\Master\ApiMstFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ListApiMstTest extends TestCase
{
  use RefreshDatabase;

  private string $listUrl = 'api/admin/api-mst/list';

  /**
   * Set up the test environment.
   *
   * @return void
   */
  protected function setUp(): void
  {
    parent::setUp();
  }

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

    // Grant access to LIST endpoint
    $this->grantAccessTo($rootRole, 'GET', 'api/admin/api-mst/list');

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
   * Test [MST_API_LST_001] Unauthenticated
   */
  public function test_MST_API_LST_001_unauthenticated()
  {
    $response = $this->json('GET', $this->listUrl);

    // Assert status 401 Unauthorized
    $response->assertStatus(401);
  }

  /**
   * Test [MST_API_LST_002] Date Format Invalid
   */
  public function test_MST_API_LST_002_invalid_date_format()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $payload = [
      'from_date' => 'invalid-date',
    ];

    $response = $this->call('GET', $this->listUrl, $payload, $cookies);

    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $response->assertJsonPath('error.messages.from_date.0', fn($msg) => !empty($msg));
  }

  /**
   * Test [MST_API_LST_003] To Date before From Date
   */
  public function test_MST_API_LST_003_to_date_before_from_date()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $payload = [
      'from_date' => now()->format('d/m/Y'),
      'to_date' => now()->subDay()->format('d/m/Y'),
    ];

    $response = $this->call('GET', $this->listUrl, $payload, $cookies);

    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $response->assertJsonPath('error.messages.to_date.0', fn($msg) => !empty($msg));
  }

  /**
   * Test [MST_API_LST_004] Feature Mst Id Invalid Type
   */
  public function test_MST_API_LST_004_invalid_feature_mst_id_type()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $payload = [
      'feature_mst_id' => 'abc',
    ];

    $response = $this->call('GET', $this->listUrl, $payload, $cookies);

    $response->assertStatus(CommonVal::HTTP_UNPROCESSABLE_CONTENT);
    $response->assertJsonPath('error.messages.feature_mst_id.0', fn($msg) => !empty($msg));
  }

  /**
   * Test [MST_API_LST_005] Filter by feature_mst_id
   */
  public function test_MST_API_LST_005_filter_by_feature_mst_id()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    $feature1 = FeatureMst::factory()->create();
    $feature2 = FeatureMst::factory()->create();

    $api1 = ApiMst::factory()->create(['feature_mst_id' => $feature1->id]);
    $api2 = ApiMst::factory()->create(['feature_mst_id' => $feature2->id]);

    $payload = [
      'feature_mst_id' => $feature1->id,
    ];

    $response = $this->call('GET', $this->listUrl, $payload, $cookies);

    $response->assertStatus(200);
    $response->assertJsonFragment(['id' => $api1->id]);
    $response->assertJsonMissing(['id' => $api2->id]);
  }

  /**
   * Test [MST_API_LST_006] Search by Name
   */
  public function test_MST_API_LST_006_search_by_name()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    // Ensure distinct names
    $uniqueName1 = 'UniqueApiNameOne';
    $uniqueName2 = 'UniqueApiNameTwo';

    $api1 = ApiMst::factory()->create(['name' => $uniqueName1]);
    $api2 = ApiMst::factory()->create(['name' => $uniqueName2]);

    $payload = [
      'name' => 'NameOne',
    ];

    $response = $this->call('GET', $this->listUrl, $payload, $cookies);

    $response->assertStatus(200);
    $response->assertJsonFragment(['id' => $api1->id]);
    $response->assertJsonMissing(['id' => $api2->id]);
  }

  /**
   * Test [MST_API_GEN_001] Response Structure
   */
  public function test_MST_API_GEN_001_response_structure_and_pagination()
  {
    $admin = AdminMst::factory()->create();
    $cookies = $this->getAuthCookies($admin);

    ApiMst::factory()->count(5)->create();

    $response = $this->call('GET', $this->listUrl, [], $cookies);

    $response->assertStatus(200);
    $response->assertJsonStructure([
      'data' => [
        'data' => [
          '*' => [
            'id',
            'type',
            'name',
            'path',
            'is_active',
            'feature_mst_id',
            'updated_at',
          ]
        ],
        'links',
        'meta'
      ]
    ]);
  }
}

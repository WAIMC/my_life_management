<?php

namespace Tests\Feature\History\Management\SkillDescriptionMgmtHist;

use App\Models\Master\AdminMst;
use App\Models\Master\ApiMst;
use App\Models\Master\FeatureMst;
use App\Models\Master\RoleMst;
use App\Models\Management\SkillDescriptionMgmt;
use App\Models\History\Management\SkillDescriptionMgmtHist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class DeleteSkillDescriptionMgmtHistTest extends TestCase
{
    use RefreshDatabase;

    private string $baseUrl = 'api/admin/skill-description-mgmt-hist/delete';

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

        $this->grantAccessTo($rootRole, 'DELETE', $this->baseUrl);

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
        $typeMap = ['DELETE' => 0, 'POST' => 1, 'PUT' => 2, 'PATCH' => 3, 'DELETE' => 4];
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

    public function test_SKL_DSC_HST_DEL_001_unauthenticated()
    {
        $response = $this->getJson($this->baseUrl);
        $response->assertStatus(401);
    }

    public function test_SKL_DSC_HST_DEL_002_success_list()
    {
        $admin = AdminMst::factory()->create();
        $cookies = $this->getAuthCookies($admin);

        $desc = SkillDescriptionMgmt::factory()->create();
        SkillDescriptionMgmtHist::create([
            'skill_description_mgmt_id' => $desc->id,
            'parent_id' => 0,
            'title' => $desc->title,
            'summary' => $desc->summary,
            'article' => $desc->article,
            'status' => $desc->status,
            'is_display' => $desc->is_display,
            'rank_order' => $desc->rank_order,
            'skill_mgmt_id' => $desc->skill_mgmt_id,
            'action' => 1,
            'author_id' => $admin->id,
            'created_at' => now(),
        ]);

        $response = $this->call('DELETE', $this->baseUrl, [], $cookies);
        $response->assertStatus(200);

        $data = $response->json('data.data');
        $this->assertGreaterThanOrEqual(1, count($data));
        
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('skill_description_mgmt_id', $data[0]);
        $this->assertArrayHasKey('action', $data[0]);
    }

    public function test_SKL_DSC_HST_DEL_003_filter_by_desc()
    {
        $admin = AdminMst::factory()->create();
        $cookies = $this->getAuthCookies($admin);

        $desc1 = SkillDescriptionMgmt::factory()->create();
        $desc2 = SkillDescriptionMgmt::factory()->create();
        
        SkillDescriptionMgmtHist::create([
            'skill_description_mgmt_id' => $desc1->id,
            'parent_id' => 0,
            'title' => $desc1->title,
            'summary' => $desc1->summary,
            'article' => $desc1->article,
            'status' => $desc1->status,
            'is_display' => $desc1->is_display,
            'rank_order' => $desc1->rank_order,
            'skill_mgmt_id' => $desc1->skill_mgmt_id,
            'action' => 1,
            'author_id' => $admin->id,
            'created_at' => now(),
        ]);

        $response = $this->call('DELETE', $this->baseUrl, ['skill_description_mgmt_id' => $desc1->id], $cookies);
        $response->assertStatus(200);
        
        $data = $response->json('data.data');
        foreach ($data as $item) {
            $this->assertEquals($desc1->id, $item['skill_description_mgmt_id']);
        }
    }
}

<?php

namespace Tests\Feature\Auth;

use App\Constants\CommonVal;
use App\Constants\Messages;
use App\Models\Master\AdminMst;
use App\Models\Master\RoleMst;
use App\Models\Master\AdminRoleMst;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LoginApiTest extends TestCase
{
  // Use DatabaseTransactions to roll back changes after each test
  use DatabaseTransactions;

  protected string $loginUrl = '/api/admin/credential/login';

  protected function setUp(): void
  {
    parent::setUp();
  }

  /**
   * Test allowed HTTP methods.
   * GET request should likely fail with 405 Method Not Allowed.
   */
  public function test_login_method_not_allowed()
  {
    $response = $this->getJson($this->loginUrl);

    $response->assertStatus(405);
  }

  /**
   * Test validation errors (422).
   */
  public function test_login_validation_errors()
  {
    // 1. Empty request
    $response = $this->postJson($this->loginUrl, []);

    $response->assertStatus(422)
      ->assertJson([
        'error' => [
          'code' => 422,
          'status' => true, // Odd that status is true for error, but following observed output
        ]
      ]);

    // Check specific validation messages structure: error.messages.field[0]
    $json = $response->json();
    $this->assertArrayHasKey('user_name', $json['error']['messages']);
    $this->assertArrayHasKey('password', $json['error']['messages']);

    // 2. Missing password
    $response = $this->postJson($this->loginUrl, ['user_name' => 'admin']);
    $response->assertStatus(422);

    $json = $response->json();
    $this->assertArrayHasKey('password', $json['error']['messages']);
  }

  /**
   * Test invalid credentials (401).
   */
  public function test_login_fail_invalid_credentials()
  {
    $response = $this->postJson($this->loginUrl, [
      'user_name' => 'non_existent_user',
      'password' => 'password123',
    ]);

    $response->assertStatus(401)
      ->assertJson([
        'error' => [
          'code' => 401,
          // 'messages' => Messages::E0401 // Messages::E0401 is "Unauthorized Access" likely
        ]
      ]);

    // Check message content
    $this->assertEquals(Messages::E0401, $response->json()['error']['messages']);
  }

  /**
   * Test successful login (200) and verify Cookies & JSON.
   */
  public function test_login_success_structure_and_cookies()
  {
    // Setup Admin
    $admin = AdminMst::factory()->create([
      'user_name' => 'feature_test_user',
      'password' => Hash::make('password123'),
      'limit_access' => 0,
    ]);

    // Assign Role
    $rootRole = RoleMst::where('name', 'root')->first();
    DB::table('admin_role_mst')->insert([
      'admin_mst_id' => $admin->id,
      'role_mst_id' => $rootRole->id,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    $response = $this->postJson($this->loginUrl, [
      'user_name' => 'feature_test_user',
      'password' => 'password123',
    ]);

    $response->assertStatus(200);



    // 1. Verify JSON Structure
    // Expected: { "data": { "ttl": 300 }, "error": { "status": false, "code": 200, "messages": null } }
    $response->assertJsonStructure([
      'data' => ['ttl'],
      'error' => ['status', 'code', 'messages']
    ]);

    // 2. Verify Headers / Cookies
    // Feature tests allow asserting cookies on the response
    $response->assertCookie('access_token');
    $response->assertCookie('refresh_token');

    // Verify Cookie Values are not empty
    $accessCookie = $response->getCookie('access_token', false);
    $this->assertNotEmpty($accessCookie->getValue());

    $refreshCookie = $response->getCookie('refresh_token', false);
    $this->assertNotEmpty($refreshCookie->getValue());

    // Verify Cookie Attributes
    $this->assertTrue($accessCookie->isHttpOnly(), 'Access token should be HttpOnly');
  }
}

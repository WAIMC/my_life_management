<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\Master\AdminMst;
use App\Models\Master\RoleMst;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogoutApiTest extends TestCase
{
  use DatabaseTransactions;

  protected string $loginUrl = '/api/admin/credential/login';
  protected string $logoutUrl = '/api/admin/credential/trust/logout';

  /**
   * Test successful logout functionality.
   * Verifies that logout clears cookies.
   */
  public function test_logout_success()
  {
    // 1. Setup User and Role
    $uniqueName = 'logout_feature_user_' . uniqid();
    $admin = AdminMst::factory()->create([
      'user_name' => $uniqueName,
      'password' => Hash::make('password123'),
    ]);


    $rootRole = RoleMst::where('name', 'root')->first();
    DB::table('admin_role_mst')->insert([
      'admin_mst_id' => $admin->id,
      'role_mst_id' => $rootRole->id,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // 2. Perform Login to get Cookies
    $loginResponse = $this->postJson($this->loginUrl, [
      'user_name' => $uniqueName,
      'password' => 'password123',
    ]);


    $loginResponse->assertStatus(200);

    $accessTokenCookie = $loginResponse->getCookie('access_token', false);
    $refreshTokenCookie = $loginResponse->getCookie('refresh_token', false);

    $this->assertNotNull($accessTokenCookie);
    $this->assertNotNull($refreshTokenCookie);

    // 3. Call Logout Endpoint
    $this->disableCookieEncryption();

    $response = $this->call('POST', $this->logoutUrl, [], [
      'access_token' => $accessTokenCookie->getValue(),
      'refresh_token' => $refreshTokenCookie->getValue(),
    ], [], ['HTTP_ACCEPT' => 'application/json']);

    // 4. Verify Response
    $response->assertStatus(200);

    // Verify JSON Structure
    $response->assertJson([
      'data' => [
        'ttl' => null
      ],
      'error' => [
        'code' => 200
      ]
    ]);

    // Verify Cookies Deletion
    // Assert that cookies are set in the response
    $response->assertCookie('access_token');
    $response->assertCookie('refresh_token');

    // Verify Cookie Values are empty or "deleted"
    $logoutAccessCookie = $response->getCookie('access_token', false);
    $logoutRefreshCookie = $response->getCookie('refresh_token', false);

    // Check if values are logically empty or 'deleted' (Laravel uses 'deleted' string for deletion)
    $this->assertTrue(
      empty($logoutAccessCookie->getValue()) || $logoutAccessCookie->getValue() === 'deleted' || $logoutAccessCookie->getExpiresTime() < time(),
      "Access token cookie should be set to deleted/expired. Actual: " . $logoutAccessCookie->getValue()
    );

    $this->assertTrue(
      empty($logoutRefreshCookie->getValue()) || $logoutRefreshCookie->getValue() === 'deleted' || $logoutRefreshCookie->getExpiresTime() < time(),
      "Refresh token cookie should be set to deleted/expired. Actual: " . $logoutRefreshCookie->getValue()
    );
  }
}

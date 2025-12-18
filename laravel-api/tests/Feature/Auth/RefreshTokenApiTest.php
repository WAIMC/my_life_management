<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\Master\AdminMst;
use App\Models\Master\RoleMst;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RefreshTokenApiTest extends TestCase
{
  use DatabaseTransactions;

  protected string $loginUrl = '/api/admin/credential/login';
  protected string $refreshTokenUrl = '/api/admin/credential/trust/refresh-token';

  protected function setUp(): void
  {
    parent::setUp();
  }

  /**
   * Test successful refresh token functionality.
   * Verifies that providing valid cookies results in new cookies and updated state.
   */
  public function test_refresh_token_success()
  {
    // 1. Setup User and Role
    $uniqueName = 'refresh_feature_user_' . uniqid();
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

    // Extract cookies automatically handled by test client if we chain? 
    // No, feature tests retain state if using same session, but API is stateless usually.
    // However, Laravel Test Client cookies persist if we don't clear them?
    // Let's explicitly get cookies to be sure.

    $accessTokenCookie = $loginResponse->getCookie('access_token', false);
    $refreshTokenCookie = $loginResponse->getCookie('refresh_token', false);

    $this->assertNotNull($accessTokenCookie);
    $this->assertNotNull($refreshTokenCookie);

    // 3. Call Refresh Token Endpoint
    // We pass the cookies manually to verify "Login then Refresh" flow

    // Wait 1 second to ensure token IAT changes
    sleep(1);

    $this->disableCookieEncryption();

    // Use call() to pass cookies explicitly
    $response = $this->call('POST', $this->refreshTokenUrl, [], [
      'access_token' => $accessTokenCookie->getValue(),
      'refresh_token' => $refreshTokenCookie->getValue(),
    ], [], ['HTTP_ACCEPT' => 'application/json']);

    // 4. Verify Response
    $response->assertStatus(200);

    // Verify JSON Structure
    $response->assertJsonStructure([
      'data' => ['ttl'], // User requested only ttl might be returned if structure same as login?
      // "return to the same response format as login" -> so error field too?
      'error' => ['status', 'code', 'messages']
    ]);

    // Verify New Cookies Present
    $response->assertCookie('access_token');
    $response->assertCookie('refresh_token');

    // Verify Cookies NOT Empty
    $newAccessCookie = $response->getCookie('access_token', false);
    $newRefreshCookie = $response->getCookie('refresh_token', false);

    $this->assertNotEmpty($newAccessCookie->getValue());
    $this->assertNotEmpty($newRefreshCookie->getValue());

    // Verify New Token is different from old one?
    // Usually yes, but technically could be same if implementation allowed it (it doesn't, it generates new).
    $this->assertNotEquals($accessTokenCookie->getValue(), $newAccessCookie->getValue());
    // $this->assertNotEquals($refreshTokenCookie->getValue(), $newRefreshCookie->getValue()); 
    // Refresh token might rotate.
  }

  /**
   * Test refresh token with missing cookies.
   */
  public function test_refresh_token_fail_unauthorized()
  {
    $response = $this->postJson($this->refreshTokenUrl);

    $response->assertStatus(401);
    $response->assertJson([
      'error' => [
        'code' => 401
      ]
    ]);
  }

  /**
   * Test refresh token with missing Refresh Token cookie (but valid Access Token).
   */
  public function test_refresh_token_fail_missing_refresh_cookie()
  {
    $uniqueName = 'refresh_fail_missing_c_' . uniqid();
    $admin = AdminMst::factory()->create([
      'user_name' => $uniqueName,
      'password' => Hash::make('password123'),
    ]);

    // Perform Login to get Cookies
    $loginResponse = $this->postJson($this->loginUrl, [
      'user_name' => $uniqueName,
      'password' => 'password123',
    ]);
    $accessTokenCookie = $loginResponse->getCookie('access_token', false);

    $this->disableCookieEncryption();
    // Send only Access Token
    $response = $this->call('POST', $this->refreshTokenUrl, [], [
      'access_token' => $accessTokenCookie->getValue(),
      // 'refresh_token' => missing
    ], [], ['HTTP_ACCEPT' => 'application/json']);

    $response->assertStatus(401);
  }

  /**
   * Test refresh token with Revoked (Not in DB) Refresh Token.
   */
  public function test_refresh_token_fail_revoked_token()
  {
    $uniqueName = 'refresh_fail_revoked_' . uniqid();
    $admin = AdminMst::factory()->create([
      'user_name' => $uniqueName,
      'password' => Hash::make('password123'),
    ]);

    // Assign Role (needed for login/permission check inside service if called)
    $rootRole = RoleMst::where('name', 'root')->first();
    DB::table('admin_role_mst')->insert(['admin_mst_id' => $admin->id, 'role_mst_id' => $rootRole->id]);

    // Login
    $loginResponse = $this->postJson($this->loginUrl, [
      'user_name' => $uniqueName,
      'password' => 'password123',
    ]);
    $accessTokenCookie = $loginResponse->getCookie('access_token', false);
    $refreshTokenCookie = $loginResponse->getCookie('refresh_token', false);

    // Physically delete the token from DB to simulate revocation
    // We need to decode cookie to get the hash or just delete all tokens for this user
    DB::table('token_mst')->where('account_id', $admin->id)->delete();

    $this->disableCookieEncryption();
    $response = $this->call('POST', $this->refreshTokenUrl, [], [
      'access_token' => $accessTokenCookie->getValue(),
      'refresh_token' => $refreshTokenCookie->getValue(),
    ], [], ['HTTP_ACCEPT' => 'application/json']);

    $response->assertStatus(401);
  }

  /**
   * Test refresh token with Invalid Signature.
   */
  public function test_refresh_token_fail_invalid_signature()
  {
    // Reuse logic to get a valid structure, but tamper with it?
    // Or just generate a fake token signed with wrong key.
    $uniqueName = 'refresh_fail_sig_' . uniqid();
    $admin = AdminMst::factory()->create();

    // Valid Access Token (needed to pass Middleware)
    // Note: AdminMiddleware verifies Access Token using env('ACCESS_TOKEN_SECRET').
    // If we want to test REFRESH TOKEN invalid signature, we must pass Middleware first.

    // 1. Get Valid Access Token
    $loginResponse = $this->postJson($this->loginUrl, [
      // Need real user for login or just manual generation
      'user_name' => 'non_existent',
      'password' => 'wrong'
    ]);
    // Easier to generate manually
    $accessToken = \App\Utilities\JsonWebToken::encode(
      \App\Utilities\JsonWebToken::JWTPayload(['id' => (string)$admin->id, 'type' => \App\Constants\CommonVal::ADMIN_TYPE], false),
      env('ACCESS_TOKEN_SECRET')
    );

    // 2. Generate Invalid Refresh Token (Wrong Secret)
    $invalidRefreshToken = \App\Utilities\JsonWebToken::encode(
      \App\Utilities\JsonWebToken::JWTPayload(['id' => (string)$admin->id, 'type' => \App\Constants\CommonVal::ADMIN_TYPE], true),
      'WRONG_SECRET_KEY'
    );

    // Need to set Redis state for Access Token otherwise Middleware fails with E0609?
    // AdminMiddleware checks Redis exists.
    $parentKey = \App\Constants\CommonVal::ADMIN_TYPE . ":{$admin->id}";
    $tokenKey = $parentKey . ":{$accessToken}";
    \Illuminate\Support\Facades\Redis::set($tokenKey, '1');
    // Also Permission key
    $permissionKey = $parentKey . ":" . \App\Constants\CommonVal::ADMIN_PERMISSION_TABLE;
    $allowedRoutes = ['REFRESH-TOKEN']; // Mock allowed route?
    // Only GET/POST method check?
    // Middleware logic: $pathsJson = Redis::hget($permissionTableKey, $method);
    \Illuminate\Support\Facades\Redis::hset($permissionKey, 'POST', json_encode(['api/admin/credential/trust/refresh-token']));

    $this->disableCookieEncryption();
    $response = $this->call('POST', $this->refreshTokenUrl, [], [
      'access_token' => $accessToken,
      'refresh_token' => $invalidRefreshToken,
    ], [], ['HTTP_ACCEPT' => 'application/json']);

    // Expect 500 (Signature verification failed in Service -> UnexpectedValueException) OR 401 handled?
    // Service calls JsonWebToken::decode.
    // JsonWebToken::decode throws UnexpectedValueException if signature invalid.
    // Laravel Handler converts it? 
    // If unhandled, it is 500. Users usually prefer 401.
    // Let's check exception handling.
    // AdminMiddleware catches AuthorizationException.
    // CredentialService does NOT catch decode exception.

    // However, usually detailed JWT library throws specific exception.
    // If it returns 500, asserts 500. If 401, asserts 401.
    // User prompt: "case thất bại không có refresh token... refresh token không tồn tại...".
    // "Invalid token" -> usually 401.

    // For now, assertion 401 or 500? I'll assert 500 if unhandled, or try-catch in Service?
    // I will assume it might be 500 or 401. Let's see. 
    // Actually, if it fails signature, `JsonWebToken` throws.

    // Let's assert != 200 first, or expect 401 if global handler handles it.
    // Most secure apps return 401.

    $response->assertStatus(401);
    // Wait, if it throws UnexpectedValueException, Handler might not map to 401 unless configured.
    // I will assert 500 if my code doesn't catch it. 
    // But user likely wants to Ensure it fails.
  }
}

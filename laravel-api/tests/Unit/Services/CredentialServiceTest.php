<?php

namespace Tests\Unit\Services;

use App\Constants\CommonVal;
use App\Constants\Messages;
use App\Models\Master\AdminMst;
use App\Models\Master\AdminRoleMst;
use App\Models\Master\RoleMst;
use App\Models\Master\TokenMst;
use App\Services\Custom\CredentialService;
use App\Utilities\JsonWebToken;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Tests\TestCase;

class CredentialServiceTest extends TestCase
{
  protected CredentialService $credentialService;

  protected function setUp(): void
  {
    parent::setUp();
    // DatabaseTransactions trait rolls back DB. tearDown cleans Redis.
    $this->credentialService = new CredentialService();
  }

  /**
   * Test login fails when user does not exist.
   */
  public function test_login_fail_user_not_found()
  {
    $request = Request::create('/login', 'POST', [
      'user_name' => 'non_existent_user_' . Str::random(5),
      'password' => 'password',
    ]);

    $this->expectException(AuthorizationException::class);
    $this->expectExceptionMessage(Messages::E0401);

    $this->credentialService->login($request);
  }

  /**
   * Test login fails when account is locked (limit_access >= LIMIT_ACCESS_FAIL).
   */
  public function test_login_fail_locked_account()
  {
    $admin = AdminMst::factory()->create([
      'limit_access' => CommonVal::LIMIT_ACCESS_FAIL,
    ]);

    $request = Request::create('/login', 'POST', [
      'user_name' => $admin->user_name,
      'password' => 'correct_or_wrong_does_not_matter',
    ]);

    $this->expectException(AuthorizationException::class);
    $this->expectExceptionMessage(Messages::E0610);

    $this->credentialService->login($request);
  }

  /**
   * Test login fails with wrong password and increments limit_access.
   */
  public function test_login_fail_wrong_password_increments_limit_access()
  {
    $admin = AdminMst::factory()->create([
      'password' => Hash::make('correct_password'),
      'limit_access' => 0,
    ]);

    $request = Request::create('/login', 'POST', [
      'user_name' => $admin->user_name,
      'password' => 'wrong_password',
    ]);

    try {
      $this->credentialService->login($request);
      $this->fail('Expected AuthorizationException was not thrown.');
    } catch (AuthorizationException $e) {
      $this->assertEquals(Messages::E0401, $e->getMessage());
      // Verify DB: limit_access increased by 1
      $this->assertEquals(1, $admin->fresh()->limit_access);
    }
  }

  /**
   * Test login success with full verification of tokens, DB reset, and Redis.
   */
  public function test_login_success_full_verification()
  {
    // Setup: Admin with limit_access > 0 to verify reset
    $admin = AdminMst::factory()->create([
      'password' => Hash::make('password123'),
      'limit_access' => 2,
    ]);

    // Assign 'root' role to ensuring permissions exist (so Redis key is created)
    $rootRole = RoleMst::where('name', 'root')->first();
    \Illuminate\Support\Facades\DB::table('admin_role_mst')->insert([
      'admin_mst_id' => $admin->id,
      'role_mst_id' => $rootRole->id,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    $request = Request::create('/login', 'POST', [
      'user_name' => $admin->user_name,
      'password' => 'password123',
    ]);

    $result = $this->credentialService->login($request);

    // 1. Verify Response Structure
    $this->assertArrayHasKey('ttl', $result);
    $this->assertArrayHasKey('_cookies', $result);
    $this->assertEquals(CommonVal::MAX_ACCESS_TTL, $result['ttl']);

    // 2. Verify DB: limit_access reset to 0
    $this->assertEquals(0, $admin->fresh()->limit_access);

    // 3. Extract and Verify Tokens
    $accessCookie = collect($result['_cookies'])->firstWhere('name', 'access_token');
    $refreshCookie = collect($result['_cookies'])->firstWhere('name', 'refresh_token');

    $this->assertNotNull($accessCookie, 'Access token cookie missing');
    $this->assertNotNull($refreshCookie, 'Refresh token cookie missing');

    // Verify Access Token Signature & Payload
    $accessPayload = JsonWebToken::decode($accessCookie['value'], env('ACCESS_TOKEN_SECRET'), false);
    $this->assertEquals((string)$admin->id, $accessPayload['body']['id']);
    $this->assertEquals(CommonVal::ADMIN_TYPE, $accessPayload['body']['type']);
    // Verify Access Token TTL (approximate)
    $this->assertEqualsWithDelta(time() + CommonVal::MAX_ACCESS_TTL, $accessPayload['body']['exp'], 5);

    // Verify Refresh Token Signature & Payload
    $refreshPayload = JsonWebToken::decode($refreshCookie['value'], env('REFRESH_TOKEN_SECRET'), true);
    $this->assertEquals((string)$admin->id, $refreshPayload['body']['id']);
    $this->assertEqualsWithDelta(time() + CommonVal::MAX_REFRESH_TTL, $refreshPayload['body']['exp'], 5);

    // 4. Verify Redis Data
    $parentKey = CommonVal::ADMIN_TYPE . ":{$admin->id}";
    $tokenKey = $parentKey . ":{$accessCookie['value']}";
    $permissionKey = $parentKey . ":" . CommonVal::ADMIN_PERMISSION_TABLE;

    // Verify Access Token Key
    $this->assertTrue((bool)Redis::exists($tokenKey), "Redis token key {$tokenKey} should exist");
    $tokenTtl = Redis::ttl($tokenKey);
    $this->assertGreaterThan(0, $tokenTtl);
    $this->assertLessThanOrEqual(CommonVal::MAX_ACCESS_TTL, $tokenTtl);

    // Verify fields in Token Key (e.g., last_access_at)
    $this->assertTrue((bool)Redis::hexists($tokenKey, 'last_access_at'));

    // Verify Permission Key
    $this->assertTrue((bool)Redis::exists($permissionKey), "Redis permission key {$permissionKey} should exist");
    $permTtl = Redis::ttl($permissionKey);
    // Permission key TTL should be set to match token's TTL (approx)
    $this->assertEqualsWithDelta($tokenTtl, $permTtl, 2);

    // 5. Verify DB: Refresh Token should be stored
    $this->assertDatabaseHas('token_mst', [
      'account_id' => $admin->id,
    ]);
  }

  /**
   * Test re-login behavior.
   */
  public function test_login_success_multiple_times_behavior()
  {
    $admin = AdminMst::factory()->create([
      'password' => Hash::make('password123'),
    ]);

    // Assign 'root' role
    $rootRole = RoleMst::where('name', 'root')->first();
    \Illuminate\Support\Facades\DB::table('admin_role_mst')->insert([
      'admin_mst_id' => $admin->id,
      'role_mst_id' => $rootRole->id,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    $request = Request::create('/login', 'POST', [
      'user_name' => $admin->user_name,
      'password' => 'password123',
    ]);

    // --- First Login ---
    $result1 = $this->credentialService->login($request);
    $accessToken1 = collect($result1['_cookies'])->firstWhere('name', 'access_token')['value'];
    $tokenKey1 = CommonVal::ADMIN_TYPE . ":{$admin->id}:{$accessToken1}";

    $this->assertTrue((bool)Redis::exists($tokenKey1));

    // Wait 1 second
    sleep(1);

    // --- Second Login ---
    $result2 = $this->credentialService->login($request);
    $accessToken2 = collect($result2['_cookies'])->firstWhere('name', 'access_token')['value'];
    $tokenKey2 = CommonVal::ADMIN_TYPE . ":{$admin->id}:{$accessToken2}";

    $this->assertNotEquals($accessToken1, $accessToken2);
    $this->assertTrue((bool)Redis::exists($tokenKey2));
  }

  public function test_refresh_token_success()
  {
    $admin = AdminMst::factory()->create();
    // Assign role for permission logic
    $rootRole = RoleMst::where('name', 'root')->first();
    \Illuminate\Support\Facades\DB::table('admin_role_mst')->insert([
      'admin_mst_id' => $admin->id,
      'role_mst_id' => $rootRole->id,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // 1. Initial Login
    $loginRequest = Request::create('/login', 'POST', [
      'user_name' => $admin->user_name,
      'password' => 'password123', // factory default password is 'password' usually, wait.. 
      // User factory sets password to 'password' or Hash::make('password')? 
      // AdminMst factory usually sets 'password' => static::$password ??= Hash::make('password'),
    ]);
    // To safely test login, simpler to manually setup token in DB for refresh

    // Manual setup similar to test_logout to control tokens
    $oldRefreshToken = JsonWebToken::encode(
      JsonWebToken::JWTPayload(['id' => (string)$admin->id, 'type' => CommonVal::ADMIN_TYPE], true),
      env('REFRESH_TOKEN_SECRET')
    );
    $oldTokenId = md5($oldRefreshToken);
    TokenMst::factory()->create([
      'token_hash' => $oldTokenId,
      'account_id' => $admin->id,
    ]);

    // Setup Request
    $request = Request::create('/refresh-token', 'POST');
    $request->cookies->set('refresh_token', $oldRefreshToken);

    // Redis setup not strictly needed for refresh logic unless it checks old access token?
    // Service logic: revokeToken($refreshToken, true) -> finds using hash.
    // It does NOT require Access Token check inside Service (Middleware handles that).

    // ACTION
    sleep(1); // Ensure IAT changes so new token hash differs from old one
    $result = $this->credentialService->refreshToken($request);

    // VERIFICATION

    // 1. Old Refresh Token Deleted from DB
    $this->assertDatabaseMissing('token_mst', ['token_hash' => $oldTokenId]);

    // 2. Response Structure
    $this->assertArrayHasKey('ttl', $result);
    $this->assertArrayHasKey('_cookies', $result);

    $newAccessCookie = collect($result['_cookies'])->firstWhere('name', 'access_token');
    $newRefreshCookie = collect($result['_cookies'])->firstWhere('name', 'refresh_token');

    $this->assertNotNull($newAccessCookie);
    $this->assertNotNull($newRefreshCookie);

    // 3. New Refresh Token in DB
    $newRefreshToken = $newRefreshCookie['value'];
    $newTokenId = md5($newRefreshToken);
    $this->assertDatabaseHas('token_mst', ['token_hash' => $newTokenId]);

    // 4. Redis: Access Token Set
    $parentKey = CommonVal::ADMIN_TYPE . ":{$admin->id}";
    $accessToken = $newAccessCookie['value'];
    $tokenKey = $parentKey . ":{$accessToken}";
    $this->assertTrue((bool)Redis::exists($tokenKey));

    // 5. Redis: Permission Key Updated
    // Since we assigned role, permission key should exist and have updated TTL
    $permissionKey = $parentKey . ":" . CommonVal::ADMIN_PERMISSION_TABLE;
    $this->assertTrue((bool)Redis::exists($permissionKey));
    $this->assertEqualsWithDelta(Redis::ttl($tokenKey), Redis::ttl($permissionKey), 2);
  }

  public function test_refresh_token_fail_no_cookie()
  {
    $request = Request::create('/refresh-token', 'POST');
    // No cookie set

    $this->expectException(AuthorizationException::class);
    $this->expectExceptionCode(CommonVal::HTTP_UNAUTHORIZED);

    $this->credentialService->refreshToken($request);
  }

  public function test_logout()
  {
    $admin = AdminMst::factory()->create();

    // Generate valid tokens manually
    $accessToken = JsonWebToken::encode(
      JsonWebToken::JWTPayload(['id' => (string)$admin->id, 'type' => CommonVal::ADMIN_TYPE], false),
      env('ACCESS_TOKEN_SECRET')
    );

    $refreshToken = JsonWebToken::encode(
      JsonWebToken::JWTPayload(['id' => (string)$admin->id, 'type' => CommonVal::ADMIN_TYPE], true),
      env('REFRESH_TOKEN_SECRET')
    );

    // Redis state
    $parentKey = CommonVal::ADMIN_TYPE . ":{$admin->id}";
    $tokenKey = $parentKey . ":{$accessToken}";
    Redis::hset($tokenKey, 'last_access_at', now());
    Redis::expire($tokenKey, CommonVal::MAX_ACCESS_TTL);

    // DB state
    $tokenId = md5($refreshToken);
    TokenMst::factory()->create([
      'token_hash' => $tokenId,
      'account_id' => $admin->id,
    ]);

    $request = Request::create('/logout', 'POST');
    $request->cookies->set('access_token', $accessToken);
    $request->cookies->set('refresh_token', $refreshToken);

    // ACTION
    $result = $this->credentialService->logout($request);

    // VERIFICATION

    // 1. Response Structure
    $this->assertArrayHasKey('_cookies', $result);
    // User requested: "return response just contains ttl: null and 2 cookies to delete"
    $this->assertNull($result['ttl'] ?? null); // User changed code to set 'ttl' => null

    // 2. Cookies Cleared
    $accessCookie = collect($result['_cookies'])->firstWhere('name', 'access_token');
    $refreshCookie = collect($result['_cookies'])->firstWhere('name', 'refresh_token');

    $this->assertNotNull($accessCookie, 'Logout should return Access Token cookie config');
    $this->assertNotNull($refreshCookie, 'Logout should return Refresh Token cookie config');

    // Value should be null (Service passes null to helper) OR check if 'minutes' < 0
    $this->assertNull($accessCookie['value']);
    $this->assertLessThan(0, $accessCookie['minutes']);

    $this->assertNull($refreshCookie['value']);
    $this->assertLessThan(0, $refreshCookie['minutes']);

    // 3. Redis: Access Token DELETED
    $this->assertFalse((bool)Redis::exists($tokenKey));

    // 4. DB: Refresh Token DELETED
    $this->assertDatabaseMissing('token_mst', ['token_hash' => $tokenId]);
  }

  public function test_me_success()
  {
    $admin = AdminMst::factory()->create();
    $accessToken = JsonWebToken::encode(
      JsonWebToken::JWTPayload(['id' => (string)$admin->id, 'type' => CommonVal::ADMIN_TYPE], false),
      env('ACCESS_TOKEN_SECRET')
    );

    $request = Request::create('/me', 'GET');
    $request->headers->set('Authorization', 'Bearer ' . $accessToken);

    $result = $this->credentialService->me($request);

    $this->assertEquals($admin->id, $result['id']);
    $this->assertEquals($admin->email, $result['email']);
  }

  /**
   * Helper to retrieve cookie value by name from response array.
   * Supports both Cookie objects and raw set-cookie strings.
   */
  private function getCookieValue(array $cookies, string $name)
  {
    foreach ($cookies as $cookie) {
      // Case 1: Cookie Object
      if ($cookie instanceof \Symfony\Component\HttpFoundation\Cookie && $cookie->getName() === $name) {
        return $cookie->getValue();
      }
      // Case 2: Raw String
      if (is_string($cookie) && str_contains($cookie, $name . '=')) {
        if (preg_match('/' . preg_quote($name) . '=([^;]+)/', $cookie, $matches)) {
          return rawurldecode($matches[1]);
        }
      }
    }
    return null;
  }
}

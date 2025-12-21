<?php

namespace Tests\Feature\Auth;

use App\Constants\CommonVal;
use App\Constants\Messages;
use App\Models\Master\AdminMst;
use App\Models\Master\RoleMst;
use App\Utilities\JsonWebToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class MeApiTest extends TestCase
{
  use DatabaseTransactions;

  protected string $meUrl = '/api/admin/credential/me';
  protected string $loginUrl = '/api/admin/credential/login';

  protected function setUp(): void
  {
    parent::setUp();
    Redis::flushall();
  }

  /**
   * Helper to get authenticated cookies with 'root' role
   */
  protected function getAuthCookies(AdminMst $admin): array
  {
    // Assign 'root' role to ensuring permissions exist
    $rootRole = RoleMst::where('name', 'root')->first();
    if (!$rootRole) {
      $rootRole = RoleMst::create(['name' => 'root', 'permission' => '{}', 'is_active' => 1, 'is_delete' => 0]);
    }
    DB::table('admin_role_mst')->insert([
      'admin_mst_id' => $admin->id,
      'role_mst_id' => $rootRole->id,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    // Simulate login flow to get valid tokens and Redis state
    $response = $this->postJson($this->loginUrl, [
      'user_name' => $admin->user_name,
      'password' => 'password', // Assumes 'password' is used in factory setup
    ]);

    $cookies = [];
    foreach ($response->headers->getCookies() as $cookie) {
      $cookies[$cookie->getName()] = $cookie->getValue();
    }
    return $cookies;
  }

  // ======================================================================
  // Layer 1: Route Analysis
  // ======================================================================

  /**
   * T001: Wrong HTTP Method (POST)
   */
  public function test_T001_method_post_not_allowed()
  {
    $response = $this->postJson($this->meUrl);
    $response->assertStatus(CommonVal::HTTP_METHOD_NOT_ALLOWED);
  }

  /**
   * T002: Wrong HTTP Method (PUT)
   */
  public function test_T002_method_put_not_allowed()
  {
    $response = $this->putJson($this->meUrl);
    $response->assertStatus(CommonVal::HTTP_METHOD_NOT_ALLOWED);
  }

  /**
   * T003: Wrong HTTP Method (DELETE)
   */
  public function test_T003_method_delete_not_allowed()
  {
    $response = $this->deleteJson($this->meUrl);
    $response->assertStatus(CommonVal::HTTP_METHOD_NOT_ALLOWED);
  }

  // ======================================================================
  // Layer 2: Middleware Analysis (AdminMiddleware)
  // ======================================================================

  /**
   * T004: Missing Access Token Cookie
   */
  public function test_T004_auth_missing_access_token()
  {
    $response = $this->getJson($this->meUrl);
    $response->assertStatus(CommonVal::HTTP_UNAUTHORIZED);
    $this->assertEquals(Messages::E0401, $response->json('error')['messages']);
  }

  /**
   * T005: Invalid Access Token (Tampered)
   */
  public function test_T005_auth_invalid_access_token()
  {
    $response = $this->withCookie('access_token', 'invalid_token_string')
      ->getJson($this->meUrl);

    $this->assertNotEquals(CommonVal::HTTP_OK, $response->status());
    // Expect generic Unauthorized or specific JWT error depending on handler
    // Based on LogoutApiTest experience, it might return E0401 or E06xx
    // Let's assert status 401 mostly.
    $response->assertStatus(CommonVal::HTTP_UNAUTHORIZED);
  }

  /**
   * T006: Expired Access Token
   */
  public function test_T006_auth_expired_access_token()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    
    // Create expired token
    $payload = [
      'id' => (string)$admin->id,
      'type' => CommonVal::ADMIN_TYPE,
    ];
    // Generate token with past expiration
    // Note: JsonWebToken::encode uses current time for iat, but we can't easily inject past time 
    // unless we modify JWTPayload or use a mock.
    // However, we can manually construct a JWT with exp in the past.
    
    // Manual JWT construction for expired token
    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload['exp'] = time() - 3600; // Expired 1 hour ago
    $payload['iat'] = time() - 7200;
    $payloadJson = json_encode($payload);
    
    $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payloadJson));
    
    $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, env('ACCESS_TOKEN_SECRET'), true);
    $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
    $expiredToken = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

    $response = $this->withCookie('access_token', $expiredToken)
      ->getJson($this->meUrl);

    $response->assertStatus(CommonVal::HTTP_UNAUTHORIZED);
    // Message might be E0607 "Invalid expiration time" or generic E0401
    // Checking code is safer
    $this->assertTrue(in_array($response->json('error')['messages'], [Messages::E0607, Messages::E0401]));
  }

  /**
   * T007: Wrong User Type (Non-Admin)
   */
  public function test_T007_auth_wrong_user_type()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    
    // Forge token with USER type
    $payload = [
      'id' => (string)$admin->id,
      'type' => 'USER', 
    ];
    $token = JsonWebToken::encode(
      JsonWebToken::JWTPayload($payload, false),
      env('ACCESS_TOKEN_SECRET')
    );

    $response = $this->withCookie('access_token', $token)
      ->getJson($this->meUrl);

    // Based on AdminMiddleware, this throws UnexpectedValueException(E0608)
    // Handler likely converts to 401 with E0401 or returns E0608
    // In LogoutApiTest we saw it returned E0401.
    $response->assertStatus(CommonVal::HTTP_UNAUTHORIZED);
    $this->assertEquals(Messages::E0401, $response->json('error')['messages']);
  }

  /**
   * T008: Revoked Access Token (Redis)
   */
  public function test_T008_auth_revoked_access_token()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);
    $accessToken = $cookies['access_token'];

    // Delete from Redis
    $key = CommonVal::ADMIN_TYPE . ":{$admin->id}:{$accessToken}";
    Redis::del($key);

    $response = $this->call(
      'GET',
      $this->meUrl,
      [],
      $cookies
    );

    $response->assertStatus(CommonVal::HTTP_UNAUTHORIZED);
    $this->assertEquals(Messages::E0609, $response->json('error')['messages']);
  }

  /**
   * T009: Unauthorized Route (Permission)
   */
  public function test_T009_perm_unauthorized_route()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    // Manipulate Redis Permission Table
    $permissionTableKey = CommonVal::ADMIN_TYPE . ":{$admin->id}:" . CommonVal::ADMIN_PERMISSION_TABLE;
    
    // Get current permissions
    $pathsJson = Redis::hget($permissionTableKey, 'GET');
    $paths = json_decode($pathsJson, true);

    // Remove me path
    $target = 'api/admin/credential/me';
    $paths = array_values(array_diff($paths, [$target]));

    Redis::hset($permissionTableKey, 'GET', json_encode($paths));

    $response = $this->call(
      'GET',
      $this->meUrl,
      [],
      $cookies
    );

    $response->assertStatus(CommonVal::HTTP_UNAUTHORIZED);
    $this->assertEquals(Messages::E0401, $response->json('error')['messages']);
  }

  // ======================================================================
  // Layer 3: Request Entry Analysis
  // ======================================================================

  /**
   * T010: Unexpected Query Parameters
   */
  public function test_T010_unexpected_query_params()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    $response = $this->call(
      'GET',
      $this->meUrl . '?foo=bar',
      [],
      $cookies
    );

    $response->assertStatus(CommonVal::HTTP_OK);
    $response->assertJsonStructure(['data' => ['id', 'email']]);
  }

  /**
   * T011: Unexpected Request Body
   */
  public function test_T011_unexpected_request_body()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    $response = $this->call(
      'GET',
      $this->meUrl,
      ['foo' => 'bar'],
      $cookies
    );

    $response->assertStatus(CommonVal::HTTP_OK);
    $response->assertJsonStructure(['data' => ['id', 'email']]);
  }

  // ======================================================================
  // Layer 5: Service / Business Logic Analysis
  // ======================================================================

  /**
   * T012: User Not Found in DB
   */
  public function test_T012_user_not_found_in_db()
  {
    $admin = AdminMst::factory()->create(['password' => Hash::make('password')]);
    $cookies = $this->getAuthCookies($admin);

    // Delete admin from DB but keep token valid (Redis still has it)
    $admin->delete();

    $response = $this->call(
      'GET',
      $this->meUrl,
      [],
      $cookies
    );

    $response->assertStatus(CommonVal::HTTP_UNAUTHORIZED);
    $this->assertEquals(Messages::E0401, $response->json('error')['messages']);
  }

  /**
   * T013, T014, T015: Successful Retrieval & Response Verification
   */
  public function test_T013_success_retrieval_and_verification()
  {
    $admin = AdminMst::factory()->create([
      'password' => Hash::make('password'),
      'status' => 1,
      'is_active' => true
    ]);
    $cookies = $this->getAuthCookies($admin);

    $response = $this->call(
      'GET',
      $this->meUrl,
      [],
      $cookies
    );

    $response->assertStatus(CommonVal::HTTP_OK);
    
    // Verify Structure (T014)
    $response->assertJsonStructure([
      'data' => [
        'expires_at',
        'id',
        'email',
        'status',
        'is_active'
      ],
      'error' => [
        'code',
        'messages'
      ]
    ]);

    // Verify Data Correctness (T015)
    $data = $response->json('data');
    $this->assertEquals($admin->id, $data['id']);
    $this->assertEquals($admin->email, $data['email']);
    $this->assertEquals($admin->status, $data['status']);
    $this->assertEquals($admin->is_active, $data['is_active']);
    
    // Verify expires_at is a valid timestamp in future
    $this->assertIsInt($data['expires_at']);
    $this->assertGreaterThan(time(), $data['expires_at']);
  }
}

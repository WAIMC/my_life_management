<?php

namespace App\Http\Controllers\Custom;

use App\Http\Requests\Custom\Credential\LoginRequest;
use App\Services\Custom\CredentialService;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CredentialController extends Controller
{
    public function __construct(
        protected CredentialService $credentialService
    )
    {
    }

    /**
     * Login admin account
     *
     * @param LoginRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('user_name', 'password');

        return $this->credentialService->login($credentials);
    }

    /**
     * Refresh token admin account
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function refreshToken(Request $request): JsonResponse
    {
        $accessToken = $request->cookie('access_token');
        $refreshToken = $request->cookie('refresh_token');

        return $this->credentialService->refreshToken($accessToken, $refreshToken);
    }

    /**
     * Logout admin account
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function logout(Request $request): JsonResponse
    {
        $accessToken = $request->cookie('access_token');
        $refreshToken = $request->cookie('refresh_token');

        return $this->credentialService->logout($accessToken, $refreshToken);
    }
}

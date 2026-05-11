<?php

namespace App\Http\Controllers\Custom;

use App\Http\Requests\Custom\Credential\LoginRequest;
use App\Services\Custom\CredentialService;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
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
     * @return array
     * @throws AuthorizationException
     */
    public function login(LoginRequest $request): array
    {
        return $this->credentialService->login($request);
    }

    /**
     * Refresh token admin account
     *
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function refreshToken(Request $request): array
    {
        return $this->credentialService->refreshToken($request);
    }

    /**
     * Logout admin account
     *
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function logout(Request $request): array
    {
        return $this->credentialService->logout($request);
    }

    /**
     * Get current authenticated admin user
     *
     * @param Request $request
     * @return array
     */
    public function me(Request $request): array
    {
        return $this->credentialService->me($request);
    }
}

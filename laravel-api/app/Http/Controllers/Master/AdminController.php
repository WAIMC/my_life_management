<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\Admin\AdminDeleteRequest;
use App\Http\Requests\Master\Admin\AdminListRequest;
use App\Http\Requests\Master\Admin\AdminStoreRequest;
use App\Http\Requests\Master\Admin\AdminUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Master\AdminService;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminController extends Controller
{
    public function __construct(
        private AdminService $adminService
    )
    {
    }

    /**
     * Admin list
     *
     * @param AdminListRequest $request
     * @return JsonResource
     */
    public function list(AdminListRequest $request): JsonResource
    {
        return $this->adminService->list($request->all());
    }

    /**
     * Store admin
     *
     * @param AdminStoreRequest $request
     * @return int
     */
    public function store(AdminStoreRequest $request): int
    {
        return $this->adminService->store($request->all());
    }

    /**
     * Update account
     *
     * @param AdminUpdateRequest $request
     * @param string $id
     * @return int
     */
    public function update(AdminUpdateRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->adminService->update($payload);
    }

    /**
     * Delete account
     *
     * @param AdminDeleteRequest $request
     * @return void
     */
    public function delete(AdminDeleteRequest $request): void
    {
        $this->adminService->delete($request->all());
    }

    /**
     * Login admin account
     *
     * @param Request $request
     * @return array
     * @throws AuthorizationException
     */
    public function login(Request $request): array
    {
        $credentials = $request->only('user_name', 'password');
        return $this->adminService->login($credentials);
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
        return $this->adminService->refreshToken($request->bearerToken());
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
        return $this->adminService->logout($request['refresh_token']);
    }
}

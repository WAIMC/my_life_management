<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\Admin\AdminMstDeleteRequest;
use App\Http\Requests\Master\Admin\AdminMstListRequest;
use App\Http\Requests\Master\Admin\AdminMstStoreRequest;
use App\Http\Requests\Master\Admin\AdminMstUpdateRequest;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Master\AdminMstService;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminMstController extends Controller
{
    public function __construct(
        private AdminMstService $adminService
    )
    {
    }

    /**
     * AdminMst list
     *
     * @param AdminMstListRequest $request
     * @return JsonResource
     */
    public function list(AdminMstListRequest $request): JsonResource
    {
        return $this->adminService->list($request->all());
    }

    /**
     * Store admin
     *
     * @param AdminMstStoreRequest $request
     * @return int
     */
    public function store(AdminMstStoreRequest $request): int
    {
        return $this->adminService->store($request->all());
    }

    /**
     * Update account
     *
     * @param AdminMstUpdateRequest $request
     * @param string $id
     * @return int
     */
    public function update(AdminMstUpdateRequest $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;

        return $this->adminService->update($payload);
    }

    /**
     * Delete account
     *
     * @param AdminMstDeleteRequest $request
     * @return void
     */
    public function delete(AdminMstDeleteRequest $request): void
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

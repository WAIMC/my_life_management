<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\User\DeleteUserMgmtRequest;
use App\Http\Requests\Management\User\StoreUserMgmtRequest;
use App\Http\Requests\Management\User\UpdateUserMgmtRequest;
use App\Http\Requests\Management\User\UserMgmtListRequest;
use App\Http\Resources\Management\UserMgmtResource;
use App\Services\Management\UserMgmtService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserMgmtController extends Controller
{
    protected UserMgmtService $userMgmtService;

    /**
     * Constructor
     *
     * @param UserMgmtService $userMgmtService
     */
    public function __construct(UserMgmtService $userMgmtService)
    {
        $this->userMgmtService = $userMgmtService;
    }

    /**
     * Get a listing of users
     *
     * @param UserMgmtListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(UserMgmtListRequest $request): AnonymousResourceCollection
    {
        return $this->userMgmtService->getList($request->validated());
    }

    /**
     * Store a newly created user in storage.
     *
     * @param StoreUserMgmtRequest $request
     * @return UserMgmtResource
     * @throws Exception
     */
    public function store(StoreUserMgmtRequest $request): UserMgmtResource
    {
        return $this->userMgmtService->create($request->validated());
    }

    /**
     * Display the specified user.
     *
     * @param int $id
     * @return UserMgmtResource
     * @throws Exception
     */
    public function show(int $id): UserMgmtResource
    {
        return $this->userMgmtService->getById($id);
    }

    /**
     * Update the specified user in storage.
     *
     * @param UpdateUserMgmtRequest $request
     * @param int $id
     * @return UserMgmtResource
     * @throws Exception
     */
    public function update(UpdateUserMgmtRequest $request, int $id): UserMgmtResource
    {
        return $this->userMgmtService->update($request->validated(), $id);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param DeleteUserMgmtRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(DeleteUserMgmtRequest $request): JsonResponse
    {
        $this->userMgmtService->delete($request->validated()['id']);
        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Get users by department ID
     *
     * @param int $departmentId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function getByDepartmentId(int $departmentId): AnonymousResourceCollection
    {
        return $this->userMgmtService->getByDepartmentId($departmentId);
    }

    /**
     * Get users by role ID
     *
     * @param int $roleId
     * @return AnonymousResourceCollection
     * @throws Exception
     */
    public function getByRoleId(int $roleId): AnonymousResourceCollection
    {
        return $this->userMgmtService->getByRoleId($roleId);
    }

    /**
     * Get user by email
     *
     * @param string $email
     * @return UserMgmtResource
     * @throws Exception
     */
    public function getByEmail(string $email): UserMgmtResource
    {
        return $this->userMgmtService->getByEmail($email);
    }

    /**
     * Get user by username
     *
     * @param string $userName
     * @return UserMgmtResource
     * @throws Exception
     */
    public function getByUserName(string $userName): UserMgmtResource
    {
        return $this->userMgmtService->getByUserName($userName);
    }
}
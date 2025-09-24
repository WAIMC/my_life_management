<?php

namespace App\Services\Management;

use App\Http\Resources\Management\UserMgmtResource;
use App\Interfaces\Management\UserMgmtInterface;
use App\Interfaces\Master\DepartmentMstInterface;
use App\Interfaces\Master\RoleMstInterface;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Hash;

class UserMgmtService
{
    /**
     * @var UserMgmtInterface
     */
    protected $userMgmtRepository;

    /**
     * @var RoleMstInterface
     */
    protected $roleMstRepository;

    /**
     * @var DepartmentMstInterface
     */
    protected $departmentMstRepository;

    /**
     * UserMgmtService constructor.
     *
     * @param UserMgmtInterface $userMgmtRepository
     * @param RoleMstInterface $roleMstRepository
     * @param DepartmentMstInterface $departmentMstRepository
     */
    public function __construct(
        UserMgmtInterface $userMgmtRepository,
        RoleMstInterface $roleMstRepository,
        DepartmentMstInterface $departmentMstRepository
    ) {
        $this->userMgmtRepository = $userMgmtRepository;
        $this->roleMstRepository = $roleMstRepository;
        $this->departmentMstRepository = $departmentMstRepository;
    }

    /**
     * Get list of users
     *
     * @param array $payload
     * @return AnonymousResourceCollection
     */
    public function getList(array $payload): AnonymousResourceCollection
    {
        $data = $this->userMgmtRepository->getList($payload);
        return UserMgmtResource::collection($data);
    }

    /**
     * Get user by ID
     *
     * @param int $id
     * @return UserMgmtResource
     * @throws Exception
     */
    public function getById(int $id): UserMgmtResource
    {
        $user = $this->userMgmtRepository->getById($id);
        if (!$user) {
            throw new Exception('User not found');
        }
        return new UserMgmtResource($user);
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
        $user = $this->userMgmtRepository->getByEmail($email);
        if (!$user) {
            throw new Exception('User not found');
        }
        return new UserMgmtResource($user);
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
        $user = $this->userMgmtRepository->getByUserName($userName);
        if (!$user) {
            throw new Exception('User not found');
        }
        return new UserMgmtResource($user);
    }

    /**
     * Create user
     *
     * @param array $payload
     * @return UserMgmtResource
     * @throws Exception
     */
    public function create(array $payload): UserMgmtResource
    {
        // Check if role exists
        $role = $this->roleMstRepository->getById($payload['role_id']);
        if (!$role) {
            throw new Exception('Role not found');
        }

        // Check if department exists
        $department = $this->departmentMstRepository->getById($payload['department_id']);
        if (!$department) {
            throw new Exception('Department not found');
        }

        // Check if email already exists
        $existingEmail = $this->userMgmtRepository->getByEmail($payload['email']);
        if ($existingEmail) {
            throw new Exception('Email already exists');
        }

        // Check if username already exists
        $existingUserName = $this->userMgmtRepository->getByUserName($payload['user_name']);
        if ($existingUserName) {
            throw new Exception('Username already exists');
        }

        // Hash password if it exists
        if (isset($payload['password'])) {
            $payload['password'] = Hash::make($payload['password']);
        }

        $user = $this->userMgmtRepository->create($payload);
        return new UserMgmtResource($user);
    }

    /**
     * Update user
     *
     * @param array $payload
     * @param int $id
     * @return UserMgmtResource
     * @throws Exception
     */
    public function update(array $payload, int $id): UserMgmtResource
    {
        // Check if user exists
        $user = $this->userMgmtRepository->getById($id);
        if (!$user) {
            throw new Exception('User not found');
        }

        // Check if role exists when role_id is provided
        if (isset($payload['role_id'])) {
            $role = $this->roleMstRepository->getById($payload['role_id']);
            if (!$role) {
                throw new Exception('Role not found');
            }
        }

        // Check if department exists when department_id is provided
        if (isset($payload['department_id'])) {
            $department = $this->departmentMstRepository->getById($payload['department_id']);
            if (!$department) {
                throw new Exception('Department not found');
            }
        }

        // Check if email already exists (if email is being changed)
        if (isset($payload['email']) && $payload['email'] !== $user->email) {
            $existingEmail = $this->userMgmtRepository->getByEmail($payload['email']);
            if ($existingEmail) {
                throw new Exception('Email already exists');
            }
        }

        // Check if username already exists (if username is being changed)
        if (isset($payload['user_name']) && $payload['user_name'] !== $user->user_name) {
            $existingUserName = $this->userMgmtRepository->getByUserName($payload['user_name']);
            if ($existingUserName) {
                throw new Exception('Username already exists');
            }
        }

        // Hash password if it exists
        if (isset($payload['password'])) {
            $payload['password'] = Hash::make($payload['password']);
        }

        $updatedUser = $this->userMgmtRepository->update($payload, $id);
        return new UserMgmtResource($updatedUser);
    }

    /**
     * Delete user
     *
     * @param int $id
     * @return bool
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        // Check if user exists
        $user = $this->userMgmtRepository->getById($id);
        if (!$user) {
            throw new Exception('User not found');
        }

        return $this->userMgmtRepository->delete($id);
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
        // Check if department exists
        $department = $this->departmentMstRepository->getById($departmentId);
        if (!$department) {
            throw new Exception('Department not found');
        }

        $users = $this->userMgmtRepository->getByDepartmentId($departmentId);
        return UserMgmtResource::collection($users);
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
        // Check if role exists
        $role = $this->roleMstRepository->getById($roleId);
        if (!$role) {
            throw new Exception('Role not found');
        }

        $users = $this->userMgmtRepository->getByRoleId($roleId);
        return UserMgmtResource::collection($users);
    }
}
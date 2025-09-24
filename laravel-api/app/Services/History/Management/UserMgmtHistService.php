<?php

namespace App\Services\History\Management;

use App\Interfaces\History\Management\UserMgmtHistInterface;
use App\Interfaces\Management\UserMgmtInterface;
use App\Interfaces\Master\AdminMstInterface;
use Illuminate\Support\Facades\Auth;

class UserMgmtHistService
{
    /**
     * @var UserMgmtHistInterface
     */
    protected $userMgmtHistRepository;

    /**
     * @var UserMgmtInterface
     */
    protected $userMgmtRepository;

    /**
     * @var AdminMstInterface
     */
    protected $adminMstRepository;

    /**
     * UserMgmtHistService constructor.
     *
     * @param UserMgmtHistInterface $userMgmtHistRepository
     * @param UserMgmtInterface $userMgmtRepository
     * @param AdminMstInterface $adminMstRepository
     */
    public function __construct(
        UserMgmtHistInterface $userMgmtHistRepository,
        UserMgmtInterface $userMgmtRepository,
        AdminMstInterface $adminMstRepository
    ) {
        $this->userMgmtHistRepository = $userMgmtHistRepository;
        $this->userMgmtRepository = $userMgmtRepository;
        $this->adminMstRepository = $adminMstRepository;
    }

    /**
     * Get all user history records
     *
     * @param array $payload
     * @return mixed
     */
    public function getAllUserMgmtHist(array $payload = [])
    {
        return $this->userMgmtHistRepository->getAll($payload);
    }

    /**
     * Get user history record by ID
     *
     * @param int $id
     * @return mixed
     */
    public function getUserMgmtHistById(int $id)
    {
        $userMgmtHist = $this->userMgmtHistRepository->getById($id);
        
        if (!$userMgmtHist) {
            throw new \Exception('User history record not found');
        }

        return $userMgmtHist;
    }

    /**
     * Get user history records by user management ID
     *
     * @param int $userMgmtId
     * @param array $payload
     * @return mixed
     */
    public function getUserMgmtHistByUserMgmtId(int $userMgmtId, array $payload = [])
    {
        $user = $this->userMgmtRepository->getById($userMgmtId);

        if (!$user) {
            throw new \Exception('User not found');
        }

        return $this->userMgmtHistRepository->getByUserMgmtId($userMgmtId, $payload);
    }

    /**
     * Create a new user history record
     *
     * @param array $payload
     * @return mixed
     */
    public function createUserMgmtHist(array $payload)
    {
        // Check if user exists
        $user = $this->userMgmtRepository->getById($payload['user_mgmt_id']);
        if (!$user) {
            throw new \Exception('User not found');
        }

        // Get the current authenticated admin ID or use the provided author_id
        if (!isset($payload['author_id'])) {
            $payload['author_id'] = Auth::guard('admin')->id();
        } else {
            // Check if author exists
            $admin = $this->adminMstRepository->getById($payload['author_id']);
            if (!$admin) {
                throw new \Exception('Author admin not found');
            }
        }

        return $this->userMgmtHistRepository->create($payload);
    }
}
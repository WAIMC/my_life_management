<?php

namespace App\Http\Controllers\History\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Management\User\StoreUserMgmtHistRequest;
use App\Http\Requests\History\Management\User\UserMgmtHistByUserRequest;
use App\Http\Requests\History\Management\User\UserMgmtHistDetailRequest;
use App\Http\Requests\History\Management\User\UserMgmtHistListRequest;
use App\Http\Resources\History\Management\UserMgmtHistResource;
use App\Services\History\Management\UserMgmtHistService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserMgmtHistController extends Controller
{
    /**
     * @var UserMgmtHistService
     */
    protected $userMgmtHistService;

    /**
     * UserMgmtHistController constructor.
     *
     * @param UserMgmtHistService $userMgmtHistService
     */
    public function __construct(UserMgmtHistService $userMgmtHistService)
    {
        $this->userMgmtHistService = $userMgmtHistService;
    }

    /**
     * Get all user history records
     *
     * @param UserMgmtHistListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(UserMgmtHistListRequest $request)
    {
        $userMgmtHists = $this->userMgmtHistService->getAllUserMgmtHist($request->validated());
        return UserMgmtHistResource::collection($userMgmtHists);
    }

    /**
     * Get user history record by ID
     *
     * @param UserMgmtHistDetailRequest $request
     * @return UserMgmtHistResource
     */
    public function show(UserMgmtHistDetailRequest $request)
    {
        $userMgmtHist = $this->userMgmtHistService->getUserMgmtHistById($request->id);
        return new UserMgmtHistResource($userMgmtHist);
    }

    /**
     * Get user history records by user management ID
     *
     * @param UserMgmtHistByUserRequest $request
     * @return AnonymousResourceCollection
     */
    public function getByUserId(UserMgmtHistByUserRequest $request)
    {
        $validated = $request->validated();
        $userMgmtHists = $this->userMgmtHistService->getUserMgmtHistByUserMgmtId(
            $validated['user_mgmt_id'],
            $validated
        );
        return UserMgmtHistResource::collection($userMgmtHists);
    }

    /**
     * Store a newly created user history record
     *
     * @param StoreUserMgmtHistRequest $request
     * @return UserMgmtHistResource
     */
    public function store(StoreUserMgmtHistRequest $request)
    {
        $userMgmtHist = $this->userMgmtHistService->createUserMgmtHist($request->validated());
        return new UserMgmtHistResource($userMgmtHist);
    }
}

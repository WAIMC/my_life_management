<?php

namespace App\Http\Controllers\History\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\History\Master\Role\CreateRoleMstHistRequest;
use App\Http\Requests\History\Master\Role\ListRoleMstHistRequest;
use App\Http\Resources\History\Master\RoleMstHistResource;
use App\Services\History\Master\RoleMstHistService;
use Illuminate\Http\Response;

class RoleMstHistController extends Controller
{
    /**
     * @var RoleMstHistService
     */
    protected RoleMstHistService $roleHistoryService;

    /**
     * RoleMstHistController constructor.
     */
    public function __construct(RoleMstHistService $roleHistoryService)
    {
        $this->roleHistoryService = $roleHistoryService;
    }

    /**
     * Display a listing of the role histories.
     *
     * @param ListRoleMstHistRequest $request
     * @return RoleMstHistCollection
     */
    public function index(ListRoleMstHistRequest $request): RoleMstHistCollection
    {
        $params = $request->validated();
        $roleHistories = $this->roleHistoryService->getAllRoleHistories($params);

        return new RoleMstHistCollection($roleHistories);
    }

    /**
     * Store a newly created role history in storage.
     *
     * @param CreateRoleMstHistRequest $request
     * @return RoleMstHistResource
     * @throws \Exception
     */
    public function store(CreateRoleMstHistRequest $request)
    {
        $data = $request->validated();
        $roleHistory = $this->roleHistoryService->createRoleHistory($data);

        return new RoleMstHistResource($roleHistory);
    }

    /**
     * Display the specified role history.
     *
     * @param int $id
     * @return RoleMstHistResource|Response
     */
    public function show($id)
    {
        $roleHistory = $this->roleHistoryService->getRoleHistoryById($id);

        if (!$roleHistory) {
            return response()->json([
                'message' => 'Role history not found'
            ], 404);
        }

        return new RoleMstHistResource($roleHistory);
    }

    /**
     * Get role histories by role ID.
     *
     * @param int $roleId
     * @return Response
     */
    public function getByRoleId($roleId)
    {
        $roleHistories = $this->roleHistoryService->getRoleHistoriesByRoleId($roleId);

        return response()->json([
            'data' => RoleMstHistResource::collection($roleHistories)
        ]);
    }

    /**
     * Get role histories by author ID.
     *
     * @param int $authorId
     * @return Response
     */
    public function getByAuthorId($authorId)
    {
        $roleHistories = $this->roleHistoryService->getRoleHistoriesByAuthorId($authorId);

        return response()->json([
            'data' => RoleMstHistResource::collection($roleHistories)
        ]);
    }

    /**
     * Get role histories by action type.
     *
     * @param int $action
     * @return Response
     */
    public function getByAction($action)
    {
        $roleHistories = $this->roleHistoryService->getRoleHistoriesByAction($action);

        return response()->json([
            'data' => RoleMstHistResource::collection($roleHistories)
        ]);
    }
}

<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\Social\SocialMgmtListRequest;
use App\Http\Requests\Management\Social\SocialMgmtStoreRequest;
use App\Http\Requests\Management\Social\SocialMgmtUpdateRequest;
use App\Http\Resources\Management\SocialMgmtResource;
use App\Services\Management\SocialMgmtService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class SocialMgmtController extends Controller
{
    /**
     * @var SocialMgmtService
     */
    protected SocialMgmtService $socialService;

    /**
     * SocialMgmtController constructor.
     *
     * @param SocialMgmtService $socialService
     */
    public function __construct(SocialMgmtService $socialService)
    {
        $this->socialService = $socialService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param SocialMgmtListRequest $request
     * @return AnonymousResourceCollection
     */
    public function index(SocialMgmtListRequest $request): AnonymousResourceCollection
    {
        return $this->socialService->getList($request->validated());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SocialMgmtStoreRequest $request
     * @return SocialMgmtResource
     */
    public function store(SocialMgmtStoreRequest $request): SocialMgmtResource
    {
        return $this->socialService->create($request->validated());
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return SocialMgmtResource|JsonResponse
     */
    public function show(int $id): SocialMgmtResource|JsonResponse
    {
        $social = $this->socialService->getById($id);

        if (!$social) {
            return response()->json([
                'message' => 'Social not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return $social;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param SocialMgmtUpdateRequest $request
     * @param int $id
     * @return SocialMgmtResource|JsonResponse
     */
    public function update(SocialMgmtUpdateRequest $request, int $id): SocialMgmtResource|JsonResponse
    {
        $social = $this->socialService->update($request->validated(), $id);

        if (!$social) {
            return response()->json([
                'message' => 'Social not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return $social;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->socialService->delete($id);

        if (!$deleted) {
            return response()->json([
                'message' => 'Social not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'message' => 'Social deleted successfully'
        ], Response::HTTP_OK);
    }
}

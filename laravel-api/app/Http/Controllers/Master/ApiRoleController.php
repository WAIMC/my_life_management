<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\ApiRole\ApiRoleListRequest;
use App\Http\Requests\Master\ApiRole\ApiRoleUpdateRequest;
use App\Models\Master\Api;
use App\Constants\Messages;
use App\Constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use App\Http\Controllers\Controller;
use App\Services\Master\ApiRoleService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ApiRoleController extends Controller
{
    public function __construct(
        private ApiRoleService $apiRoleService
    )
    {
    }

    /**
     * Api role list
     *
     * @param ApiRoleListRequest $request
     * @return JsonResource
     */
    public function list(ApiRoleListRequest $request): JsonResource
    {
        return $this->apiRoleService->list($request->all());
    }

    /**
     * Update api role
     *
     * @param ApiRoleUpdateRequest $request
     * @return bool
     * @throws ValidationException
     */
    public function update(ApiRoleUpdateRequest $request): bool
    {
        return $this->apiRoleService->update($request->all());
    }
}

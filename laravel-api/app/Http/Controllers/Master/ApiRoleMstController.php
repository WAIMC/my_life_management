<?php

namespace App\Http\Controllers\Master;

use App\Http\Requests\Master\ApiRole\ApiRoleMstListRequest;
use App\Http\Requests\Master\ApiRole\ApiRoleMstUpdateRequest;
use App\Models\Master\ApiMst;
use App\Constants\Messages;
use App\Constants\CommonVal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use App\Http\Controllers\Controller;
use App\Services\Master\ApiRoleMstService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class ApiRoleMstController extends Controller
{
    public function __construct(
        private ApiRoleMstService $apiRoleService
    )
    {
    }

    /**
     * ApiMst role list
     *
     * @param ApiRoleMstListRequest $request
     * @return JsonResource
     */
    public function list(ApiRoleMstListRequest $request): JsonResource
    {
        return $this->apiRoleService->list($request->all());
    }

    /**
     * Update api role
     *
     * @param ApiRoleMstUpdateRequest $request
     * @return bool
     * @throws ValidationException
     */
    public function update(ApiRoleMstUpdateRequest $request): bool
    {
        return $this->apiRoleService->update($request->all());
    }
}

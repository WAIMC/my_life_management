<?php

namespace App\Services\Master;

use App\Services\BaseJunctionService;
use App\Interfaces\Master\ApiRoleMstInterface;
use Illuminate\Http\Resources\Json\JsonResource;
use LogicException;
use App\Constants\Messages;
use App\Constants\CommonVal;
use App\Http\Resources\Master\ApiRoleMstResource;

class ApiRoleMstService extends BaseJunctionService
{
    public function __construct(
        protected ApiRoleMstInterface $apiRoleMst
    )
    {
    }

    /**
     * Get api role mst list
     *
     * @param array $payload
     * @return JsonResource
     */
    public function list(array $payload): JsonResource
    {
        $list = $this->apiRoleMst->list($payload);

        return ApiRoleMstResource::collection($list);
    }

    /**
     * Update api role mst
     *
     * @param array $payload
     * @return bool
     */
    public function update(array $payload): bool
    {
        // Don't allow editing of personal role without role admin
        if ($this->apiRoleMst->isMyRole($payload)) {
            throw new LogicException(Messages::E0018, CommonVal::HTTP_UNPROCESSABLE_CONTENT);
        }

        // Delete api role mst
        if ($payload['delete']) {
            $this->validateExistence(
                $payload['delete'],
                fn($values) => $this->apiRoleMst->getApiRoleMstId($values),
                'api_role_id',
                'api_role_mst'
            );
            $this->apiRoleMst->executeDelete($payload['delete']);
        }

        // Insert api role mst
        if ($payload['insert']) {
            $this->validateNonExistence(
                $payload['insert'],
                fn($values) => $this->apiRoleMst->getApiRoleMstId($values),
                'api_role_id',
                'api_role_mst'
            );
            $this->apiRoleMst->executeStore($payload['insert']);
        }

        return true;
    }
}

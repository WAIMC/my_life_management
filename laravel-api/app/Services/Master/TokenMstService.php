<?php

namespace App\Services\Master;

use App\Services\BaseService;
use App\Interfaces\Master\TokenMstInterface;
use App\Enums\ActionType;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Master\TokenMstResource;

class TokenMstService extends BaseService
{
  public function __construct(
    protected TokenMstInterface $tokenMst
  ) {}

  protected function getHistoryRepository()
  {
    return null;
  }

  protected function getHistoryForeignKey(): string
  {
    return 'token_mst_id';
  }

  /**
   * Get token mst list
   *
   * @param array $payload
   * @return JsonResource
   */
  public function list(array $payload): JsonResource
  {
    $list = $this->tokenMst->list($payload);

    return TokenMstResource::collection($list);
  }

  /**
   * Store token mst
   *
   * @param array $payload
   * @return int
   */
  public function store(array $payload): int
  {
    $id = $this->tokenMst->executeStore($payload);
    return $id;
  }

  /**
   * Update token mst
   *
   * @param array $payload
   * @return int
   */
  public function update(array $payload): int
  {
    $id = $payload['id'];
    $affected = $this->tokenMst->executeUpdate($payload);
    return $affected;
  }

  /**
   * Delete token mst
   *
   * @param array $payload
   * @return void
   */
  public function delete(array $payload): void
  {
    if (!isset($payload['ids']) || !is_array($payload['ids'])) {
      $this->tokenMst->executeDelete($payload['ids'] ?? []);
      return;
    }

    $this->tokenMst->executeDelete($payload['ids']);
  }
}

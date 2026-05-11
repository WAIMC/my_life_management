<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\UserMgmt\ListUserMgmtRequest;
use App\Http\Requests\Management\UserMgmt\StoreUserMgmtRequest;
use App\Http\Requests\Management\UserMgmt\UpdateUserMgmtRequest;
use App\Http\Requests\Management\UserMgmt\DeleteUserMgmtRequest;
use App\Services\Management\UserMgmtService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMgmtController extends Controller
{
  public function __construct(
    protected UserMgmtService $userMgmt
  ) {}

  /**
   * UserMgmt list
   *
   * @param ListUserMgmtRequest $request
   * @return JsonResource
   */
  public function list(ListUserMgmtRequest $request): JsonResource
  {
    // dump($request->all());
    return $this->userMgmt->list($request->all());
  }

  /**
   * Store user mgmt
   *
   * @param StoreUserMgmtRequest $request
   * @return int
   */
  public function store(StoreUserMgmtRequest $request): int
  {
    return $this->userMgmt->store($request->all());
  }

  /**
   * Update user mgmt
   *
   * @param UpdateUserMgmtRequest $request
   * @param string $id
   * @return int
   */
  public function update(UpdateUserMgmtRequest $request, string $id): int
  {
    $payload = $request->all();
    $payload['id'] = $id;

    return $this->userMgmt->update($payload);
  }

  /**
   * Delete user mgmt
   *
   * @param DeleteUserMgmtRequest $request
   * @return void
   */
  public function delete(DeleteUserMgmtRequest $request): void
  {
    $this->userMgmt->delete($request->all());
  }
}

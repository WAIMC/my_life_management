<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\MediaMgmt\ListMediaMgmtRequest;
use App\Http\Requests\Management\MediaMgmt\StoreMediaMgmtRequest;
use App\Http\Requests\Management\MediaMgmt\UpdateMediaMgmtRequest;
use App\Http\Requests\Management\MediaMgmt\DeleteMediaMgmtRequest;
use App\Services\Management\MediaMgmtService;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaMgmtController extends Controller
{
    public function __construct(
        protected MediaMgmtService $mediaMgmt
    ) {}

    /**
     * List media (files and folders)
     */
    public function list(ListMediaMgmtRequest $request): array
    {
        return $this->mediaMgmt->list($request->validated());
    }

    /**
     * Store media (file upload or folder creation)
     */
    public function store(StoreMediaMgmtRequest $request): int
    {
        return $this->mediaMgmt->store($request->validated(), $request->file('file'));
    }

    /**
     * Update media (rename or move)
     */
    public function update(UpdateMediaMgmtRequest $request, string $id): int
    {
        $payload = $request->validated();
        $payload['id'] = (int)$id;
        return $this->mediaMgmt->update($payload);
    }

    /**
     * Delete media (file or folder)
     */
    public function delete(DeleteMediaMgmtRequest $request, string $id): void
    {
        // Support both single delete (from route param) and batch delete (from request body)
        $ids = $request->has('ids') ? $request->validated()['ids'] : [(int)$id];
        $this->mediaMgmt->delete(['ids' => $ids]);
    }
}

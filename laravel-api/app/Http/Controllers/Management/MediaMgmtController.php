<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Http\Requests\Management\MediaMgmt\UploadFileRequest;
use App\Http\Requests\Management\MediaMgmt\CreateFolderRequest;
use App\Services\Management\MediaMgmtService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaMgmtController extends Controller
{
    public function __construct(
        protected MediaMgmtService $mediaMgmt
    ) {}

    /**
     * List media (files and folders)
     */
    public function list(Request $request): JsonResource
    {
        return $this->mediaMgmt->list($request->all());
    }

    /**
     * Upload file
     */
    public function uploadFile(UploadFileRequest $request): int
    {
        return $this->mediaMgmt->uploadFile($request->file('file'), $request->all());
    }

    /**
     * Create folder
     */
    public function createFolder(CreateFolderRequest $request): int
    {
        return $this->mediaMgmt->createFolder($request->all());
    }

    /**
     * Rename (file or folder)
     */
    public function rename(Request $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;
        return $this->mediaMgmt->rename($payload);
    }

    /**
     * Move (file or folder)
     */
    public function move(Request $request, string $id): int
    {
        $payload = $request->all();
        $payload['id'] = $id;
        return $this->mediaMgmt->move($payload);
    }

    /**
     * Delete (file or folder)
     */
    public function delete(Request $request): void
    {
        $this->mediaMgmt->delete($request->all());
    }
}

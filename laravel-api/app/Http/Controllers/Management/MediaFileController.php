<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\MediaFile\UploadMediaFileRequest;
use App\Http\Requests\Management\MediaFile\ListMediaFileRequest;
use App\Http\Requests\Management\MediaFile\RenameMediaFileRequest;
use App\Http\Requests\Management\MediaFile\MoveMediaFileRequest;
use App\Http\Requests\Management\MediaFile\DeleteMediaFileRequest;
use App\Services\Management\MediaFileService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class MediaFileController extends Controller
{
    public function __construct(
        protected MediaFileService $mediaFileService
    ) {
    }

    /**
     * Upload file
     *
     * @param UploadMediaFileRequest $request
     * @return array
     */
    public function upload(UploadMediaFileRequest $request): array
    {
        $payload = $request->validated();
        $payload['admin_mst_id'] = Auth::id();

        return $this->mediaFileService->upload($payload);
    }

    /**
     * List media files
     *
     * @param ListMediaFileRequest $request
     * @return JsonResource
     */
    public function list(ListMediaFileRequest $request): JsonResource
    {
        return $this->mediaFileService->list($request->validated());
    }

    /**
     * Get file metadata
     *
     * @param string $id
     * @return array
     */
    public function show(string $id): array
    {
        return $this->mediaFileService->getFile((int)$id);
    }

    /**
     * Stream file content for viewing
     *
     * @param string $id
     * @return Response
     */
    public function view(string $id): Response
    {
        $fileData = $this->mediaFileService->downloadFile((int)$id);

        return response($fileData['content'])
            ->header('Content-Type', $fileData['mime_type'])
            ->header('Content-Disposition', 'inline; filename="' . $fileData['filename'] . '"');
    }

    /**
     * Download file
     *
     * @param string $id
     * @return Response
     */
    public function download(string $id): Response
    {
        $fileData = $this->mediaFileService->downloadFile((int)$id);

        return response($fileData['content'])
            ->header('Content-Type', $fileData['mime_type'])
            ->header('Content-Disposition', 'attachment; filename="' . $fileData['filename'] . '"');
    }

    /**
     * Rename file
     *
     * @param RenameMediaFileRequest $request
     * @param string $id
     * @return int
     */
    public function rename(RenameMediaFileRequest $request, string $id): int
    {
        $payload = $request->validated();
        $payload['id'] = (int)$id;

        return $this->mediaFileService->rename($payload);
    }

    /**
     * Move file to different folder
     *
     * @param MoveMediaFileRequest $request
     * @param string $id
     * @return int
     */
    public function move(MoveMediaFileRequest $request, string $id): int
    {
        $payload = $request->validated();
        $payload['id'] = (int)$id;

        return $this->mediaFileService->move($payload);
    }

    /**
     * Delete file(s)
     *
     * @param DeleteMediaFileRequest $request
     * @return void
     */
    public function delete(DeleteMediaFileRequest $request): void
    {
        $this->mediaFileService->delete($request->validated());
    }
}

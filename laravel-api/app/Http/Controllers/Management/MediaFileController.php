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
        try {
            $payload = $request->validated();
            // TODO: Replace with Auth::id() when authentication is implemented
            $payload['admin_mst_id'] = 1; // Auth::id();

            return $this->mediaFileService->upload($payload);
        } catch (\App\Exceptions\GoogleDriveAuthException $e) {
            \Log::error('Google Drive authentication failed during file upload', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new \Exception('Google Drive authentication failed. Please check your credentials configuration.', 401);
        } catch (\App\Exceptions\GoogleDriveQuotaExceededException $e) {
            \Log::error('Google Drive quota exceeded during file upload', [
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Google Drive storage quota exceeded. Please free up space or upgrade your plan.', 507);
        } catch (\App\Exceptions\GoogleDrivePermissionException $e) {
            \Log::error('Google Drive permission denied during file upload', [
                'error' => $e->getMessage()
            ]);
            throw new \Exception('Permission denied. Please check Google Drive folder permissions.', 403);
        } catch (\Exception $e) {
            \Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'file_name' => $request->file('file')?->getClientOriginalName(),
                'file_size' => $request->file('file')?->getSize(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Check for common configuration issues
            if (str_contains($e->getMessage(), 'credentials not found') || 
                str_contains($e->getMessage(), 'credentials_path')) {
                throw new \Exception('Google Drive is not configured. Please upload credentials via Admin Panel > Google Drive Configuration.', 500);
            }
            
            if (str_contains($e->getMessage(), 'root_folder_id') || 
                str_contains($e->getMessage(), 'your_google_drive_root_folder_id_here')) {
                throw new \Exception('Google Drive root folder is not configured. Please set GOOGLE_DRIVE_ROOT_FOLDER_ID in your configuration.', 500);
            }
            
            throw new \Exception('File upload failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * List media files
     *
     * @param ListMediaFileRequest $request
     * @return JsonResource
     */
    public function list(ListMediaFileRequest $request): JsonResource
    {
        try {
            return $this->mediaFileService->list($request->validated());
        } catch (\Exception $e) {
            \Log::error('MediaFileController::list error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
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

    public function delete(DeleteMediaFileRequest $request): void
    {
        $this->mediaFileService->delete($request->validated());
    }

    /**
     * Create folder
     *
     * @param CreateFolderRequest $request
     * @return array
     */
    public function createFolder(\App\Http\Requests\Management\MediaFile\CreateFolderRequest $request): array
    {
        try {
            $payload = $request->validated();
            // TODO: Replace with Auth::id() when authentication is implemented
            $payload['admin_mst_id'] = 1; // Auth::id();

            return $this->mediaFileService->createFolder($payload);
        } catch (\App\Exceptions\GoogleDriveAuthException $e) {
            \Log::error('Google Drive authentication failed during folder creation', [
                'error' => $e->getMessage(),
                'folder_name' => $request->input('name')
            ]);
            throw new \Exception('Google Drive authentication failed. Please check your credentials configuration.', 401);
        } catch (\Exception $e) {
            \Log::error('Folder creation failed', [
                'error' => $e->getMessage(),
                'folder_name' => $request->input('name'),
                'folder_path' => $request->input('folder_path'),
                'trace' => $e->getTraceAsString()
            ]);
            
            if (str_contains($e->getMessage(), 'credentials not found')) {
                throw new \Exception('Google Drive is not configured. Please upload credentials via Admin Panel.', 500);
            }
            
            throw new \Exception('Folder creation failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * List folders
     *
     * @param ListMediaFileRequest $request
     * @return JsonResource
     */
    public function listFolders(ListMediaFileRequest $request): JsonResource
    {
        return $this->mediaFileService->listFolders($request->validated());
    }

    /**
     * Copy files
     *
     * @param CopyMediaFileRequest $request
     * @return array
     */
    public function copy(\App\Http\Requests\Management\MediaFile\CopyMediaFileRequest $request): array
    {
        $payload = $request->validated();
        // TODO: Replace with Auth::id() when authentication is implemented
        $payload['admin_mst_id'] = 1; // Auth::id();

        return $this->mediaFileService->copyFiles($payload);
    }
}

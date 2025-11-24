<?php

namespace App\Http\Controllers\Management;

use App\Http\Requests\Management\GoogleDriveConfig\UploadCredentialsRequest;
use App\Http\Requests\Management\GoogleDriveConfig\ListGoogleDriveConfigRequest;
use App\Http\Requests\Management\GoogleDriveConfig\DeleteGoogleDriveConfigRequest;
use App\Services\Management\GoogleDriveConfigService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Resources\Json\JsonResource;

class GoogleDriveConfigController extends Controller
{
    public function __construct(
        protected GoogleDriveConfigService $googleDriveConfigService
    ) {
    }

    /**
     * Upload credentials file
     */
    public function upload(UploadCredentialsRequest $request): array
    {
        return $this->googleDriveConfigService->upload($request->all());
    }

    /**
     * List configurations
     */
    public function list(ListGoogleDriveConfigRequest $request): JsonResource
    {
        return $this->googleDriveConfigService->list($request->validated());
    }

    /**
     * Get active configuration
     */
    public function getActive()
    {
        $config = $this->googleDriveConfigService->getActive();
        
        if (!$config) {
            return response()->json(['message' => 'No active configuration'], 404);
        }

        return new \App\Http\Resources\Management\GoogleDriveConfigResource($config);
    }

    /**
     * Activate configuration
     */
    public function activate(string $id): array
    {
        $this->googleDriveConfigService->activate((int)$id);
        
        return [
            'message' => 'Configuration activated successfully',
        ];
    }

    /**
     * Delete configuration(s)
     */
    public function delete(DeleteGoogleDriveConfigRequest $request): void
    {
        $this->googleDriveConfigService->delete($request->validated());
    }
}

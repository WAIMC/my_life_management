<?php

namespace App\Repositories\Management;

use App\Interfaces\Management\GoogleDriveConfigInterface;
use App\Models\Management\GoogleDriveConfig;
use Illuminate\Pagination\LengthAwarePaginator;

class GoogleDriveConfigRepository implements GoogleDriveConfigInterface
{
    public function list(array $payload): LengthAwarePaginator
    {
        $query = GoogleDriveConfig::query();

        // Filter by status
        if (isset($payload['status'])) {
            $query->where('status', $payload['status']);
        }

        // Exclude deleted
        $query->where('is_delete', false);

        // Order by active first, then by created date
        $query->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc');

        $perPage = $payload['per_page'] ?? 20;
        $page = $payload['page'] ?? 1;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    public function executeStore(array $payload): int
    {
        $config = GoogleDriveConfig::create($payload);
        return $config->id;
    }

    public function executeUpdate(array $payload): int
    {
        $id = $payload['id'];
        unset($payload['id']);

        return GoogleDriveConfig::where('id', $id)->update($payload);
    }

    public function executeDelete(array $ids): void
    {
        GoogleDriveConfig::whereIn('id', $ids)->update(['is_delete' => true]);
    }

    public function find(int $id)
    {
        return GoogleDriveConfig::where('id', $id)
            ->where('is_delete', false)
            ->first();
    }

    public function getActive()
    {
        return GoogleDriveConfig::active()->first();
    }

    public function deactivateAll(): void
    {
        GoogleDriveConfig::where('is_active', true)->update(['is_active' => false]);
    }
}

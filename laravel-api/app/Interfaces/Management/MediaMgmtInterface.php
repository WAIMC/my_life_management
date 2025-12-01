<?php

namespace App\Interfaces\Management;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MediaMgmtInterface
{
    public function list(array $payload): LengthAwarePaginator;
    public function find(int $id);
    public function executeStore(array $payload): int;
    public function executeUpdate(array $payload): int;
    public function executeDelete(array $ids): void;
}

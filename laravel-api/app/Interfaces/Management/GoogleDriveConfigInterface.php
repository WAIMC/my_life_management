<?php

namespace App\Interfaces\Management;

interface GoogleDriveConfigInterface
{
    public function list(array $payload);
    public function executeStore(array $payload): int;
    public function executeUpdate(array $payload): int;
    public function executeDelete(array $ids): void;
    public function find(int $id);
    public function getActive();
    public function deactivateAll(): void;
}

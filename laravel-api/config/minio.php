<?php

return [
    'endpoint' => env('MINIO_ENDPOINT', 'http://localhost:9100'),
    'access_key' => env('MINIO_ROOT_USER', 'ml_minio_admin'),
    'secret_key' => env('MINIO_ROOT_PASSWORD', 'ml_minio_password123'),
    'region' => env('MINIO_REGION', 'us-east-1'),
    'bucket' => env('MINIO_BUCKET', 'media'),
    'preview_bucket' => env('MINIO_PREVIEW_BUCKET', 'media-previews'),
    'public_url' => env('MINIO_PUBLIC_URL', 'http://localhost:9100'),
    'use_ssl' => env('MINIO_USE_SSL', false),
];

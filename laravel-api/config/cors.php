<?php

return [
    'paths' => ['api/*'],  // Áp dụng cho tất cả routes api
    'allowed_methods' => ['*'],  // Allow methods
    'allowed_origins' => ['*'],  // Allow origin cụ thể, thay '*' để secure
    'allowed_origins_patterns' => [],  // Nếu cần regex
    'allowed_headers' => ['*'],
    'exposed_headers' => [],  // Expose nếu cần
    'max_age' => 86400,  // Cache preflight
    'supports_credentials' => false,  // Set true nếu dùng cookies với credentials
];

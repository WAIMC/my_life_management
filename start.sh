#!/bin/bash

# Script đơn giản để khởi chạy Laravel Docker
echo "=== Khởi động Docker cho My Life Management ==="

# Thiết lập quyền cho toàn bộ project Laravel
echo "Thiết lập quyền cho toàn bộ Laravel project..."
sudo chown -R $(id -u):$(id -g) laravel-api/
sudo chmod -R 755 laravel-api/

# Thiết lập quyền đặc biệt cho các thư mục cần quyền ghi
echo "Thiết lập quyền đặc biệt cho các thư mục cần quyền ghi..."
mkdir -p laravel-api/storage/logs laravel-api/bootstrap/cache
sudo chmod -R 777 laravel-api/storage laravel-api/bootstrap/cache

# Đảm bảo quyền thực thi cho các script
echo "Thiết lập quyền thực thi cho các script..."
sudo chmod +x laravel-api/artisan
sudo find laravel-api -name "*.sh" -exec chmod +x {} \;

# Đảm bảo quyền đặc biệt cho tất cả các file trong thư mục Providers
# Các file này có thể được tạo bởi script auto_generator
echo "Thiết lập quyền đặc biệt cho tất cả các file trong thư mục Providers..."
sudo find laravel-api/app/Providers -type f -exec chmod 755 {} \; 2>/dev/null || true

# Đảm bảo quyền cho các thư mục cần thiết khác
echo "Thiết lập quyền cho các thư mục khác..."
sudo chmod -R 755 laravel-api/database
sudo chmod -R 755 laravel-api/resources
sudo chmod -R 755 laravel-api/public
sudo chmod -R 755 laravel-api/tests
sudo chmod -R 755 laravel-api/vendor

# Khởi động Docker
echo "Khởi động Docker containers..."
docker-compose -f docker/docker-compose.yml up -d

echo "=== Hoàn tất! ==="
echo "Truy cập API: http://localhost:81"
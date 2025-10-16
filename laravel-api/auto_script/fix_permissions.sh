#!/bin/bash

# Script này được thiết kế để chạy sau khi run_generator.sh hoàn thành
# Nó sẽ sửa quyền cho các file vừa được tạo

echo "Fixing permissions after auto-generator run..."

# Định nghĩa đường dẫn gốc
PROJECT_ROOT="/home/vinhdv/projects/my_life_management/laravel-api"

# Thiết lập quyền cho tất cả các file trong project
chmod -R 755 $PROJECT_ROOT/app/
chmod -R 755 $PROJECT_ROOT/config/
chmod -R 755 $PROJECT_ROOT/routes/
chmod -R 755 $PROJECT_ROOT/database
chmod -R 755 $PROJECT_ROOT/resources
chmod -R 755 $PROJECT_ROOT/public
chmod -R 755 $PROJECT_ROOT/tests

# Đảm bảo quyền đặc biệt cho tất cả các file trong thư mục Providers
echo "Setting permissions for all Provider files..."
chmod -R 755 $PROJECT_ROOT/app/Providers/

# Đảm bảo quyền ghi cho các thư mục cần thiết
chmod -R 777 $PROJECT_ROOT/storage
chmod -R 777 $PROJECT_ROOT/bootstrap/cache

# Đảm bảo quyền thực thi cho các script
chmod +x $PROJECT_ROOT/artisan
find $PROJECT_ROOT -name "*.sh" -exec chmod +x {} \;

echo "Permissions fixed successfully!"
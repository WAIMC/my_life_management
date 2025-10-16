# My Life Management Project

## 📑 Tổng quan dự án

**My Life Management** là hệ thống quản lý đa nền tảng được thiết kế theo kiến trúc microservice, gồm các thành phần chính:

- **Laravel API**: Backend API sử dụng Laravel 11 với PostgreSQL và Redis
- **Next.js Frontend**: Giao diện người dùng hiện đại sử dụng Next.js
- **Nuxt.js Frontend**: Phiên bản giao diện thay thế sử dụng Nuxt.js
- **Docker**: Môi trường phát triển đồng nhất cho tất cả các thành phần

## 🛠️ Yêu cầu hệ thống

- **Docker**: Phiên bản mới nhất
- **WSL 2**: Windows Subsystem for Linux 2
- **Git**: Quản lý phiên bản mã nguồn

## 🚀 Hướng dẫn cài đặt nhanh

### 1. Clone dự án

```bash
git clone https://github.com/WAIMC/my_life_management.git
cd my_life_management
```

### 2. Thiết lập môi trường

```bash
# Chạy script thiết lập file môi trường
./setup_env_files.sh

# Cấp quyền và khởi động Docker
chmod +x start.sh
./start.sh
```

### 3. Truy cập dự án

- **Laravel API**: http://localhost:81
- **Next.js Frontend**: http://localhost:3000 (khi được khởi động)
- **Nuxt.js Frontend**: http://localhost:3000 (khi được khởi động thay thế)

## 🗂️ Cấu trúc dự án

Dự án được tổ chức thành các thành phần riêng biệt:

```
my_life_management/
├── laravel-api/         # Backend API (Laravel 11)
├── nextjs-fe/           # Frontend chính (Next.js)
├── nuxtjs-FE/           # Frontend thay thế (Nuxt.js)
├── docker/              # Cấu hình Docker cho toàn bộ dự án
└── start.sh             # Script khởi động tự động
```

## 📋 Tài liệu chi tiết

Mỗi thành phần của dự án có file README.md riêng với hướng dẫn chi tiết. Bạn có thể xem:

- [Danh mục tài liệu đầy đủ](./DOCUMENTATION.md): Danh sách tất cả các tài liệu và hướng dẫn
- [Laravel API README](./laravel-api/README.md): Hướng dẫn chi tiết về API backend
- [Next.js Frontend README](./nextjs-fe/README.md): Hướng dẫn chi tiết về frontend Next.js
- [Nuxt.js Frontend README](./nuxtjs-FE/README.md): Hướng dẫn chi tiết về frontend Nuxt.js
- [Docker README](./docker/README.md): Hướng dẫn chi tiết về thiết lập Docker
## 🔐 Quản lý quyền WSL và Docker

Script `start.sh` tự động xử lý quyền truy cập giữa WSL và Docker:

```bash
# Chạy script để thiết lập quyền
./start.sh
```

Script `fix_permissions.sh` sẽ sửa quyền truy cập cho các file được tạo bởi auto-generator:

```bash
# Chạy sau khi sử dụng auto-generator
cd laravel-api/auto_script
./fix_permissions.sh
```

## 📝 Các lệnh Docker thông dụng

```bash
# Xem danh sách container đang chạy
docker ps

# Khởi động lại các container
docker-compose -f docker/docker-compose.yml restart

# Dừng tất cả container
docker-compose -f docker/docker-compose.yml down

# Xây dựng và khởi động lại container
docker-compose -f docker/docker-compose.yml up -d --build
```

## 🤝 Đóng góp

Xem [CONTRIBUTING.md](./CONTRIBUTING.md) để biết chi tiết về quy trình đóng góp.
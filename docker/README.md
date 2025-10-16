# Docker Configuration - My Life Management

## 📑 Giới thiệu

Thư mục này chứa cấu hình Docker cho toàn bộ dự án My Life Management, bao gồm các container cho Laravel API, Nginx, PostgreSQL và Redis. Cấu hình này cho phép phát triển và triển khai đồng nhất trên các môi trường khác nhau.

## 🛠️ Yêu cầu môi trường

- **Docker**: Phiên bản mới nhất
- **Docker Compose**: Phiên bản mới nhất
- **WSL 2** (đối với người dùng Windows)

## 🚀 Hướng dẫn cài đặt

### 1. Thiết lập môi trường

```bash
# Clone dự án (nếu chưa có)
git clone https://github.com/WAIMC/my_life_management.git
cd my_life_management

# Tạo file .env cho Docker
cp docker/.env.example docker/.env  # Nếu có

### 2. Cấu hình file .env

File `docker/.env` chứa các biến môi trường quan trọng:

```properties
# NGINX
NGINX_PORT_INSIDE_ENV=80
NGINX_PORT_OUTSIDE_ENV=81  # Port truy cập từ bên ngoài

# PostgreSQL
POSTGRES_HOST_INSIDE_ENV=ml-postgres
POSTGRES_HOST_OUTSIDE_ENV=localhost
POSTGRES_PORT_INSIDE_ENV=5432
POSTGRES_PORT_OUTSIDE_ENV=5502  # Port truy cập từ bên ngoài
POSTGRES_USER=ml_pg_user
POSTGRES_PASSWORD=ml_pg_password
POSTGRES_DB=ml_pg_db

# Redis
REDIS_HOST_INSIDE_ENV=ml-redis
REDIS_HOST_OUTSIDE_ENV=localhost
REDIS_PORT_OUTSIDE_ENV=6601  # Port truy cập từ bên ngoài
REDIS_PORT_INSIDE_ENV=6379
REDIS_PASSWORD=ml_redis_password
```

### 3. Xây dựng và khởi động Docker

```bash
# Cách 1: Sử dụng script start.sh (Khuyến nghị)
chmod +x start.sh
./start.sh

# Cách 2: Sử dụng docker-compose trực tiếp
cd docker
docker-compose down
docker-compose up -d --build
```

### 4. Kiểm tra trạng thái

```bash
docker ps
```

## 📋 Cấu trúc Docker

```
docker/
├── docker-compose.yml         # Cấu hình Docker Compose chính
├── fastapi/                   # Cấu hình cho FastAPI (nếu kích hoạt)
│   ├── Dockerfile
│   └── requirements.txt
├── laravel/                   # Cấu hình cho Laravel/PHP
│   ├── Dockerfile
│   └── php.ini
├── nextjs/                    # Cấu hình cho NextJS (nếu kích hoạt)
│   └── Dockerfile
├── nginx/                     # Cấu hình Nginx
│   └── default.conf
├── postgres/                  # Cấu hình PostgreSQL
│   ├── pg_hba.conf
│   └── postgresql.conf
└── redis/                     # Cấu hình Redis
    └── redis.conf
```

## 🔧 Các container chính

### 1. ml-php (Laravel API)

```yaml
# Cấu hình trong docker-compose.yml
ml-php:
  container_name: ml-php
  image: php:8.2-fpm-alpine
  volumes:
    - ../laravel-api:/var/www/laravel-api
  # Kết nối với PostgreSQL và Redis
```

### 2. ml-nginx (Web Server)

```yaml
# Cấu hình trong docker-compose.yml
ml-nginx:
  container_name: ml-nginx
  image: nginx:alpine
  volumes:
    - ./nginx/default.conf:/etc/nginx/conf.d/default.conf:ro
    - ../laravel-api:/var/www/laravel-api
  ports:
    - "${NGINX_PORT_OUTSIDE_ENV}:${NGINX_PORT_INSIDE_ENV}"
  # Phụ thuộc vào ml-php
```

### 3. ml-postgres (Database)

```yaml
# Cấu hình trong docker-compose.yml
ml-postgres:
  container_name: ml-postgres
  image: postgres:15-alpine
  volumes:
    - ./postgres/pg_hba.conf:/etc/postgresql/pg_hba.conf:ro
    - ./postgres/postgresql.conf:/etc/postgresql/postgresql.conf:ro
  environment:
    POSTGRES_DB: ${POSTGRES_DB}
    POSTGRES_USER: ${POSTGRES_USER}
    POSTGRES_PASSWORD: ${POSTGRES_PASSWORD}
  ports:
    - "${POSTGRES_PORT_OUTSIDE_ENV}:${POSTGRES_PORT_INSIDE_ENV}"
```

### 4. ml-redis (Cache)

```yaml
# Cấu hình trong docker-compose.yml
ml-redis:
  container_name: ml-redis
  image: redis:alpine
  volumes:
    - ./redis/redis.conf:/usr/local/etc/redis/redis.conf:ro
  ports:
    - "${REDIS_PORT_OUTSIDE_ENV}:${REDIS_PORT_INSIDE_ENV}"
  environment:
    REDIS_PASSWORD: ${REDIS_PASSWORD}
  command: ["redis-server", "/usr/local/etc/redis/redis.conf"]
```

## 🔍 Kiểm tra kết nối

### PostgreSQL

```bash
# Từ container ml-php
docker exec -it ml-php sh -c "PGPASSWORD=ml_pg_password psql -h ml-postgres -U ml_pg_user -d ml_pg_db"

# Từ máy chủ local
psql -h localhost -p 5502 -U ml_pg_user -d ml_pg_db
```

### Redis

```bash
# Từ container ml-php
docker exec -it ml-php sh -c "redis-cli -h ml-redis -p 6379 -a ml_redis_password"

# Từ máy chủ local
redis-cli -h localhost -p 6601 -a ml_redis_password
```

## 🔄 Quản lý dữ liệu

### Persistence

Dữ liệu PostgreSQL được lưu trữ trong volume Docker để đảm bảo tính bền vững:

```yaml
volumes:
  - postgres_data:/var/lib/postgresql/data
```

### Backup và Restore

```bash
# Backup PostgreSQL database
docker exec -it ml-postgres pg_dump -U ml_pg_user -d ml_pg_db > backup.sql

# Restore PostgreSQL database
docker exec -i ml-postgres psql -U ml_pg_user -d ml_pg_db < backup.sql
```

## 🔐 Quản lý quyền truy cập

Trong môi trường WSL với Docker, có thể xảy ra vấn đề về quyền truy cập file giữa container và hệ thống chủ. Sử dụng script `start.sh` để tự động xử lý:

```bash
./start.sh
```

## 🔧 Xử lý sự cố

### Không thể kết nối đến cơ sở dữ liệu

Kiểm tra:
1. Container PostgreSQL đang chạy
2. File cấu hình PostgreSQL cho phép kết nối từ bên ngoài
3. Mạng Docker đang hoạt động đúng

```bash
# Kiểm tra các container đang chạy
docker ps

# Kiểm tra logs
docker logs ml-postgres

# Kiểm tra mạng Docker
docker network ls
docker network inspect bridge
```

### Không thể kết nối đến Redis

```bash
# Kiểm tra logs
docker logs ml-redis

# Đảm bảo Redis đang lắng nghe trên tất cả interfaces
# Trong redis.conf: bind 0.0.0.0
```

### Các lệnh Docker hữu ích

```bash
# Khởi động lại một container cụ thể
docker restart ml-php

# Xem logs real-time
docker logs -f ml-nginx

# Kiểm tra sử dụng tài nguyên
docker stats

# Xóa tất cả containers và images không sử dụng
docker system prune
```

### etc
- Lựa chọn version sử dụng cho công nghệ chính (ở đây là php), sau đó tìm kiếm phiên bản công nghệ khác tương thích với công nghệ chính
- Tạo folder chứa dockerfile và config riêng cho mỗi công nghệ để config custom sâu hơn. Research dockerhub để tìm package cần thiết 
- Bên ngoài các folder vừa tạo, tạo file và config file docker-compose.yml
để quản lý chung
- Kiểm tra giao tiếp giữa các container nếu cần. Ở đây kiểm tra giao tiếp
giữa nginx và php thông qua service php-fpm

## 📝 Tham khảo

- [Docker Documentation](https://docs.docker.com/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [PostgreSQL Docker Hub](https://hub.docker.com/_/postgres)
- [Redis Docker Hub](https://hub.docker.com/_/redis)
- [Nginx Docker Hub](https://hub.docker.com/_/nginx)
- [PHP Docker Hub](https://hub.docker.com/_/php)
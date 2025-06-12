# Project Setup Guide

## ✅ Requirement

- Docker
- WSL 2


---

## 🛠️ Setup Steps
### 1. Install ubuntu distro for wsl + start docker
### 2. Run cmd below to access wsl
  ```
  wsl
  ```
### 3. Create folder containing source code
  ```
  cd /home/[user]/projects
  cd /home/[user]/
  mkdir projects
  ```
### 4. Clone source + build + run env
  ```
  git clone {path}
  cd docker
  docker-compose down
  docker-compose up -d --build
  ```
### 5. Database configuration for Laravel API:
Go to laravel-api folder, duplicate .env.dev file and update information:
- Connect in Docker env:
  ```
    DB_CONNECTION=pgsql
    DB_HOST=localhost
    DB_PORT=5432
    DB_DATABASE=xyz
    DB_USERNAME=postgres
    DB_PASSWORD=
  ```
- Connect localhost to postgres in docker
  ```
    DB_CONNECTION=pgsql
    DB_HOST=localhost
    DB_PORT=5502
    DB_DATABASE=ml_pg_db
    DB_USERNAME=ml_pg_user
    DB_PASSWORD=ml_password
  ```
### 6. Go to source laravel-api duplicate file env.dev and change
  - Connect redis in Docker env:
  ```
    REDIS_CLIENT=predis
    REDIS_HOST=ml-redis
    REDIS_PASSWORD=ml_redis_password
    REDIS_PORT=6379
  ```
  - Connect redis from localhost to redis in docker env
  ```
    REDIS_CLIENT=predis
    REDIS_HOST=localhost or 127.0.0.1:6601
    REDIS_PASSWORD=ml_redis_password
    REDIS_PORT=6601
  ```
### 7. Run the following cmd in the ml-php docker container
- Run all migrate
  ```
    php artisan migrate:all
  ```
- Rollback all migrate
  ```
    php artisan migrate:rollback-all
  ```
### 8. Execute cmd below in ml-php container
  ```
    php artisan app:sync-api-permission
  ```
### 9. Execute cmd below in ml-php container to setup if not execute migrate, sync api, 
  ```
    php artisan app:sync-api-permission
  ```






Another work
  9. Dựa vào APP_URL trong .env source laravel-api để biết nó nhận url nào bên ngoài docker connect vào
  1. Mở + làm việc với project với app ở path: \\wsl.localhost\Ubuntu\home\[user]\projects\
  2. Mở explorer trực quan ở path: cmd chạy wsl và explorer.exe .



/////////////
- Connect thử postgresql
  Host: localhost
  Port: 5433
  db: ml_db
  username: ml_user
  pass: ml_password
- chạy lệnh sau trong container postgresql
  psql -U ml_pg_user ml_pg_db
  CREATE TABLE test_table (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100),
    created_at TIMESTAMP DEFAULT NOW()
  );
- Vào dbeaver check thử có table được tạo không

// test connect redis
redis-cli -h 127.0.0.1 -p 6601 -a ml_redis_password

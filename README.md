Requirement
  + Requirement: docker, wsl, dbeaver, vscode, redis insight, fort git

+ Step
  1. Cài distro ubuntu cho wsl + khởi động docker
  2. Chạy cmd sau
    wsl
  3. Tạo folder chứa source code trong cd /home/[user]/projects
    cd /home/[user]/
    mkdir projects
  4. Clone source + run + buid source
    git clone path
    cd docker
    docker-compose down
    docker-compose up -d --build
  5. Vào source laravel-api duplicate file env.dev và thay đổi
    - Kết nối trong docker
    DB_CONNECTION=pgsql
    DB_HOST=localhost
    DB_PORT=5432
    DB_DATABASE=xyz
    DB_USERNAME=postgres
    DB_PASSWORD=
    - Kết nối localhost tới postgres trong docker
    DB_CONNECTION=pgsql
    DB_HOST=localhost
    DB_PORT=5502
    DB_DATABASE=ml_pg_db
    DB_USERNAME=ml_pg_user
    DB_PASSWORD=ml_password
  6. Vào source laravel-api duplicate file env.dev và thay đổi
  - kết nối redis trong docker
    REDIS_CLIENT=predis
    REDIS_HOST=ml-redis
    REDIS_PASSWORD=ml_redis_password
    REDIS_PORT=6379
  - Kết nối redis localhost tới docker
    REDIS_CLIENT=predis
    REDIS_HOST=localhost or 127.0.0.1:6601
    REDIS_PASSWORD=ml_redis_password
    REDIS_PORT=6601
  7. Chạy cmd sau trong docker container ml-php
  - Chạy tất cả migrate
    php artisan migrate:all
  - Rollback tất cả migrate
    php artisan migrate:rollback-all
  8. Execute sql query trong file data_init.sql
  9. Dựa vào APP_URL trong .env source laravel-api để biết nó nhận url nào bên ngoài docker connect vào







Another work
  1. Mở + làm việc với project với app ở path: \\wsl.localhost\Ubuntu\home\[user]\projects\
  2. Mở explorer trực quan ở path: cmd chạy wsl và explorer.exe .



// Test thử connect dbeaver
- Connect thử
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


docker restart 


// test connect redis
redis-cli -h 127.0.0.1 -p 6601 -a ml_redis_password

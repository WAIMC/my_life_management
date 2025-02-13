# Step cài đặt env bằng docker
- Cài đặt wsl, docker
- Khởi động docker, chạy command line nơi chứa file `docker-compose.yml`
- Chạy command line dưới đây
  - **Build các container**
    ```
    docker-compose build
    ```
  - **Khởi động các container**
    ```
    docker-compose up -d
    ```
- Hiển thị các container, image đang chạy
  ```
  docker ps
  ```
- Truy cập contrainer laravel cấu hình
  ```
  docker exec -it container-name bash
  ```
- Vào file laravel-api/README.md thực hiện tiếp tục từ `step 3`

# Syntax thao tác và ý nghĩa
- **Lưu ý :**
  - Restart image và chạy container mới, ko mất dữ liệu nếu dùng volume lưu trữ ngoài container
  - Restart docker sẽ bị mất hết data
- Khởi động và chạy ngầm (tắt cũng được) tất cả container
  ```
  docker-compose up -d
  ```
- Dừng tất cả container
  ```
  docker-compose down
  ```
- Build lại container từ image từ file `docker-compose.yml`
  ```
  docker-compose build
  ```
  **Thực hiện để cập nhật sau khi thay đổi dockerfile, cấu hình code, thư viện mà ko update trong container, dependence, cấu hình hệ thống default.conf, php.ini...**


# Mindset setup
- Nghiên cứu công nghệ cần sử dụng cho project software.
`Ở đây là build 1 project software website`
- Công nghệ website này dùng cần
  + Web server: nginx, apache,.. `Dự án này dùng web server là nginx`
  + Back-end: `Dùng php, service dùng để giao tiếp giữa php và web server là php-fpm`
  + Database: `Dùng postgresql`
  + Cache: `Dùng redis`
- Lựa chọn version sử dụng cho công nghệ chính (ở đây là php), sau đó tìm kiếm phiên bản công nghệ khác tương thích với công nghệ chính
- Tạo folder chứa dockerfile và config riêng cho mỗi công nghệ để config custom sâu hơn. Research dockerhub để tìm package cần thiết 
- Bên ngoài các folder vừa tạo, tạo file và config file docker-compose.yml
để quản lý chung
- Kiểm tra giao tiếp giữa các container nếu cần. Ở đây kiểm tra giao tiếp
giữa nginx và php thông qua service php-fpm
- Custom host default.conf của nginx thì cần mở file C:\Windows\System32\drivers\etc\hosts và thêm code để config host ánh xạ đến IP của docker
  ```
  127.0.0.1 my-life-management.com
  ```
- Tiến hành build image và container, chạy terminal nơi chứa file
**docker-compose.yml**
  ```
  docker-compose build
  ```
- Truy cập host+port kiểm tra có truy cập được không, nếu không thì check và sửa lại config:
  + Kiểm tra path các config sau khi build trong container có tồn tại
  không, copy đã đúng chưa ?
  + Kiểm tra giao tiếp giữa các container
  + Kiểm tra IP và các config khác
  docker inspect container-name
- Kiểm tra kết nối giữa các container với nhau trong docker:
  ping container-name or ping [container Networks IPAddress (trong inspect mỗi container)]
  + Nếu không có ping trong env container thì cài đặt ping:
  apt-get update && apt-get install -y iputils-ping
  Kiểm tra connect:
    webserver (nginx) <-> back-end(php) qua service fpm
    back-end(php) <-> DB(posgres) qua pg_sql or pdo_pgsql của php, qua giao thức TCP/IP
    back-end(php) <-> Cache(redis) qua redis_extension của php, qua giao thức TCP/IP
  + Kiểm tra access DB(postgresql)
    Vào terminal container qua docker > container db > exec hoặc docker exec -it container-db bash
      * chạy lệnh: psql -U postgres
      * Nhập pass
      * Liệt kê các db hiện có: \l
      * Kết nối với db cụ thể: \c postgres
      * Kiểm tra table: \dt
      * 
      * Nếu dùng bên trong container db thì bỏ -h [host or ip] -p [port]

      psql -h [host or ip] -p [port] -U [user] -d [db name]  -c "password"
      * Kiểm tra PostgreSQL lắng nghe các kết nối TCP/IP
        / Mở file /var/lib/postgresql/data/postgresql.conf và kiểm tra có đúng đang lắng nghe tất cả địa chỉ
        listen_addresses = '*'
      * Kiểm tra quyền truy cập trong pg_hba.conf
        / Mở file /var/lib/postgresql/data/pg_hba.conf và kiểm tra có dòng sau cho phép kết nối mọi IP
        host    all             all             0.0.0.0/0            md5
        / Kiểm tra từ bên ngoài docker
      - Cài đặt extension postgresql-client cho php và test connect container posgresql db
      ```
      PGPASSWORD="Vinh@123" psql -h db-pgsql -p 5432 -U postgres -d postgres
      ```

- Kiểm tra kết nỗi từ bên ngoài với docker
  + config firewall container docker posgresql cho phép port
  + Config firewall window (nếu dùng window) window defence firewall inbound cho phép port của posgresl
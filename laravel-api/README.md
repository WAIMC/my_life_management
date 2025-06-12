# Project Setup Guide

### 1. Requirement
-   Apache version 2.4.62
-   php version 8.2.26
-   postgresql 16.6
-   redis version 5.0.14.1
### 2. Clone project
```
git clone {path}
```
### 3. Install package
```
composer i
```
### 4: Duplicate file `.env.example` and rename to `.env`
### 5: Run command below to generate key
```
php artisan key:generate
```
### 6: Run start web server apache, php, postgresql, redis
### 7: Create new schema in postgresql
### 8: Open file `.env` , get information connect from `Step 7` to edit
```
DB_CONNECTION=pgsql
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=
```
### 9: Run command below to migrate database
#### 9.1: Migration all tables**
```
php artisan migrate:all
```
#### 9.2: Migration rollback all**
```
php artisan migrate:rollback-all
```
### 10: Run command below when init or update api route
```
php artisan app:sync-api-permission
```
### 11: Open file `.env` , get information connect redis to edit
```
REDIS_CLIENT=predis
REDIS_HOST=
REDIS_PASSWORD=
REDIS_PORT=
```
### 12: Open cmd or terminal run command below to generate access and refresh secret key
#### 12.1: Copy result, create assign value for new variable `ACCESS_TOKEN_SECRET=`
```
php -r 'echo base64_encode(random_bytes(32));'
```
#### 12.2: Copy result, create assign value for new variable `REFRESH_TOKEN_SECRET=`
```
php -r 'echo base64_encode(random_bytes(32));'
```
## Step 13: Open cmd or terminal run command below to start web
```
php artisan serve
```
## Step 14: Open cmd or terminal run command below to auto setup all
```
php artisan setup:project
```








////////////////////////////////////////////////////////////////////////////
TÍNH NĂNG TỰ ĐỘNG HÓA TẠO VÀ CẬP NHẬT TÀI KHOẢN ADMIN FULL QUYỀN

Tôi đang xây dựng ứng dụng với laravel, tôi có 1 file .sql và 1 ảnh thiết kế ERD như đính kèm. Tôi muốn quản lý quyền truy cập của user, ý tưởng của tôi như sau:
- Hình thức quản lý thứ nhất: Mỗi account sẽ được gắn vào 1 hoặc nhiều role khác nhau, mỗi role chịu trách nghiệm 1 số lượng API nào đó, 1 hoặc nhiều API sẽ thuộc 1 cụm chức năng
  tôi đặt là feature VD: API thêm, sửa, xóa, export, import,... product thuộc feature product management.
- Hình thức quản lý thứ hai: Mỗi account sẽ được quản lý bởi department (phòng ban), account đó có thể được quản lý or thuộc 1 or nhiều department. Mỗi department sẽ được giao quản lý
  1 số table và số lượng record nhất định. Nhiệm vụ của nó là để setting quyền truy cập của một account được thao tác với table nào, record nào
  Kết quả: Quyền truy cập và thao tác với 1 record quy định account đang thao tác thuộc department nào, department đó có được truy cập hay không và account đó có được thực hiện các API để thao tác với record đó hay không

Tôi bắt đầu thực hiện phác thảo ý tưởng của mình như hình ảnh thiết kế ERD đính kèm, tôi đã thực hiện
- Tạo migrate cho tất cả các table
- Tạo file .sql để khi một người mới phát triển dự án, họ sẽ có 1 account admin full quyền


Vấn đề:
- Hiện tại api đang lưu dạng path vd: api/admin/department-management/list và gắn tương ứng với một feature như feature department management.
  Vấn đề xảy ra khi tôi mở rộng tính năng tôi lại phải tạo feature và api thủ công, sau đó tạo thêm record mới để liên kết chúng tương ứng với 1 role admin,
  role admin này chỉ gán cho account admin.

- Tôi đang muốn tạo một tính năng tự động như bash, dbseed,...

******************************
- Thời gian chạy tương ứng với số lượng route
- Cần thêm logic đồng bộ ngược, khi 1 api or route sửa hoặc xóa
- Nếu số lượng route thay đổi thường xuyên liên tục thì chuyển qua cơ chế thực hiện theo định kỳ
Mong muốn:
tôi đang có ý tưởng rằng, mỗi khi tạo api mới. tính năng mới sẽ phán đoán vd api: api/admin/department-management/list
thì tính năng mới sẽ thực hiện:
- Lấy tất cả cả route hiện tại
- Phân tách chúng ra : vd api: api/admin/department-management/list
  Lấy feature là: department management. Kiểm tra nếu chưa tồn tại thì tạo mới
  lấy api là : api/admin/department-management/list gắn api này vào feature đó. Kiểm tra nếu chưa tồn tại thì tạo mới và gắn liên kết với feature
  Tạo Role là root nếu chưa có và add api trên liên kết với role root
  Tạo admin là root@gmail.com ,.... nếu chưa có và gắn liên kết với role root nếu chưa liên kết

- Tôi muốn tính năng này có thể chạy tự động trong một câu lệnh. Khi mới khởi tạo dự án or phát triển các tính năng mới chạy lệnh này thì admin sẽ được add các quyền truy cập api mới, phần departmen
  tôi tạm thời gác lại phần đó sẽ xử lý sau. Còn lại hãy xem xét tính khả thi, gợi ý cho tôi cách thức tối ưu hơn để thực hiện có cùng kết quả và cách thực hiện nó thế nào một cách chi tiết

///////////////////////////////////////////////////////////////////////////////


Báo cáo chi tiết
Giới thiệu
Chức năng tự động hóa quá trình thiết lập dự án hoặc cập nhật quyền truy cập các tính năng mới bằng một lệnh duy nhất. Mục tiêu là đảm bảo rằng khi chạy lệnh này, dự án được thiết lập đầy đủ, bao gồm một tài khoản admin với quyền truy cập đầy đủ vào tất cả các tính năng (features) và API. 

Phân tích yêu cầu
Yêu cầu của bạn bao gồm:
Tự động hóa với một lệnh duy nhất: Lệnh này sẽ được sử dụng khi khởi tạo dự án mới hoặc phát triển tính năng mới, đảm bảo admin có quyền truy cập đầy đủ.
Tài khoản admin với quyền đầy đủ: Sau khi chạy lệnh, phải có một tài khoản admin (ví dụ: root@gmail.com) được liên kết với vai trò “root” và có quyền truy cập vào tất cả các tính năng và API.

Cách tiếp cận tối ưu là:
    - Tạo, sắp xếp, phân vùng các migrations để tạo schema cơ sở dữ liệu.
    - Tạo seeder tạo dữ liệu ban đầu
    - sync:permission Đồng bộ hóa các tính năng và API từ các tuyến đường, liên kết chúng với vai trò admin.


Ý tưởng thiết kế: Ban đầu khi dự án khởi tạo, phát triển, vận hành dự án. Thì có 1 account gọi là root. Luôn luôn có toàn quyền truy cập
Khi một user khác được thêm vào hệ thống, sẽ cần có 1 cơ chế để quản trị.

- Tạo ra các department (phòng ban), mỗi phòng ban sẽ có trách nghiệm quản lý 1 số vùng. Gán user vào department, có nghĩa phạm vi của user sẽ ở quản lý trong vùng đó
. Một user có thể thuộc 1 hoặc nhiều phòng ban

- Tạo ra các chức vụ (role), mỗi chức vụ sẽ ứng với 1 số hành động được phép thực hiện hoặc không. Mỗi user được gán 1 hoặc nhiều role khác nhau. User có thể thực hiện
các hành động đó trong phạm vi phòng ban


Phân quyền theo mô hình kim tự tháp, ban đầu chỉ có root sau đó tạo ra các role, department để share quyền quản lý cho các user khác. Một số chỉ được phép set quyền
truy cập cho các user khác, một số user chỉ có thể thao tác xem, sửa,... cho 1 số tính năng. Những user có thể linh hoạt có quyền hành động ở 1 hoặc nhiều phòng ban khác nhau và cũng có thể thu hồi quyền.

Từ ý tưởng đến áp dụng nó vào thiết kế csdl vật lý để lưu trữ và quản lý, tôi thiết kế và mô tả ý nghĩa của chúng như sau:

    - t_admin: lưu trữ thông tin account
    - t_role: lưu trữ thông tin các chức vụ
    - t_admin_role: bảng trung gian liên kết N-N giữa admin và role
    - t_api: lưu trữ thông tin uri
    - t_api_role: Bảng trung gian liên kết N-N giữa api và role
    - t_feature: lưu trữ thông tin một tính năng vd: tính năng category bao gồm nhiều api: crud, import, export, upload,... category
    - t_department: lưu trữ thông tin phòng ban
    - t_admin_department: Bảng trung gian liên kết N-N giữa admin và department
    - t_policy_department: lưu trữ thông tin table, record id thuộc quản lý của department
    - t_department_management: Bảng trung gian lưu trữ ...

Tinh năng tự động 


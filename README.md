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

/////////////////////////////////////////////////////
Tạo rule coding convention, format, structure,.. khi sử dụng AI

Khi code các chức năng curd cho 1 model làm những công việc sau:
Lệnh yêu cầu: "tạo chức năng crud cho t_admin như hình ảnh sau hoặc theo thiết kế sau:
  Table name	t_admin						
  Description	Admin account						
                
  Column Name	Data Type	Default	Not Null	Primary Key	Foreign key	Comment	Explain
  id	serial4	sequence	TRUE	TRUE	FALSE		ID admin account 
  email	Varchar(30)		TRUE	FALSE	FALSE	Admin email	Admin email
  user_name	Varchar(50)		TRUE	FALSE	FALSE	Admin user name	Admin user name
  password	Varchar(100)		TRUE	FALSE	FALSE	Admin password	Admin password
  first_name	Varchar(20)		TRUE	FALSE	FALSE	Admin first name	Admin first name
  last_name	Varchar(20)		TRUE	FALSE	FALSE	Admin last name	Admin last name
  address	Varchar(100)		FALSE	FALSE	FALSE	Admin address	Admin address
  phone_numner	Varchar(20)		FALSE	FALSE	FALSE	Admin phone number	Admin phone number
  birth	Timestamp		FALSE	FALSE	FALSE	Admin birth	Admin birth
  gender	Int2	0	TRUE	FALSE	FALSE	Admin gender	"Admin gender
  Value:
  + 0: male
  + 1: Female"
  status	Int2	0	TRUE	FALSE	FALSE	Admin status	"Admin status
  Value:
  + 0: Default, Inactive
  + 1: Active
  + 2: Waiting
  + 3: Suspended"
  is_active	Bool	FALSE	TRUE	FALSE	FALSE	Admin active	"Admin has active
  Value:
  + FALSE: Admin disable
  + TRUE: Admin enlable"
  avatar	Varchar(30)		FALSE	FALSE	FALSE	Admin avatar name	Admin avatar name
  email_verified_at	Timestamp		FALSE	FALSE	FALSE	Verified email time	Verified email time
  remember_token	Varchar(100)		FALSE	FALSE	FALSE	Remember token	Remember token
  create_at	Timestamp		FALSE	FALSE	FALSE	Create at	Create at
  update_at	Timestamp		FALSE	FALSE	FALSE	Update at	Update at
        FALSE	FALSE	FALSE		
							
hoặc link google sheet 
", AI sẽ phân tích và thực hiện.

- Thực hiện tính năng migrate:
  1. kiểm tra đã có migrate định nghĩa cho table t_admin chưa ? Nếu chưa
  có thì tạo mới. Nếu có rồi thì kiểm tra xem các thuộc tính của thiết kế so với migrate hiện tại của table t_admin, nếu thêm hoặc bớt định nghĩa data type, comment, default, not null,... thì cập nhật lại như theo thiết kế
    + Chạy cmd: php artisan make:migrate {path}/{create: cho tạo mới và update: cho cập nhật lại}_{tên table}_table
    + Trong đó path được quy định như sau: 
      / Nếu table có tiền tố là: t_{table name}, ví dụ: t_admin thì path tạo trong database/migrations/tables/masters
      / Nếu table có tiền tố là: h_{table name}, ví dụ: h_admin thì path tạo trong database/migrations/tables/histories
    + Trong file migrate mới tạo: Định nghĩa tên table, các data type của table như thiết kế trong function up() và drop table trong function down()
  2. Nếu yêu cầu là tạo triggers cho table t_admin với logic như sau before or after với "logic" thì tiến hành phân tích nội dung trigger. kiểm tra trong phạm vi Database\Migrations\triggers xem có trigger đó không ? nếu không thì tạo mới nếu có rồi thì sửa lại logic đã đưa
    + Chạy cmd cho triggers chưa có: php artisan make:migrate {path}/create_{before or after}_{tên table}_{insert or update or delete}
    + Trong đó path tạo trong: database/migrations/triggers
    + Trong file migrate vừa tạo thực hiện viết logic cho function up() ví dụ như sau:
     // Create the function
    DB::unprepared("
      CREATE OR REPLACE FUNCTION insert_into_api_role_from_api() RETURNS TRIGGER AS $$
        BEGIN
          -- Insert a new record into t_api_role table
          INSERT INTO t_api_role (api_id, role_id, created_at, updated_at)
          SELECT NEW.id, id, now(), now() FROM t_role WHERE name = 'root';
          RETURN NEW;
        END;
      $$ LANGUAGE plpgsql;
    ");

    // Create the trigger
    DB::unprepared("
      CREATE TRIGGER after_api_insert AFTER INSERT ON t_api
      FOR EACH ROW
      EXECUTE FUNCTION insert_into_api_role_from_api();
    ");
    + Trong file migrate vừa tạo thực hiện viết logic cho function down() ví dụ như sau:
    // Drop the trigger
    DB::unprepared('DROP TRIGGER IF EXISTS after_api_insert ON t_api');

    // Drop the function
    DB::unprepared('DROP FUNCTION IF EXISTS insert_into_api_role_from_api');
  
  3. Nếu yêu cầu là tạo views với logic như sau "logic" thì tiến hành phân tích nội dung views. kiểm tra trong phạm vi Database\Migrations\views xem có views đó không ? nếu không thì tạo mới nếu có rồi thì sửa lại logic đã đưa
  + Chạy cmd cho views chưa có: php artisan make:migrate {path}/_{tên view}_view
    + Trong đó path tạo trong: database/migrations/view
    + Trong file migrate vừa tạo thực hiện viết logic cho function up() ví dụ như sau:
    DB::statement("
            CREATE VIEW admin_permission_view AS
            SELECT
            ta.id         AS admin_id,
            tr.id         AS role_id,
            tr.name       AS role_name,
            CASE
                WHEN ta2.type = 0 THEN 'GET'
                WHEN ta2.type = 1 THEN 'POST'
                WHEN ta2.type = 2 THEN 'PUT'
                WHEN ta2.type = 3 THEN 'PATCH'
                WHEN ta2.type = 4 THEN 'DELETE'
                ELSE null
            END AS type,
            ta2.name      AS api_name,
            ta2.path      AS path,
            tf.name       AS feature_name,
            tf.group_name AS feature_group
        FROM
            t_admin ta												-- Account
            INNER JOIN t_admin_role tar ON tar.admin_id = ta.id 	-- Admin role 
            INNER JOIN t_role tr ON tr.id = tar.role_id				-- Role 
            INNER JOIN t_api_role tar2 ON tar2.role_id = tr.id		-- Api feature
            INNER JOIN t_api ta2 ON ta2.id = tar2.api_id 			-- Api id
            INNER JOIN t_feature tf ON tf.id = ta2.feature_id 		-- Feature
        WHERE
            ta.status = 1
            AND ta.is_active = TRUE
            AND tr.is_active = TRUE 
            AND ta2.is_active = TRUE 
            AND tf.status = 1
        ");
    + Trong file migrate vừa tạo thực hiện viết logic cho function down() ví dụ như sau:
    DB::statement("DROP VIEW IF EXISTS admin_permission_view");

- Thực hiện tính năng model:
  + Kiểm tra nếu chưa có model thì tạo model bằng câu lệnh
    php artisan make:model {path}/{tên model}
  + Path dựa vào tên tiền tố của table vd:
    / Tiền tố là t_{table} tạo ở path: App\Models\Master
    / Tiền tố là h_{table} tạo ở path: App\Models\History
  + Tên model bỏ tiền tố, dạng PascalCase => Admin
  + Định nghĩa các const cho model đó ứng với data type trong thiết kế vd:
    / Trong thiết kế:
    gender	Int2	0	TRUE	FALSE	FALSE	Admin gender	"Admin gender Value: + 0: male + 1: Female"
    / Định nghĩa: 
      public const GENDER = [
        'male'   => 0,
        'female' => 1
      ];
  + Định nghĩa fillable là các column sẽ được thao tác đọc/ghi dữ liệu vd:
    /**
    * The attributes that are mass assignable.
    *
    * @var string[]
    */
    protected $fillable = [
      'email',
      'user_name',
      'first_name',
      'last_name',
      'password',
      'address',
      'phone_number',
      'birth',
      'gender',
      'status',
      'is_active',
      'avatar',
    ];
  + Định nghĩa attributes và tên của chúng ví dụ:
    /**
    * Get custom attributes for validator errors.
    *
    * @return array<string, string>
    */
    public static function attributes(): array
    {
      return [
        'id'                => 'Admin ID',
        'email'             => 'email',
        'user_name'         => 'user name',
        'password'          => 'password',
        'first_name'        => 'first name',
        'last_name'         => 'last name',
        'address'           => 'address',
        'phone_number'      => 'phone_number',
        'birth'             => 'birth',
        'gender'            => 'gender',
        'status'            => 'status',
        'is_active'         => 'is active',
        'avatar'            => 'avatar',
        'email_verified_at' => 'email verified',
        'remember_token'    => 'remember token',
        'created_at'        => 'created at',
        'updated_at'        => 'created at',
      ];
    }
  + Định nghĩa relationship nếu có, ví dụ:
    / Thiết kế: 
      Table name	t_admin_role						
      Description	Account and role of account admin						
                    
      Column Name	Data Type	Default	Not Null	Primary Key	Foreign key	Comment	Explain
      admin_id	Int4		TRUE	FALSE	TRUE	Admin id	ID admin account
      role_id	Int4		TRUE	FALSE	TRUE	Role id	ID role of admin account
      create_at	Timestamp		FALSE	FALSE	FALSE	Create at	Create at
      update_at	Timestamp		FALSE	FALSE	FALSE	Update at	Update at
    / Thực hiện viết hasOne, hasMany, belongto,... tương ứng vd:
      public function roles()
      {
          return $this->belongsToMany(Role::class, 't_admin_role');
      }

- Thực hiện tính năng controller:
- Thực hiện tính năng service:
- Thực hiện tính năng validate:
- Thực hiện tính năng interface:
- Thực hiện tính năng abtract:
- Thực hiện tính năng repository:
- Thực hiện tính năng collection:
- Thực hiện tính năng route:
....

////////////////////////////////////////////////////
Dưới đây là **rule coding convention** tổng quát cho việc sử dụng AI sinh mã Laravel CRUD theo thiết kế bảng dữ liệu như `t_admin`:

---

## 🧠 **1. Input Format yêu cầu cho AI**

Yêu cầu phải bắt đầu bằng cụm từ rõ ràng:

> **"Tạo chức năng CRUD cho `tên_table` như thiết kế sau:"**
> hoặc
> **"Tạo CRUD cho bảng `t_admin` như hình ảnh sau hoặc link Google Sheet:"**

### ✅ **Yêu cầu thiết kế cần có:**

* Tên bảng
* Mô tả bảng (description)
* Bảng chi tiết các cột:

  * `Column Name`, `Data Type`, `Default`, `Not Null`, `Primary Key`, `Foreign Key`, `Comment`, `Explain`
* Các logic liên quan trigger, view nếu có (mô tả cụ thể)

---

## 🏗️ **2. Migration**

### ✅ Kiểm tra tồn tại:

* Nếu chưa có -> Tạo mới
* Nếu đã có -> So sánh & cập nhật fields, data types, default, comment, NOT NULL

### ✅ Cấu trúc lệnh:

```bash
php artisan make:migrate database/migrations/tables/masters/create_t_admin_table
```

### ✅ Phân loại theo tiền tố:

| Tiền tố | Path                                    |
| ------- | --------------------------------------- |
| `t_`    | `database/migrations/tables/masters/`   |
| `h_`    | `database/migrations/tables/histories/` |

### ✅ Logic viết file:

```php
Schema::create('t_admin', function (Blueprint $table) {
    $table->id(); // hoặc $table->bigIncrements('id')
    $table->string('email', 30)->comment('Admin email');
    $table->string('user_name', 50)->comment('Admin user name');
    ...
    $table->timestamps();
});
```

---

## 🔁 **3. Trigger**

### ✅ Kiểm tra và cập nhật:

* Path: `database/migrations/triggers/`
* Tên file: `create_{before|after}_{table}_{insert|update|delete}`

### ✅ Cấu trúc lệnh:

```bash
php artisan make:migrate database/migrations/triggers/create_after_t_admin_insert
```

### ✅ Mẫu logic:

```php
DB::unprepared("CREATE OR REPLACE FUNCTION ...");
DB::unprepared("CREATE TRIGGER ...");
```

---

## 👁️ **4. View**

### ✅ Path: `database/migrations/views/`

### ✅ Tên file: `_admin_permission_view`

```bash
php artisan make:migrate database/migrations/views/_admin_permission_view
```

### ✅ Trong file:

```php
DB::statement("
    CREATE VIEW admin_permission_view AS
    SELECT ...
");
```

---

## 🧩 **5. Model**

### ✅ Kiểm tra & tạo:

```bash
php artisan make:model App/Models/Master/Admin
```

### ✅ Phân loại:

| Tiền tố | Path                 | Tên Model                |
| ------- | -------------------- | ------------------------ |
| `t_`    | `App\Models\Master`  | PascalCase không tiền tố |
| `h_`    | `App\Models\History` | PascalCase không tiền tố |

### ✅ Chuẩn hóa:

* `fillable`: Chứa các cột cập nhật được
* `attributes()`: Dịch tên field
* `const`: Cho enum như `status`, `gender`
* Quan hệ: belongsToMany, hasMany, hasOne...

---

## 📦 **6. Controller**

* Tạo: `php artisan make:controller AdminController`
* Theo chuẩn REST: index, store, show, update, destroy
* Gọi service bên trong controller

---

## 🧰 **7. Service**

* Đặt trong: `App\Services\{ModelName}Service`
* Chứa logic chính của nghiệp vụ CRUD

---

## 🧪 **8. Validate**

* Tạo Request classes: `php artisan make:request AdminStoreRequest`, `AdminUpdateRequest`
* Tách biệt logic validate khỏi controller
* Sử dụng trong controller thông qua dependency injection

---

## 🔌 **9. Interface & Abstract**

* Interface: định nghĩa trong `App\Contracts\Repositories\{ModelName}RepositoryInterface`
* Abstract Repository (optional): dùng khi có shared logic nhiều repositories

---

## 📚 **10. Repository**

* Mỗi model có 1 repository: `App\Repositories\{ModelName}Repository`
* Giao tiếp giữa service và database
* Implement Interface

---

## 🔄 **11. Collection**

* Custom response logic, transformation
* Đặt trong: `App\Http\Resources\{ModelName}Collection` hoặc `Resource`

---

## 🛣️ **12. Route**

* CRUD theo nhóm route (Route::prefix / group / middleware)

```php
Route::prefix('admin')->controller(AdminController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('{id}', 'show');
    Route::put('{id}', 'update');
    Route::delete('{id}', 'destroy');
});
```

---

## ✍️ **13. Gợi ý lời yêu cầu mẫu**

> Tạo chức năng CRUD cho bảng `t_admin` theo thiết kế sau:
> (Kèm file thiết kế hoặc bảng chi tiết hoặc link Google Sheet)

---

=>  tổng hợp các phần còn lại như Controller, Service, Validation... theo format YAML / markdown để dễ copy dùng AI, hướng dẫn chi tiết các bước thực hiện, lưu trữ các rule này ở đâu để có thể share cho toàn bộ team có đều có thể thực hiện, cách demo.

trực tiếp tạo một rule cụ thể để sử dụng AI (như GitHub Copilot) tự động hóa việc tạo mã CRUD trong Laravel, sau đó lưu trữ rule này trong source code để team có thể clone, đọc và áp dụng. Dưới đây là cách tôi thực hiện yêu cầu của bạn bằng cách cung cấp rule thực dưới dạng tài liệu markdown, sẵn sàng để lưu vào source code (ví dụ: trong docs/crud_rule.md).


=> Tổng hợp, thống kê một flow chuẩn bao gồm các rule, nghiệp vụ,.. của dự án. Tự định nghĩa thiết kế 1 quy tắc chuẩn áp dụng được cho mọi source, sau đó ta có 1 bộ tài liệu chuẩn để học hỏi đồng thời khi tiếp cận các dự án khác có thể nhờ AI nghiên cứu và tạo ra bộ tài liệu tương tự để bản thân có thể nhanh chóng nắm bắt thông tin dự án, làm việc hiệu quả.


- Mỗi request có: logic xử lý, load data, thời điểm truy cập khác nhau -> response time 1 request cũng sẽ khác nhau, tài nguyên tiêu tốn để xử lý cho mỗi request cũng sẽ khác nhau
	- 1 hệ thống sẽ có cấu hình riêng, tài nguyên hệ thống như ram, cpu, băng thông,... tối đa sẽ xử lý 1 số lượng request 
	Làm sao tôi có thể tính toán 1 request tiêu tốn tài nguyên nào ? bao nhiêu tài nguyên ? cấu hình hệ thống sử dụng tài nguyên để xử lý request, vậy làm sao tôi tính toán được hệ thống tối 
	đa có thể xử lý bao nhiêu request đó trong 1 thời điểm ? khiến request bị timeout, request error
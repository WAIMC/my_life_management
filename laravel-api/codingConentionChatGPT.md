Cảm ơn bạn. Mình sẽ tổng hợp lại toàn bộ coding convention Laravel CRUD này thành một tài liệu chuẩn định dạng Markdown/YAML, đồng thời đề xuất cách lưu trữ tối ưu trong dự án để team dễ tiếp cận và chạy demo.

Ngoài ra, mình sẽ hướng dẫn cách tích hợp tài liệu vào source Laravel (ví dụ trong thư mục `docs/`) và cách setup một bảng mẫu `t_admin` để mọi thành viên có thể clone source, làm theo tài liệu và chạy thử.

Mình sẽ quay lại với tài liệu đầy đủ và hướng dẫn cụ thể sau khi hoàn tất.


# Hướng dẫn quy ước mã hóa (coding convention) CRUD trong Laravel

Tài liệu này tổng hợp các quy ước chung và ví dụ mẫu khi xây dựng chức năng CRUD trong Laravel, bao gồm các phần như Migration, Trigger (cơ sở dữ liệu), View (giao diện), Model, Controller, Service, Validate (Form Request), Interface, Repository, Resource, Route. Mỗi phần được nêu rõ cách đặt tên, vị trí file và các quy tắc thường dùng. Ví dụ minh họa chức năng CRUD cho bảng `t_admin` cũng được cung cấp. Tất cả đều theo chuẩn Laravel (PSR-2/PSR-12) để code nhất quán và dễ duy trì.

## 1. Migration

* **Mục đích:** Migration là “phiên bản điều khiển” của cơ sở dữ liệu, giúp định nghĩa và thay đổi cấu trúc bảng. Mỗi migration có hai phương thức `up()` (tạo bảng/cột) và `down()` (hoàn tác).
* **Định danh:** Tên file migration theo mẫu `yyyy_mm_dd_hhmmss_create_table_name_table.php`. Ví dụ `create_users_table.php` sẽ tự động tạo bảng `users` nếu đúng quy tắc tên.
* **Tên bảng:** Laravel mặc định dùng **snake\_case** và số nhiều. Ví dụ bảng người dùng là `users`, bài viết là `posts`. Nếu bảng đặc biệt (ví dụ có tiền tố `t_`), nên khai báo trong model bằng `$table = 't_admin'`.
* **Cột:** Tên cột cũng theo **snake\_case** và thường là chữ thường (ví dụ `user_name`, `created_at`). Khóa chính mặc định là cột `id` (auto-increment). Khóa ngoại nên có hậu tố `_id` (ví dụ `user_id` liên kết bảng `users`).
* **Định nghĩa cột:** Sử dụng `$table->id()` cho cột ID, `$table->string('name')`, `$table->text('content')`, `$table->timestamps()` cho `created_at` và `updated_at`, v.v. Ví dụ trong migration:

  ```php
  public function up()
  {
      Schema::create('t_admin', function (Blueprint $table) {
          $table->id();
          $table->string('name', 100)->unique();
          $table->string('email', 150)->unique();
          $table->string('password');
          $table->timestamps();
      });
  }
  ```
* **Trigger cơ sở dữ liệu:** Laravel không hỗ trợ trigger trực tiếp qua Schema Builder. Nếu cần (thường là trường hợp nâng cao), có thể viết SQL thủ công trong migration bằng `DB::statement()` hoặc `DB::unprepared()`. Ví dụ:

  ```php
  DB::unprepared("
      CREATE TRIGGER before_insert_example
      BEFORE INSERT ON `t_admin`
      FOR EACH ROW BEGIN
          -- câu lệnh SQL trigger --
      END
  ");
  ```

  Khi rollback, dùng `DB::unprepared('DROP TRIGGER IF EXISTS `before\_insert\_example`')`. Lưu ý, trong nhiều trường hợp, bạn có thể xử lý logic tương đương qua **Model Events** của Eloquent thay vì trigger (xem ví dụ Model Events).

## 2. View (Giao diện Blade)

* **Vị trí:** Blade files đặt trong thư mục `resources/views`. Nên tổ chức theo thư mục tương ứng với module hoặc controller (ví dụ `resources/views/admins/index.blade.php`).
* **Định danh:** Tên file blade dùng **snake\_case** (chữ thường, nối bằng gạch dưới). Ví dụ: `list_admins.blade.php`, `show_profile.blade.php`. Không dùng dấu cách hay ký tự đặc biệt. Ngoài ra, dùng `.blade.php` làm hậu tố.
* **Nội dung:** Chỉ chứa HTML và cú pháp Blade, tránh viết logic PHP phức tạp trong view. Sử dụng **@extends**, **@section**, **@include** để tái sử dụng layout, template. Định dạng HTML nên gọn gàng, tuân PSR về indent, khoảng cách.
* **Linguistics:** Sử dụng cú pháp đọc được (ví dụ `{{ $admin->name }}` để hiển thị) và `@if`, `@foreach` chuẩn. Chia nhỏ view nếu lớn (vd: view con chứa form, modals, v.v.).

## 3. Model

* **Tên model:** Dùng **PascalCase**, tên số ít (ví dụ `Admin`, `User`). File model đặt trong `app/Models` (với Laravel 8+ mặc định) hoặc `app/` (Laravel cũ).
* **Bảng liên kết:** Theo quy tắc, Eloquent tự dùng bảng snake\_case số nhiều (ví dụ `Admin` sẽ dùng `admins`). Nếu bảng không theo quy tắc (ví dụ `t_admin`), khai báo thêm trong model: `protected $table = 't_admin';`.
* **Các thuộc tính:** Đặt `$fillable` hoặc `$guarded`. Thông thường khai báo `$fillable = ['name', 'email', 'password']` để cho phép mass assignment.
* **Timestamps & Soft Deletes:** Nếu dùng `$table->timestamps()`, model tự có `created_at/updated_at`. Nếu dùng xóa mềm (soft deletes), thêm trait `use SoftDeletes;` và cột `$table->softDeletes()`.
* **Quan hệ (Relation):** Đặt phương thức quan hệ (ví dụ `public function roles() { return $this->belongsToMany(Role::class); }`). Tên hàm quan hệ theo **camelCase**: số ít với `hasOne/belongsTo`, số nhiều với `hasMany/belongsToMany`.
* **Các phương thức khác:** Đặt theo camelCase, mô tả rõ chức năng. Ví dụ `public function fullName()` thay vì `getFullName`.

## 4. Controller

* **Tên controller:** Dùng **PascalCase** và số ít, kết thúc bằng `Controller`. Ví dụ: `AdminController`, `UserController`. Không có khoảng trắng hay ký tự lạ.
* **Vị trí:** Thư mục `app/Http/Controllers/`. Có thể phân biệt controllers Web (`Http/Controllers`) và API (`Http/Controllers/Api`) nếu cần tách route.
* **Loại controller:** Nên dùng **Resource Controller** cho CRUD. Tạo bằng `php artisan make:controller AdminController --api` để sinh các method `index, store, show, update, destroy`. Nếu cần web (có view), dùng `--resource` thay vì `--api`.
* **Action methods:**

  * `index()`: trả về danh sách (GET).
  * `store(Request)` hoặc `store(AdminRequest)`: xử lý tạo (POST).
  * `show($id)`: xem chi tiết (GET).
  * `update(Request)`: cập nhật (PUT/PATCH).
  * `destroy($id)`: xóa (DELETE).
    Không để công việc kinh doanh (business logic) vào controller; controller chỉ điều phối (gọi Service/Repository).
* **Form Request:** Có thể dùng tuỳ chọn `--requests` khi `make:controller` để tạo các lớp Form Request (ví dụ `StoreAdminRequest`, `UpdateAdminRequest`). Điều này tách riêng phần validate dữ liệu ra class chuyên biệt.
* **Phản hồi:** Controller trả dữ liệu JSON (nếu API) hoặc view (nếu web). Có thể sử dụng API Resource (phần dưới) để định dạng JSON đồng nhất.

## 5. Service

* **Mục đích:** Đưa phần xử lý nghiệp vụ (business logic) ra khỏi controller cho sạch sẽ. Service chứa các phương thức thao tác dữ liệu, logic tính toán trước khi lưu/đọc database.
* **Vị trí & tên:** Thư mục `app/Services/`. Tên thường kết thúc bằng `Service`, ví dụ `AdminService`. Mỗi service tương ứng một chức năng/bộ dữ liệu. Có thể tổ chức thêm các thư mục con theo module.
* **Cấu trúc:** Service sẽ gọi Repository (hoặc trực tiếp Model) để thao tác DB. Ví dụ:

  ```php
  namespace App\Services;
  use App\Repositories\Interfaces\AdminRepositoryInterface;

  class AdminService {
      protected $adminRepo;
      public function __construct(AdminRepositoryInterface $adminRepo) {
          $this->adminRepo = $adminRepo;
      }

      public function listAll() {
          return $this->adminRepo->getAll();
      }
      // các phương thức create, update, delete...
  }
  ```

  Controller sẽ gọi Service, giữ controller gọn gàng, dễ test.

## 6. Validate (Form Request)

* **Mục đích:** Xác thực (validation) dữ liệu đầu vào. Laravel khuyến khích tạo lớp Form Request (trong `app/Http/Requests`) cho mỗi hành động cần validate.
* **Tên & vị trí:** Ví dụ `StoreAdminRequest` (cho lưu mới), `UpdateAdminRequest` (cho cập nhật), đặt trong `app/Http/Requests`.
* **Cấu trúc:** Trong class định nghĩa `rules()` trả về mảng quy tắc. Ví dụ:

  ```php
  class StoreAdminRequest extends FormRequest {
      public function rules() {
          return [
              'name' => 'required|string|max:100',
              'email' => 'required|email|unique:t_admin,email',
              'password' => 'required|string|min:6',
          ];
      }
  }
  ```

  Controller chỉ cần type-hint `StoreAdminRequest $request` thay vì `Request`, Laravel tự kiểm tra trước khi vào logic.
* **Tự động tạo:** Khi sử dụng `make:controller` với `--requests`, Laravel sẽ sinh sẵn các file request cho phương thức `store` và `update`. Ví dụ tài nguyên `Post` sẽ có `StorePostRequest` với rule tự sinh từ định nghĩa model (như Laravel Blueprint nêu).

## 7. Interface và Repository (Pattern)

* **Mục đích:** Áp dụng *Repository Pattern* để tách biệt logic truy xuất dữ liệu ra ngoài Service/Controller, thuận lợi khi thay đổi cơ sở dữ liệu hoặc viết unit test. Interface định nghĩa các phương thức, lớp Repository cài đặt tương ứng.
* **Vị trí & tên:** Tạo thư mục `app/Repositories/Interfaces` chứa interface (ví dụ `AdminRepositoryInterface`), và `app/Repositories/Eloquent` chứa implementation (ví dụ `AdminRepository`).
* **Interface:** Định nghĩa các phương thức như `getAll()`, `getById($id)`, `create($data)`, `update($id,$data)`, `delete($id)`. Ví dụ:

  ```php
  namespace App\Repositories\Interfaces;
  interface AdminRepositoryInterface {
      public function getAll();
      public function getById($id);
      public function create(array $data);
      public function update($id, array $data);
      public function delete($id);
  }
  ```
* **Repository (cài đặt):** Class `AdminRepository` implements interface, sử dụng Eloquent để thao tác. Ví dụ:

  ```php
  namespace App\Repositories\Eloquent;
  use App\Models\Admin;
  use App\Repositories\Interfaces\AdminRepositoryInterface;

  class AdminRepository implements AdminRepositoryInterface {
      public function getAll() { return Admin::all(); }
      public function getById($id) { return Admin::findOrFail($id); }
      public function create(array $data) { return Admin::create($data); }
      public function update($id, array $data) {
          $admin = Admin::findOrFail($id);
          $admin->update($data);
          return $admin;
      }
      public function delete($id) { return Admin::destroy($id); }
  }
  ```
* **Binding vào Service Container:** Tạo `RepositoryServiceProvider` (thông qua `php artisan make:provider RepositoryServiceProvider`) và trong đó bind interface với implementation:

  ```php
  $this->app->bind(
      \App\Repositories\Interfaces\AdminRepositoryInterface::class,
      \App\Repositories\Eloquent\AdminRepository::class
  );
  ```

  Khi đó, Laravel tự resolve đúng repository khi gọi `App\Repositories\Interfaces\AdminRepositoryInterface`.

## 8. Resource (API Resource)

* **Mục đích:** Định dạng dữ liệu JSON trả về API theo chuẩn, tách riêng logic chuyển đối tượng thành mảng. Resource là các lớp con của `JsonResource`.
* **Vị trí & tên:** Tạo trong `app/Http/Resources`, ví dụ `AdminResource` cho một Admin, `AdminCollection` cho danh sách nếu cần. Tên thường giống tên model, kết thúc Resource.
* **Cấu trúc:** Trong `toArray($request)`, trả về key-value muốn trả. Ví dụ:

  ```php
  namespace App\Http\Resources;
  use Illuminate\Http\Resources\Json\JsonResource;

  class AdminResource extends JsonResource {
      public function toArray($request) {
          return [
              'id' => $this->id,
              'name' => $this->name,
              'email' => $this->email,
              // các trường khác...
          ];
      }
  }
  ```
* **Sử dụng:** Trong controller, trả về `AdminResource::collection($admins)` hoặc `new AdminResource($admin)`. Điều này giúp client nhận dữ liệu nhất quán.
* **Tự động tạo (Blueprint):** Công cụ Laravel Blueprint có thể tự sinh cả lớp Resource khi định nghĩa trong YAML.

## 9. Route

* **Loại route:** Dùng resource routes cho các controller RESTful. Ví dụ: `Route::apiResource('admins', AdminController::class);` (cho API, tự loại trừ `create/edit`) hoặc `Route::resource('admins', AdminController::class);` (có cả web). Laravel sinh đầy đủ các route `admins.index`, `admins.store`, `admins.show`, `admins.update`, `admins.destroy`.
* **Cách khai báo:** Thường đặt trong `routes/api.php` (hoặc `web.php` nếu giao diện). Ví dụ:

  ```php
  use App\Http\Controllers\AdminController;
  Route::apiResource('admins', AdminController::class);
  ```

  Nếu cần route với tên khác hoặc ẩn một số hành động, có thể dùng `->only([...])`, `->except([...])` hoặc `Route::apiResources([...])`.
* **Đặt tên:** Route tên theo mẫu `resource.action` (ví dụ `admins.index`, `admins.store`), dễ sử dụng khi generate link và trong API client.
* **Middleware & API:** Đảm bảo nhóm route API gắn middleware `api` và/hoặc `auth:sanctum` nếu cần. Đặt prefix (ví dụ `Route::prefix('v1')->group(...)`) cho versioning nếu dự án lớn.

## 10. Ví dụ Demo CRUD cho bảng `t_admin`

Dưới đây là ví dụ minh họa toàn bộ các phần chính cho CRUD bảng `t_admin` (Admin). Bạn có thể dùng làm mẫu để triển khai các bảng khác.

* **Migration (`database/migrations/xxxx_xx_xx_create_t_admin_table.php`):**

  ```php
  <?php
  use Illuminate\Database\Migrations\Migration;
  use Illuminate\Database\Schema\Blueprint;
  use Illuminate\Support\Facades\Schema;

  class CreateTAdminTable extends Migration {
      public function up() {
          Schema::create('t_admin', function (Blueprint $table) {
              $table->id();
              $table->string('name', 100);
              $table->string('email', 150)->unique();
              $table->string('password');
              $table->timestamps();
          });
      }
      public function down() {
          Schema::dropIfExists('t_admin');
      }
  }
  ```

* **Model (`app/Models/Admin.php`):**

  ```php
  <?php
  namespace App\Models;
  use Illuminate\Foundation\Auth\User as Authenticatable;
  use Illuminate\Notifications\Notifiable;

  class Admin extends Authenticatable {
      use Notifiable;
      protected $table = 't_admin';           // do bảng có tên không đúng chuẩn
      protected $fillable = ['name','email','password'];
      protected $hidden = ['password'];
      // Quan hệ, Mutators nếu cần...
  }
  ```

* **Request Validation (`app/Http/Requests/StoreAdminRequest.php`):**

  ```php
  <?php
  namespace App\Http\Requests;
  use Illuminate\Foundation\Http\FormRequest;

  class StoreAdminRequest extends FormRequest {
      public function authorize() { return true; }
      public function rules() {
          return [
              'name' => 'required|string|max:100',
              'email' => 'required|email|unique:t_admin,email',
              'password' => 'required|string|min:6',
          ];
      }
  }
  ```

* **Repository Interface (`app/Repositories/Interfaces/AdminRepositoryInterface.php`):**

  ```php
  <?php
  namespace App\Repositories\Interfaces;
  interface AdminRepositoryInterface {
      public function getAll();
      public function getById($id);
      public function create(array $data);
      public function update($id, array $data);
      public function delete($id);
  }
  ```

* **Repository Implementation (`app/Repositories/Eloquent/AdminRepository.php`):**

  ```php
  <?php
  namespace App\Repositories\Eloquent;
  use App\Models\Admin;
  use App\Repositories\Interfaces\AdminRepositoryInterface;

  class AdminRepository implements AdminRepositoryInterface {
      public function getAll() { return Admin::all(); }
      public function getById($id) { return Admin::findOrFail($id); }
      public function create(array $data) { return Admin::create($data); }
      public function update($id, array $data) {
          $admin = Admin::findOrFail($id);
          $admin->update($data);
          return $admin;
      }
      public function delete($id) { return Admin::destroy($id); }
  }
  ```

* **Service (`app/Services/AdminService.php`):**

  ```php
  <?php
  namespace App\Services;
  use App\Repositories\Interfaces\AdminRepositoryInterface;

  class AdminService {
      protected $adminRepo;
      public function __construct(AdminRepositoryInterface $adminRepo) {
          $this->adminRepo = $adminRepo;
      }
      public function listAll() {
          return $this->adminRepo->getAll();
      }
      public function create($data) {
          return $this->adminRepo->create($data);
      }
      public function show($id) {
          return $this->adminRepo->getById($id);
      }
      public function update($id, $data) {
          return $this->adminRepo->update($id, $data);
      }
      public function delete($id) {
          return $this->adminRepo->delete($id);
      }
  }
  ```

* **Controller REST (`app/Http/Controllers/AdminController.php`):**

  ```php
  <?php
  namespace App\Http\Controllers;
  use App\Services\AdminService;
  use App\Http\Requests\StoreAdminRequest;
  use App\Http\Resources\AdminResource;

  class AdminController extends Controller {
      protected $adminService;
      public function __construct(AdminService $service) {
          $this->adminService = $service;
      }

      public function index() {
          $admins = $this->adminService->listAll();
          return AdminResource::collection($admins);
      }

      public function store(StoreAdminRequest $request) {
          $admin = $this->adminService->create($request->validated());
          return new AdminResource($admin);
      }

      public function show($id) {
          $admin = $this->adminService->show($id);
          return new AdminResource($admin);
      }

      public function update(StoreAdminRequest $request, $id) {
          $admin = $this->adminService->update($id, $request->validated());
          return new AdminResource($admin);
      }

      public function destroy($id) {
          $this->adminService->delete($id);
          return response()->json(['message' => 'Deleted']);
      }
  }
  ```

* **Resource (`app/Http/Resources/AdminResource.php`):**

  ```php
  <?php
  namespace App\Http\Resources;
  use Illuminate\Http\Resources\Json\JsonResource;

  class AdminResource extends JsonResource {
      public function toArray($request) {
          return [
              'id' => $this->id,
              'name' => $this->name,
              'email' => $this->email,
              'created_at' => $this->created_at,
              'updated_at' => $this->updated_at,
          ];
      }
  }
  ```

* **Route (`routes/api.php`):**

  ```php
  use App\Http\Controllers\AdminController;
  Route::apiResource('admins', AdminController::class);
  ```

* **Chạy thử:** Chạy lệnh `php artisan migrate` để tạo bảng. Sau đó `php artisan serve` rồi dùng Postman hoặc Curl để kiểm thử các route, ví dụ:

  * **GET** `http://localhost:8000/api/admins` – lấy danh sách admin (trả về JSON).
  * **POST** `http://localhost:8000/api/admins` với body JSON `{ "name": "...", "email": "...", "password": "..." }` – tạo mới.
  * **PUT/PATCH** `.../api/admins/{id}` – cập nhật.
  * **DELETE** `.../api/admins/{id}` – xóa.
    Hoặc mở `php artisan tinker` và thử `App\Models\Admin::all()` để xem dữ liệu.

## 11. Định dạng YAML cho AI

Để tự động sinh mã CRUD cho bảng mới bằng AI (ví dụ ChatGPT), bạn có thể dùng định dạng YAML chuẩn mô tả cấu trúc bảng. Ví dụ, định nghĩa YAML cho bảng `t_admin` như sau:

```yaml
models:
  Admin:
    name: string:100
    email: string:150 unique
    password: string:255
controllers:
  Admin:
    resource: true
requests:
  StoreAdminRequest:
    name: required|string|max:100
    email: required|email|unique:t_admin,email
    password: required|string|min:6
routes:
  api:
    - resource: admins, AdminController
```

Trong đó:

* **models:** Định nghĩa model và các cột (`<tên_cột>: <kiểu_cột>:<độ_dài>`). Giống cú pháp Laravel Blueprint (cột tự thêm `id`, `timestamps`).
* **controllers:** Đặt `resource: true` để tạo resource controller.
* **requests:** Định nghĩa FormRequest (ở đây `StoreAdminRequest`) và các quy tắc validate tương ứng.
* **routes:** Khai báo resource route.

Sau khi có YAML, bạn đưa vào prompt ChatGPT (hoặc AI khác) như sau:

````
"Dựa theo định nghĩa YAML dưới đây, sinh mã PHP cho Laravel CRUD (Migration, Model, Controller, Service, Request, Repository, Resource, Route) theo quy ước đã nêu.
```yaml
... (YAML ở trên) ...
````

```
ChatGPT sẽ hiểu cấu trúc bảng và quy ước, từ đó tạo mã đầy đủ. Bạn cũng có thể dùng phần hướng dẫn Markdown ở trên như tài liệu tham khảo trong prompt để đảm bảo định dạng nhất quán.

## 12. Hướng dẫn sử dụng tài liệu

- **Sử dụng Markdown:** Tài liệu trên (định dạng Markdown) có thể copy toàn bộ vào prompt ChatGPT hoặc dùng làm tài liệu tham khảo khi phát triển. Ví dụ, bạn copy phần “quy ước chung” và YAML bên trên vào prompt kèm câu lệnh “generate code” để ChatGPT tạo code tuân quy.
- **Sử dụng YAML:** Sử dụng định dạng YAML như ví dụ để nêu chi tiết cấu trúc bảng mới trong prompt. Như trên, ChatGPT hoặc các công cụ tương tự (như Laravel Blueprint) có thể đọc YAML và sinh mã tự động.
- **Lưu ý:** Khi gọi prompt, nên nói rõ mục tiêu (ví dụ: “Tạo CRUD cho bảng [tên bảng] với các cột như bên dưới, theo coding convention Laravel”) và cung cấp quy ước (có thể copy toàn bộ nội dung phần “Nguyên tắc chung” nếu cần).

## 13. Tích hợp tài liệu vào source và chia sẻ nội bộ

- **Trong dự án:** Đặt file Markdown này vào thư mục tài liệu của dự án, ví dụ `docs/laravel-crud-guideline.md` hoặc `resources/docs/`. Đồng thời trong `README.md` hoặc phần tài liệu dự án, thêm liên kết đến file này để mọi lập trình viên mới clone project đều dễ thấy (đề xuất thêm mục **Documentation** hoặc **Docs** trong README:contentReference[oaicite:30]{index=30}).
- **Chia sẻ nội bộ:** Ngoài việc lưu trong code, bạn có thể đưa tài liệu vào Wiki của GitHub/GitLab hoặc nền tảng chung như Notion, Confluence. Đảm bảo quyền truy cập cho nhóm và thêm nhắc nhở đọc quy ước này. Ví dụ, tạo issue hoặc checklist yêu cầu dev mới “Đọc *Laravel CRUD Guidelines*” trong quá trình onboard.
- **Demo sẵn:** Trong repository, có thể thêm ví dụ (như CRUD `t_admin` ở trên) và hướng dẫn chạy (`php artisan migrate`, Postman) để dev thử ngay. Điều này khuyến khích mọi người áp dụng quy ước và kiểm thử ngay từ đầu.

**Tóm lại,** tuân theo quy ước trên sẽ giúp nhóm phát triển Laravel có code đồng nhất, dễ bảo trì và tận dụng tốt AI để sinh mã tự động. Các dev mới khi clone mã nguồn sẽ thấy tài liệu hướng dẫn, chạy ví dụ demo và nhanh chóng hiểu cấu trúc chung của dự án. 

```

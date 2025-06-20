## Tổng quan cấu trúc dự án API

Thiết kế dự án theo kiến trúc **Layered Architecture** (Service Layer, Repository Layer, Domain Layer) kết hợp **Module-based Organization** để dễ mở rộng và bảo trì. Mỗi module (theo tính năng hoặc phạm vi như Master / Management / History) được tổ chức thành các phần: Migration, Model, Repository, Service, Request Validation, Controller, Resource, Route.

Mỗi khi tạo thành phần mới, thực hiện đồng bộ các bước sau:

1. **Migration** (bao gồm table, view, procedure, sequence, trigger) và seed dữ liệu (nếu cần).
2. **Model** (Eloquent) và định nghĩa quan hệ.
3. **Repository** + **Interface** để tách rời logic truy cập dữ liệu.
4. **Service** để chứa Business Logic và validation bổ sung.
5. **Request Validation** (FormRequest) để định nghĩa rule và message.
6. **Controller** để chuyển tiếp request vào service và xử lý response.
7. **Resource** (API Resource) để định dạng đầu ra chuẩn.
8. **Route** khai báo trong file route module.

---

### 1. Quy ước đặt tên và tổ chức thư mục

#### 1.1. Phân chia theo Module

* **App/Modules/{ModuleName}**

  * **Database/** chứa Migrations, Factories, Seeders, SQL Objects (View, Procedure, Trigger, Sequence)
  * **Models/** chứa Eloquent Models
  * **Repositories/** chứa Interface + Implementation
  * **Services/** chứa Service classes (kế thừa `SingletonService`)
  * **Http/Requests/** chứa FormRequest Validation
  * **Http/Controllers/** chứa Controllers
  * **Resources/** chứa API Resources
  * **Routes/** chứa file route (api.php riêng cho module)

Ví dụ: `App/Modules/Category/Master/...` hoặc `App/Modules/Product/Management/...`

#### 1.2. Đặt tên

* **ModuleName:** PascalCase (VD: Category, Product)
* **Submodule/Scope:** `Master`, `Management`, `History` (PascalCase)
* **Table**: snake\_case số ít + `_mst` / `_mgmt` / `_hist` suffix (VD: `category_mst`, `product_mgmt`, `order_hist`)
* **Migration file:** `{yyyy_mm_dd_hhmmss}_{action}_{object}.php` (VD: `2025_06_20_093000_create_category_mst_table.php`)
* **Model:** PascalCase số ít + Scope suffix (VD: `CategoryMaster`, `CategoryHistory`)
* **Repository Interface:** `{ModelName}RepositoryInterface` (VD: `CategoryMasterRepositoryInterface`)
* **Repository Implementation:** `{ModelName}Repository` (VD: `CategoryMasterRepository`)
* **Service:** `{ModelName}Service` (VD: `CategoryMasterService`)
* **FormRequest:** `{ModelName}{Action}Request` (VD: `CategoryMasterStoreRequest`, `CategoryMasterUpdateRequest`)
* **Controller:** `{ModelName}{Scope}Controller` (VD: `CategoryMasterController`)
* **Resource:** `{ModelName}{Scope}Resource` (VD: `CategoryMasterResource`)
* **Route file:** `routes/api_{module_snake}.php` (VD: `api_category.php`)
* **Variables & Methods:** camelCase
* **Constants:** UPPER\_SNAKE\_CASE

---

### 2. Luồng xử lý API

1. **Request** đến **Route** (trong file `routes/api_{module}.php`)
2. **Controller** nhận request, gán FormRequest để validate tự động
3. Chuyển vào **Service** (singleton) xử lý logic:

   * Gọi **Repository** để thao tác DB
   * Gọi các helper, event, hoặc domain logic khác
4. **Service** trả kết quả cho **Controller**
5. **Controller** trả về **Resource** hoặc Collection để format JSON chung:

   * `return new CategoryMasterResource($data);`
   * `return CategoryMasterResource::collection($list);
     `

---

### 3. Chi tiết các thành phần

#### 3.1. Database Objects

* **Migration**: `php artisan make:migration {action}_{object} --path=database/migrations/{Module}/{Scope}`
* **Table**: snake\_case số ít + suffix `_mst` / `_mgmt` / `_hist`
* **View**/Procedure/Trigger/Sequence: viết SQL trong migration `DB::unprepared()` + rollback tương ứng
* **Folder Structure**:

  * `database/migrations/{Module}/{Scope}/Table`
  * `database/migrations/{Module}/{Scope}/View`
  * `database/migrations/{Module}/{Scope}/Procedure`
  * `database/migrations/{Module}/{Scope}/Trigger`
  * `database/migrations/{Module}/{Scope}/Sequence`

#### 3.2. Model (App/Modules/{Module}/Models/{Scope})

* Kế thừa `Model`
* Đặt `protected $table = '...';`, `$fillable`, `$casts` (casts dùng cho boolean, date,...)
* Quan hệ: method camelCase, return kiểu Eloquent Relation, tên method trùng với tên relation

#### 3.3. Repository

* **Interface:** khai báo CRUD + search + paginate
* **Implementation:** implement interface, dùng Model
* Đặt trong `App/Modules/{Module}/Repositories`

#### 3.4. Service (App/Modules/{Module}/Services)

* Kế thừa `SingletonService`
* Chứa hàm tương ứng với action (index(), show(), store(), update(), delete())
* Logic chung: validate additional, gọi repository, handle exception

#### 3.5. Request Validation (App/Modules/{Module}/Http/Requests)

* Tên class `{ModelName}{Action}Request`
* Define `rules()` dựa trên migration: type, length, unique, exists
* Define custom `messages()` dùng `Messages::getMessage(...)`

#### 3.6. Controller (App/Modules/{Module}/Http/Controllers)

* Constructor inject Service
* Methods: index(), show(\$id), store(Request), update(Request, \$id), destroy(\$id)
* Mỗi method comment chuẩn PHPDoc + type hints

#### 3.7. Resource (App/Modules/{Module}/Resources)

* Format trả về: `toArray($request)` chọn các trường cần thiết
* Sử dụng `collection` hoặc `Resource` để chuẩn hóa

#### 3.8. Route (routes/api\_{module}.php)

```php
Route::prefix('categories')->group(function() {
    Route::get('/', [CategoryMasterController::class, 'index']);
    Route::get('/{id}', [CategoryMasterController::class, 'show']);
    Route::post('/', [CategoryMasterController::class, 'store']);
    Route::put('/{id}', [CategoryMasterController::class, 'update']);
    Route::delete('/{id}', [CategoryMasterController::class, 'destroy']);
});
```

* Định nghĩa middleware, versioning (VD: `/v1/categories`)

---

### 4. Mappings & Sơ đồ

```
[Route] --> [Controller] --> [RequestValidation] --> [Service] --> [Repository] --> [Model] --> [DB Objects]
                                                     ↘--> [Domain Logic/Event]
```

* **Scope Mapping**: Mỗi module chia thành 3 scope (Master / Management / History), share cùng Model base nhưng khác table/logic
* **Master**: Dữ liệu chuẩn, build hệ thống
* **Management**: Dữ liệu dùng trong nghiệp vụ của các phòng ban
* **History**: Lưu lịch sử thao tác (audit log)

---

### 5. Hướng dẫn cho Member mới

1. Xem file \`README.md\` ở root để nắm module
2. Với module mới, copy mẫu từ `App/Modules/Example`
3. Tạo migration > model > repository > service > request > controller > resource > route theo convention
4. Chạy `php artisan migrate`, test với Postman, viết Unit test
5. Đọc comments và Messages để nhất quán lỗi và response

---

*Trên đây là bộ quy tắc đầy đủ, chi tiết cho cấu trúc API Laravel, giúp tăng khả năng mở rộng và dễ dàng bảo trì.*

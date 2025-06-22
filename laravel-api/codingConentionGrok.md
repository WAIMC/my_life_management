# Hướng dẫn quy ước mã hóa và kiến trúc cho dự án API Laravel

## Giới thiệu

Tài liệu này cung cấp các quy tắc mã hóa và hướng dẫn kiến trúc để phát triển API sử dụng Laravel, đảm bảo tính nhất quán, khả năng mở rộng, bảo trì và dễ tiếp cận cho các nhà phát triển. Các quy tắc được thiết kế để có thể tái sử dụng cho nhiều nền tảng công nghệ với các điều chỉnh nhỏ, đồng thời áp dụng cụ thể vào dự án API Laravel.

---

## Tổng quan kiến trúc

### Kiến trúc tầng

Sử dụng **kiến trúc tầng (Layered Architecture)** với các tầng:

- **Service Layer:** Chứa logic kinh doanh, phối hợp các hoạt động dữ liệu.
- **Repository Layer:** Trừu tượng hóa truy cập dữ liệu, tách rời Service Layer khỏi nguồn dữ liệu.
- **Domain Layer:** Đại diện cho dữ liệu và mối quan hệ, thông qua các Model.

> Tổ chức theo module (ví dụ: `Master`, `Management`, `History`) để phân chia chức năng.

---

### Luồng yêu cầu và phản hồi

- **Yêu cầu:**  
  `Client → Route → Middleware → Controller → Form Request (Validation) → Service → Repository → Model → Database`

- **Phản hồi:**  
  `Database → Model → Repository → Service → Controller → API Resource → JSON Response`

---

## Các thành phần chính

| Thành phần       | Vai trò                                                                 |
|------------------|-------------------------------------------------------------------------|
| **Route**        | Định nghĩa các endpoint API trong `routes/api.php`.                     |
| **Controller**   | Xử lý yêu cầu HTTP, gọi `Service`, trả về phản hồi qua `API Resource`.  |
| **Form Request** | Xác thực dữ liệu đầu vào, giữ `Controller` gọn gàng.                    |
| **Service**      | Chứa logic kinh doanh, phối hợp với `Repository`.                       |
| **Repository**   | Trừu tượng hóa truy cập dữ liệu, thực hiện `Interface`.                 |
| **Model**        | Đại diện cho bảng cơ sở dữ liệu và mối quan hệ.                         |
| **API Resource** | Chuyển đổi dữ liệu thành JSON chuẩn, che giấu cấu trúc cơ sở dữ liệu.   |

## Quy ước mã hóa

### Tiêu chuẩn mã hóa

- Tuân thủ **PSR-12** để đảm bảo mã nguồn nhất quán và dễ đọc.
- Sử dụng **tiếng Anh số ít** cho tất cả tên (file, class, method, variable, comment).

---

### Quy tắc đặt tên

| Loại                        | Quy tắc        | Ví dụ                             |
|-----------------------------|----------------|-----------------------------------|
| **Class**<br>(Model, Controller, Service) | PascalCase     | `ProductService`, `UserController` |
| **Method, Variable**        | camelCase      | `getAllProducts`, `userName`     |
| **Constant**                | CONSTANT_CASE  | `STATUS_ACTIVE = 1`              |
| **Database Table, Column**  | snake_case     | `product_mgmt`, `rank_order`     |
| **Route File**              | kebab-case     | `user-permissions.php`           |
| **Route Parameter**         | snake_case     | `created_from=2024-01-01`        |

---

### RESTful URL:

- `GET: /api/user-permissions?created_from=2024-01-01&is_active=true`
- `POST: /api/user-permissions`
- `PUT: /api/user-permissions/{id}`
- `DELETE: /api/user-permissions/{id}`

## Bình luận

- Mỗi `method` và `class` phải có bình luận mô tả **mục đích**, **kiểu tham số**, và **kiểu trả về**.

### Ví dụ:

```php
/**
 * Lấy danh sách sản phẩm theo danh mục.
 *
 * @param int $categoryId
 * @return array
 */
public function getProductsByCategory(int $categoryId): array
{
    // Logic
}
```

## Thiết kế cơ sở dữ liệu

### Bảng

- **Tên bảng:** `snake_case`, **số ít**, với hậu tố phân loại như sau:

| Hậu tố     | Ý nghĩa                         | Ví dụ               |
|------------|----------------------------------|----------------------|
| `_mst`     | Dữ liệu chủ (master data)       | `category_mst`       |
| `_mgmt`    | Dữ liệu quản lý (management)    | `product_mgmt`       |
| `_hist`    | Dữ liệu lịch sử (history)       | `order_hist`         |

- **Bảng trung gian (N-N):** Kết hợp tên hai bảng theo thứ tự alphabet.  
  **Ví dụ:** `product_tag_mgmt`

---

### Ví dụ schema bảng: `category_mst`

| Tên cột      | Kiểu dữ liệu           | Thuộc tính                 |
|--------------|------------------------|----------------------------|
| `id`         | `bigIncrements`        | Primary key                |
| `name`       | `string(255)`          |                            |
| `description`| `text`                 | Nullable                   |
| `is_active`  | `boolean`              | Default: true              |
| `created_at` | `timestamp`            |                            |
| `updated_at` | `timestamp`            |                            |

### Cột

#### Quy tắc đặt tên và kiểu cột

| Loại cột     | Quy tắc                         | Ví dụ          |
|--------------|----------------------------------|----------------|
| **Khóa chính**   | `id` sử dụng `bigIncrements` hoặc `ulid` | `id`             |
| **Khóa ngoại**   | `<table_name>_id`             | `category_id`   |
| **Boolean**      | Bắt đầu bằng `is_` hoặc `has_` | `is_active`     |
| **Timestamps**   | Sử dụng chuẩn Laravel          | `created_at`, `updated_at` |

---

#### Kiểu dữ liệu

- **Phân tích kích thước tối thiểu/tối đa** để đảm bảo hiệu quả lưu trữ và tính đúng đắn:
  - `phone_number`: `string`, tối đa 12 ký tự
  - `email`: `string`, tối đa 320 ký tự (theo RFC)

- **Định nghĩa thuộc tính**:
  - `default`: đặt giá trị mặc định khi cần
  - `not null`: áp dụng cho các trường bắt buộc
  - `unique`: áp dụng cho các trường định danh (ví dụ: `email`, `username`) để tránh trùng lặp

### Các đối tượng khác

- **View:** Tiền tố `view_`, ví dụ: `view_category_mst`.
- **Procedure:** Hậu tố `_function`, ví dụ: `get_category_mst_function`.
- **Trigger:** Tiền tố `trigger_[insert|update|delete]_[before|after]_`, ví dụ: `trigger_update_before_product_mgmt`.
- **Sequence:** Hậu tố `_seq`, ví dụ: `product_mgmt_seq`.

---

### Ví dụ view:

```sql
CREATE VIEW view_category_mst AS
SELECT id, name, is_active
FROM category_mst
WHERE is_active = true;
```

### Migration

Lệnh tạo:

php artisan make:migration create_category_mst_table --path=database/migrations/master

Tên file: yyyy_mm_dd_hhmmss_[action]_[component_name]_[component].php, ví dụ: 2025_06_22_175600_create_category_mst_table.php.

Nội dung:

- Sử dụng Schema::create hoặc Schema::table cho bảng.
- Sử dụng DB::statement hoặc DB::unprepared cho view, procedure, trigger, sequence.
- Viết logic rollback trong down().

### Ví dụ migration:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryMstTable extends Migration
{
    public function up()
    {
        Schema::create('category_mst', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_mst');
    }
}
```

### Model

Lệnh tạo:

php artisan make:model Master/CategoryMst --path=app/Models/Master

Quy ước:

- `$table`: Tên bảng, ví dụ:  
  `protected $table = 'category_mst';`

- `$fillable`: Các cột cho phép gán giá trị, ví dụ:  
  `protected $fillable = ['name', 'description', 'is_active'];`

- **Hằng số:** `CONSTANT_CASE`, ví dụ:  
  `public const STATUS_ACTIVE = 1;`

- **Quan hệ:** Tên hàm `camelCase`, ví dụ:

```php
public function products(): HasMany
{
    return $this->hasMany(ProductMst::class);
}
```

- Xử lý ngày giờ: Sử dụng Carbon, ví dụ:
$this->created_at->toIso8601String()

### Controller

Lệnh tạo:

php artisan make:controller Master/CategoryMstController --api --path=app/Http/Controllers/Master

Quy ước:

- Không chứa logic kinh doanh, chỉ gọi Service và trả về API Resource.

Ví dụ:

```php
public function index(CategoryMstRequest $request): CategoryResource
{
    $categories = $this->categoryService->getAllCategories($request->validated());
    return CategoryResource::collection($categories);
}
```

### Service

- **Vị trí:** `app/Services/[Module]/[ServiceName]Service.php`

- **Kế thừa:** `SingletonService` từ `App\Services\SingletonService`

---

### Quy ước:

- Method: `camelCase`, có bình luận mô tả.

Ví dụ:

```php
/**
 * Lấy danh sách danh mục.
 *
 * @param array $data
 * @return array
 */
public function getAllCategories(array $data): array
{
    $validator = (new CommonService())->validationManual(new CategoryMstRequest(), $data);
    if ($validator->fails()) {
        throw new ValidationException($validator);
    }
    return CategoryRepository::getAll($data);
}
```

### Validate Request (Form Request)

Lệnh tạo:

php artisan make:request Master/CategoryMstRequest --path=app/Http/Requests/Master

Quy ước:

- Xác thực dựa trên schema cơ sở dữ liệu.

Ví dụ:

```php
public function rules(): array
{
    return [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'is_active' => 'boolean',
    ];
}
```

### Interface & Repository

#### Interface:

- **Vị trí:** `app/Interfaces/[Module]/[InterfaceName]Interface.php`

Ví dụ:

```php
interface CategoryRepositoryInterface
{
    public function getAll(array $data): array;
}
```

### API Resources

Lệnh tạo:

php artisan make:resource Master/CategoryResource --path=app/Http/Resources/Master

Mục đích: Định dạng dữ liệu JSON, che giấu cấu trúc cơ sở dữ liệu.

Ví dụ:

```php
public function toArray($request): array
{
    return [
        'id' => $this->id,
        'name' => $this->name,
        'isActive' => $this->is_active,
        'createdAt' => $this->created_at->toIso8601String(),
    ];
}
```

### Routing

Vị trí: `routes/api.php`

Quy ước:

- Nhóm theo module với tiền tố, ví dụ:

```php
Route::prefix('master')->group(function () {
    Route::get('categories', [CategoryMstController::class, 'index']);
});
```

### Quản lý cấu hình

- Sử dụng file cấu hình thay vì truy cập trực tiếp biến môi trường (`env()`).

Ví dụ:

Trong `config/api.php`:

```php
return [
    'key' => env('API_KEY', 'default_key'),
];
```
- Truy cập
```config('api.key');```

### Kiểm thử

- **Unit Test:** Kiểm tra các thành phần riêng lẻ (Service, Repository).
- **Feature Test:** Kiểm tra tích hợp API endpoint.
- Sử dụng **cơ sở dữ liệu in-memory (SQLite)** để tăng tốc độ kiểm thử.

Ví dụ:

```php
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    public function test_get_categories()
    {
        $response = $this->get('/api/master/categories');
        $response->assertStatus(200);
    }
}
```

Tài liệu API
Sử dụng Swagger để tạo tài liệu API tự động.

Đảm bảo tài liệu bao gồm:

Mô tả endpoint

Tham số

Định dạng phản hồi

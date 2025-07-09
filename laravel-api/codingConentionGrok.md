# Quy ước mã hóa cho dự án Laravel API

## Mục lục

1. [Giới thiệu](#giới-thiệu)
2. [Tổng quan cấu trúc dự án API](#tổng-quan-cấu-trúc-dự-án-api)
3. [Luồng hoạt động của một API Request](#luồng-hoạt-động-của-một-api-request)
4. [Cấu trúc thư mục chuẩn](#cấu-trúc-thư-mục-chuẩn)
5. [Quy ước chung](#quy-ước-chung)
6. [Thiết kế cơ sở dữ liệu](#thiết-kế-cơ-sở-dữ-liệu)
7. [Migration](#migration)
8. [Model](#model)
9. [Common Response](#common-response)
10. [Handler](#handler)
11. [Middleware](#middleware)
12. [Controller](#controller)
13. [Service](#service)
14. [Validate Request (Form Request)](#validate-request-form-request)
15. [Interface & Repository](#interface--repository)
16. [API Resources](#api-resources)
17. [Routing](#routing)
18. [Các bước thực hiện khi làm việc với API](#các-bước-thực-hiện-khi-làm-việc-với-api)

## Giới thiệu

Tài liệu này định nghĩa các quy ước và kiến trúc chuẩn cho việc phát triển API dựa trên nền tảng Laravel, tuân thủ tiêu chuẩn PSR-2/PSR-12. Mục tiêu là đảm bảo tính nhất quán, dễ mở rộng, bảo trì và dễ tiếp cận cho các thành viên mới.

## Tổng quan cấu trúc dự án API

Dự án được thiết kế theo kiến trúc **Layered Architecture** (Service Layer, Repository Layer, Domain Layer) kết hợp với **Module-based Organization**. Các module được tổ chức theo phạm vi: **Master** (dữ liệu gốc), **Management** (dữ liệu quản lý), và **History** (lịch sử).

## Luồng hoạt động của một API Request

### Luồng xử lý (Request Flow)

`Route` -> `Middleware` -> `Controller` -> `Form Request (Validation)` -> `Service` -> `Repository` -> `Model` -> `Database`

### Luồng trả về (Response Flow)

`Database` -> `Model` -> `Repository` -> `Service` -> `Controller` -> `API Resource (Transformation)` -> `JSON Response`

### Ý nghĩa của các thành phần

- **Route:** Định nghĩa điểm cuối (endpoint) của API.
- **Controller:** Tiếp nhận HTTP request, điều phối request, gọi Service, và trả về response qua API Resource. Không chứa logic nghiệp vụ.
- **Form Request:** Xác thực dữ liệu đầu vào.
- **Service:** Chứa logic nghiệp vụ.
- **Interface & Repository:** Trừu tượng hóa truy xuất dữ liệu.
- **Model:** Đại diện cho bảng trong cơ sở dữ liệu.
- **API Resource:** Biến đổi dữ liệu thành định dạng JSON.
- **Middleware:** Xử lý logic trước/sau khi request đến Controller (xác thực, phân quyền, logging).
- **Handler:** Xử lý exception hoặc sự kiện đặc biệt.

## Cấu trúc thư mục chuẩn

Cấu trúc thư mục được tổ chức theo nghiệp vụ và loại đối tượng, phân loại theo `Master`, `Management`, và `History`.

```plaintext
app/
├── Http/
│   ├── Controllers/
│   │   ├── Master/
│   │   │   └── CategoryMstController.php
│   │   └── Management/
│   │       └── ProductMgmtController.php
│   ├── Middleware/
│   ├── Requests/
│   │   ├── Master/
│   │   │   └── CategoryMstRequest.php
│   │   └── Management/
│   │       └── ProductMgmtRequest.php
│   └── Resources/
│       ├── Master/
│       │   └── CategoryMstResource.php
│       └── Management/
│           └── ProductMgmtResource.php
├── Interfaces/
│   ├── Master/
│   │   └── CategoryMstInterface.php
│   └── Management/
│       └── ProductMgmtInterface.php
├── Repository/
│   ├── Master/
│   │   └── CategoryMstRepository.php
│   └── Management/
│       └── ProductMgmtRepository.php
├── Models/
│   ├── Master/
│   │   └── CategoryMst.php
│   └── Management/
│       └── ProductMgmt.php
├── Providers/
│   └── RepositoryServiceProvider.php
└── Services/
    ├── Master/
    │   └── CategoryMstService.php
    └── Management/
        └── ProductMgmtService.php

database/
└── migrations/
    ├── Table/
    │   ├── Master/
    │   │   └── 2025_06_20_000000_create_category_mst_table.php
    │   └── Management/
    │       └── 2025_06_20_000001_create_product_mgmt_table.php
    └── View/
    └── Procedure/
```

## Quy ước chung

- **Chuẩn code:** Tuân thủ **PSR-12**.
- **Ngôn ngữ:** Sử dụng tiếng Anh số ít cho tên file, class, method, variable, và comment.
- **Quy ước đặt tên:**

| Thành phần            | Quy ước         | Ví dụ                     |
|-----------------------|-----------------|---------------------------|
| Class (Model, Controller, Service...) | PascalCase, số ít | `ProductService`          |
| Method, Variable      | camelCase       | `getAllProducts`          |
| Hằng số (Constants)   | CONSTANT_CASE   | `STATUS_ACTIVE = 1`       |
| Bảng và Cột trong DB  | snake_case      | `product_mgmt`, `rank_order` |
| Tên file Route        | kebab-case, tham số snake_case | `/api/user-permissions?created_from=2024-01-01` |

## Thiết kế cơ sở dữ liệu

- **Table:**
  - **Định danh:** `snake_case`, số ít (Vd: `user`, `product`).
  - **Hậu tố `_mst`:** Dữ liệu gốc (Vd: `category_mst`).
  - **Hậu tố `_mgmt`:** Dữ liệu quản lý (Vd: `product_mgmt`).
  - **Hậu tố `_hist`:** Lịch sử (Vd: `product_mgmt_hist`).
  - **Bảng trung gian (N-N):** Tên gồm 2 bảng, theo thứ tự alphabet (Vd: `product_tag_mgmt`).

- **Column:**
  - **Định danh:** `snake_case`, số ít, không viết tắt, tiếng Anh.
  - **Khóa chính:** `id`, kiểu `bigIncrements` hoặc `ulid`.
  - **Khóa ngoại:** `{table}_id` (Vd: `category_id`).
  - **Boolean:** Tiền tố `is_` hoặc `has_` (Vd: `is_active`).
  - **Timestamps:** `created_at`, `updated_at` kiểu `timestamp`.

- **Procedure:** `{function_name}_{scope}_function` (Vd: `calculate_category_mst_function`).
- **View:** `view_{view_name}_{scope}` (Vd: `view_category_list_mst`).
- **Trigger:** `trigger_{action}_{time}_{action}_{table}_{scope}` (Vd: `trigger_insert_before_insert_category_mst`).
- **Sequence:** `{table}_{scope}_seq` (Vd: `category_mst_seq`).

## Migration

- **Lệnh tạo:**
  ```bash
  php artisan make:migration [create|update]_[component name]_[component] --path=[path]/[sub path]
  ```

- **Tên file:** `yyyy_mm_dd_hhmmss_[action]_[component_name]_[component].php`.
- **Nội dung:**
  - Dùng `Schema::create` hoặc `Schema::table` cho bảng.
  - Dùng `DB::statement` hoặc `DB::unprepared` cho view, procedure, trigger, sequence trong `up()`.
  - Viết logic rollback trong `down()`.

## Model

- **Lệnh tạo:**
  ```bash
  php artisan make:model [model name] --path=[path]/[sub path]
  ```

- **Quy ước:**
  - **Table:** `protected $table = '[table name]';`.
  - **Fillable:** `protected $fillable = ['column1', 'column2'];`.
  - **Hằng số:** `public const [CONST_NAME] = ['active' => 1,...];`.
  - **Quan hệ:** Tên hàm `camelCase`:
    ```php
    public function [tableName](): [hasOne|HasMany|BelongsTo|BelongsToMany]
    {
        return $this->[hasOne|HasMany|BelongsTo|BelongsToMany]([ModelName]::class);
    }
    ```

## Common Response

- **Ý nghĩa:** Chuẩn hóa response API với `$data` (dữ liệu chính) và `$error` (status, code, messages).
- **Định nghĩa:**
  ```php
  namespace App\Traits;

  trait ApiResponse {
      /**
       * Render response api
       *
       * @param mixed $data
       * @param array $error
       * @return Response
       */
      public static function renderResponse(mixed $data, array $error): Response
      {
          list($status, $code, $messages) = $error;

          return response()->json([
              'data' => $data,
              'error' => [
                  'status' => $status,
                  'code' => $code,
                  'messages' => $messages
              ]
          ]);
      }
  }
  ```

## Handler

- **Ý nghĩa:** Xử lý các exception.
- **Định nghĩa:** Trong `app/Exceptions/Handler.php`, dùng `register()` và trait `ApiResponse`:
  ```php
  $this->renderable(function ([ExceptionName] $e, $request) {
      return $this->renderResponse(null, [true, $e->getCode(), $e->getMessage()]);
  });
  ```

## Middleware

- **Ý nghĩa:** Quản lý transaction cho các thao tác ghi dữ liệu.
- **Tạo:**
  ```bash
  php artisan make:middleware DatabaseMiddleware
  ```

- **Đăng ký:** Trong `app/Http/Kernel.php`:
  ```php
  'db.transaction' => \App\Http\Middleware\DatabaseTransaction::class,
  ```

- **Sử dụng:**
  ```php
  Route::middleware(['db.transaction'])->group(function () {
      // Các route POST, PUT, DELETE
  });
  ```

## Controller

- **Lệnh tạo:**
  ```bash
  php artisan make:controller [controllerName]Controller --path=[path]/[sub path]
  ```

- **Định dạng:**
  ```php
  class [ControllerName]Controller extends Controller
  {
      /**
       * [Nhiệm vụ của method]
       * @param [dataType] [param]
       * @return [dataType]
       */
      public function [functionName]([dataType] [param]): [dataType]
      {
          return [ServiceName]::getInstance()->[methodName]([param]);
      }
  }
  ```

## Service

- **Vị trí:** `[path]/[sub path]/[serviceName]Service.php`.
- **Định dạng:**
  ```php
  /**
   * [Nhiệm vụ của method]
   * @param [dataType] [param]
   * @return [dataType]
   */
  public function [functionName]([dataType] [param]): [dataType]
  {
      $variable = [RepositoryName]Repository::[methodName]([param]);
      return $variable ? [ResourceName]Resource::collection($variable) : [];
  }
  ```

## Validate Request (Form Request)

- **Lệnh tạo:**
  ```bash
  php artisan make:request [requestName]Request --path=[path]/[sub path]
  ```

- **Ví dụ Rule:**
  ```php
  public function rules(): array
  {
      return [
          'parent_id' => 'numeric|min:0',
          'name' => 'required|string|min:0|max:50|unique:App\Models\Master\category,name',
          'slug' => 'required|string|min:0|max:50|unique:App\Models\Master\category,slug',
          'description' => 'string|min:0|max:150',
          'status' => 'in:' . implode(',', array_values(Category::CATEGORY_STATUS)),
          'is_display' => 'bool',
          'rank_order' => 'numeric|min:0'
      ];
  }
  ```

- **Message:**
  ```php
  'parent_id.numeric' => Messages::getMessage(
      Messages::E0001,
      ['attributes' => Category::attributes()['parent_id']]
  ),
  ```

## Interface & Repository

- **Interface:**
  ```php
  interface [InterfaceName]Interface {
      public function [methodName]([paramName]);
  }
  ```

- **Repository:**
  ```php
  class [RepositoryName]Repository implements [InterfaceName]Interface {
      // Implement methods
  }
  ```

- **Binding:** Trong `app/Providers/RepositoryServiceProvider.php`:
  ```php
  $this->app->bind(
      \App\Interfaces\Management\[InterfaceName]RepositoryInterface::class,
      \App\Repositories\Management\[RepositoryName]RepositoryRepository::class
  );
  ```

## API Resources

- **Lệnh tạo:**
  ```bash
  php artisan make:resource [resourceName]Request --path=[path]/[sub path]
  ```

- **Ví dụ:**
  ```php
  public function toArray($request): array
  {
      return [
          'id' => $this->id,
          'productName' => $this->name,
          'price' => $this->price,
          'isActive' => $this->is_active,
          'category' => new CategoryResource($this->whenLoaded('category')),
          'createdAt' => $this->created_at->toIso8601String(),
      ];
  }
  ```

## Routing

- **Vị trí:** `routes/api.php`.
- **Ví dụ:**
  ```php
  Route::prefix('department')->group(function () {
      Route::get('list', [DepartmentController::class, 'list']);
      Route::post('store', [DepartmentController::class, 'store']);
      Route::put('update/{id}', [DepartmentController::class, 'update']);
      Route::delete('delete/{id}', [DepartmentController::class, 'delete']);
  });
  ```

## Các bước thực hiện khi làm việc với API

1. Xác định phạm vi (Master, Management, History).
2. Migration (table, view, procedure, sequence, trigger).
3. Model (Eloquent) và định nghĩa quan hệ.
4. Repository + Interface để tách logic truy cập dữ liệu.
5. Service chứa Business Logic và validation.
6. Request Validation (FormRequest) định nghĩa rule và message.
7. Controller chuyển tiếp request vào service và xử lý response.
8. Resource (API Resource) định dạng đầu ra chuẩn.
9. Route khai báo trong file route module.
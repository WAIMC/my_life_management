Tuyệt vời\! Dưới đây là phiên bản tối ưu hóa của tệp `CodingConvention.md`.

Nội dung đã được cấu trúc lại để đi theo một luồng logic hơn, bắt đầu từ các quy ước chung, cấu trúc dự án, sau đó đi sâu vào từng lớp (layer) theo đúng luồng xử lý của một request. Các phần lặp lại đã được gom lại, văn phong được tinh chỉnh cho mạch lạc và dễ đọc hơn, đồng thời các khối mã được định dạng rõ ràng.

Toàn bộ ý nghĩa, quy tắc và ví dụ trong tài liệu gốc đều được giữ lại.

-----

# **Quy ước Mã hóa và Kiến trúc Dự án Laravel API**

Tài liệu này định nghĩa các quy ước, quy tắc và kiến trúc chuẩn cho việc phát triển dự án API sử dụng Laravel. Mục tiêu là đảm bảo tính nhất quán, dễ bảo trì, dễ mở rộng và giúp các thành viên mới nhanh chóng hòa nhập vào dự án.

## **1. Triết lý và Luồng hoạt động**

Dự án được thiết kế theo **Kiến trúc Phân lớp (Layered Architecture)**, kết hợp với việc tổ chức theo **Module (Module-based Organization)**. Mỗi lớp đảm nhiệm một trách nhiệm duy nhất (Single Responsibility Principle) để đảm bảo sự rõ ràng và tách biệt.

#### **Luồng xử lý Request (Request Flow):**

`Route` → `Middleware` → `Controller` → `Form Request (Validation)` → `Service` → `Repository` → `Model` → `Database`

#### **Luồng trả về Response (Response Flow):**

`Database` → `Model` → `Repository` → `Service` → `Controller` → `API Resource (Transformation)` → `JSON Response`

-----

## **2. Quy ước chung**

Tất cả mã nguồn trong dự án phải tuân thủ các quy tắc sau:

  * **Chuẩn Code:** Tuân thủ nghiêm ngặt **PSR-12**.
  * **Ngôn ngữ:** Sử dụng **tiếng Anh, số ít** cho toàn bộ tên file, class, method, biến và comment.
  * **Quy ước Đặt tên (Naming Conventions):**
      * **Class (Model, Controller, Service...):** `PascalCase`, số ít. (Vd: `ProductService`, `CategoryMst`)
      * **Method, Variable:** `camelCase`. (Vd: `getAllProducts`, `productDetail`)
      * **Hằng số (Constants):** `UPPER_CASE_SNAKE_CASE`. (Vd: `const STATUS_ACTIVE = 1;`)
      * **Bảng và Cột trong Database:** `snake_case`, số ít. (Vd: `product_mgmt`, `rank_order`)
      * **File Route:** `kebab-case`. (Vd: `product-management.php`)
      * **URL Endpoint:** `kebab-case`, tham số `snake_case`. (Vd: `/user-permissions/{user_id}`)

-----

## **3. Cấu trúc Thư mục**

Cấu trúc thư mục được tổ chức theo nghiệp vụ (`Master`, `Management`, `History`) và theo từng lớp kiến trúc.

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Master/
│   │   └── Management/
│   ├── Middleware/
│   ├── Requests/
│   │   ├── Master/
│   │   └── Management/
│   └── Resources/
│       ├── Master/
│       └── Management/
├── Interfaces/
│   ├── Master/
│   └── Management/
├── Models/
│   ├── Master/
│   └── Management/
├── Providers/
│   └── RepositoryServiceProvider.php
├── Repository/
│   ├── Master/
│   └── Management/
└── Services/
    ├── Master/
    └── Management/
    └── ... (Tương tự cho History)

database/
└── migrations/
    ├── Table/
    │   ├── Master/
    │   ├── Management/
    │   └── History/
    ├── View/
    └── Procedure/
```

-----

## **4. Thiết kế Cơ sở dữ liệu và Migrations**

#### **4.1. Quy ước Đặt tên (Database)**

  * **Table:**

      * **Định danh:** `snake_case`, số ít. (Vd: `user`, `product`)
      * **Hậu tố `_mst`:** Bảng dữ liệu gốc, cốt lõi (master data). (Vd: `category_mst`)
      * **Hậu tố `_mgmt`:** Bảng dữ liệu quản lý, nghiệp vụ (management data). (Vd: `product_mgmt`)
      * **Hậu tố `_hist`:** Bảng lưu lịch sử thay đổi. (Vd: `product_mgmt_hist`)
      * **Bảng trung gian (N-N):** Tên gồm 2 bảng liên quan, theo thứ tự alphabet. (Vd: `product_tag_mgmt`)

  * **View:** Tiền tố `view_` + tên view + hậu tố phạm vi. (Vd: `view_active_products_mgmt`)

  * **Procedure:** Hậu tố tên function + `_` + phạm vi + `_function`. (Vd: `calculate_revenue_mgmt_function`)

  * **Trigger:** Tiền tố `trigger_` + `[before|after]` + `_[insert|update|delete]` + `_` + tên bảng. (Vd: `trigger_after_update_product_mgmt`)

  * **Sequence:** Hậu tố tên bảng + `_seq`. (Vd: `product_mgmt_seq`)

#### **4.2. Cột (Columns)**

  * **Định danh:** `snake_case`, số ít, tiếng Anh, không viết tắt.
  * **Khóa chính (Primary Key):** `id` (khuyến khích `ULID`) hoặc `bigIncrements`.
  * **Khóa ngoại (Foreign Key):** `[tên_bảng_số_ít]_id`. (Vd: `category_id`). Luôn định nghĩa foreign key constraint để đảm bảo toàn vẹn dữ liệu.
  * **Boolean:** Tiền tố `is_` hoặc `has_`. (Vd: `is_active`, `has_stock`).
  * **Timestamps:** Luôn có `created_at` và `updated_at` kiểu `timestamp`.
  * **Ghi chú (Comment):** Mỗi cột cần có comment ngắn gọn, viết hoa chữ cái đầu, giải thích ý nghĩa.

#### **4.3. Migrations**

  * **Mục đích:** Quản lý phiên bản và thay đổi cấu trúc cơ sở dữ liệu.

  * **Lệnh tạo:**

    ```bash
    php artisan make:migration [action]_[component_name]_[component] --path=database/migrations/[ComponentType]/[Scope]
    ```

      * `[action]`: `create` hoặc `update`.
      * `[component]`: `table`, `view`, `procedure`...
      * `[ComponentType]` và `[Scope]`: `Table/Master`, `View/Management`...

  * **Nội dung:**

      * Sử dụng `Schema::create` hoặc `Schema::table` cho bảng.
      * Sử dụng `DB::statement()` hoặc `DB::unprepared()` cho View, Procedure, Trigger. Luôn đặt trong `up()` và viết logic rollback (Vd: `DROP VIEW IF EXISTS...`) trong `down()`.

-----

## **5. Các thành phần trong Luồng xử lý**

#### **5.1. Routing (`routes/api.php`)**

  * **Nhiệm vụ:** Định nghĩa các điểm cuối (endpoint) và liên kết chúng với Controller.

  * **Quy ước:**

      * Nhóm các route theo nghiệp vụ hoặc tài nguyên bằng `Route::prefix()` và `Route::group()`.
      * Đặt tên cho route bằng `->name()`.
      * Sử dụng các động từ HTTP (`get`, `post`, `put`, `delete`) một cách hợp lý theo chuẩn RESTful.

  * **Ví dụ:**

    ```php
    use App\Http\Controllers\Management\DepartmentController;

    Route::prefix('departments')->name('departments.')->group(function () {
      Route::get('/', [DepartmentController::class, 'list'])->name('list');
      Route::post('/', [DepartmentController::class, 'store'])->name('store');
      Route::put('/{id}', [DepartmentController::class, 'update'])->name('update');
      Route::delete('/{id}', [DepartmentController::class, 'delete'])->name('delete');
    });
    ```

#### **5.2. Middleware**

  * **Nhiệm vụ:** Xử lý các logic chung trước hoặc sau khi request đến Controller (xác thực, phân quyền, logging, CORS, quản lý transaction...).

  * **Quy ước:**

      * Mỗi Middleware chỉ nên thực hiện một nhiệm vụ duy nhất.
      * Đăng ký Middleware trong `app/Http/Kernel.php` và gán vào route hoặc group.

  * **Ví dụ: Middleware quản lý Transaction:**

    1.  Tạo Middleware: `php artisan make:middleware DatabaseTransactionMiddleware`
    2.  Trong `app/Http/Kernel.php`, đăng ký vào `$routeMiddleware`:
        ```php
        'db.transaction' => \App\Http\Middleware\DatabaseTransactionMiddleware::class,
        ```
    3.  Áp dụng cho các route ghi dữ liệu (POST, PUT, DELETE):
        ```php
        Route::middleware(['auth:api', 'db.transaction'])->group(function () {
            Route::post('/products', [ProductMgmtController::class, 'store']);
            Route::put('/products/{id}', [ProductMgmtController::class, 'update']);
        });
        ```

#### **5.3. Form Request (Validation)**

  * **Nhiệm vụ:** Chịu trách nhiệm duy nhất cho việc xác thực (validation) dữ liệu đầu vào.

  * **Lệnh tạo:**

    ```bash
    php artisan make:request [Path]/[RequestName]Request
    ```

      * `[RequestName]`: `[ModelName][ActionName]`. Vd: `CategoryStore`, `ProductUpdate`.
      * `[Path]`: Đường dẫn tương ứng trong `app/Http/Requests`. Vd: `Master`, `Management`.

  * **Quy ước:**

      * Trong phương thức `rules()`, định nghĩa các quy tắc validation. Các quy tắc này phải khớp với ràng buộc trong file migration (kiểu dữ liệu, độ dài, unique...).
      * Trong phương thức `messages()`, tùy chỉnh các thông báo lỗi cho thân thiện.
      * Inject trực tiếp Form Request vào phương thức của Controller để tự động validation.

  * **Ví dụ:**

    ```php
    // app/Http/Requests/Master/CategoryStoreRequest.php
    public function rules(): array
    {
        return [
            'parent_id'   => 'sometimes|numeric|min:0',
            'name'        => 'required|string|max:50|unique:category_mst,name',
            'slug'        => 'required|string|max:50|unique:category_mst,slug',
            'status'      => 'required|in:' . implode(',', CategoryMst::STATUS_LIST),
            'is_display'  => 'required|boolean',
        ];
    }
    ```

#### **5.4. Controller**

  * **Nhiệm vụ:** Tiếp nhận request, điều phối, gọi Service tương ứng và trả về response.

  * **Quy ước:**

      * **KHÔNG** chứa logic nghiệp vụ (business logic).
      * Tên Controller theo tên Model với hậu tố `Controller`. (Vd: `ProductMgmtController`).
      * Giữ cho các phương thức tinh gọn: nhận request, gọi service, trả về response.

  * **Ví dụ:**

    ```php
    use App\Http\Requests\Management\ProductStoreRequest;
    use App\Services\Management\ProductMgmtService;

    class ProductMgmtController extends Controller
    {
        protected $productService;

        public function __construct(ProductMgmtService $productService)
        {
            $this->productService = $productService;
        }

        public function store(ProductStoreRequest $request)
        {
            $product = $this->productService->createProduct($request->validated());
            // Giả sử có 1 Trait Response chuẩn hóa
            return $this->successResponse($product, 201);
        }
    }
    ```

#### **5.5. Service**

  * **Nhiệm vụ:** Là "bộ não" của ứng dụng, chứa toàn bộ logic nghiệp vụ.

  * **Quy ước:**

      * Tên Service theo tên Model với hậu tố `Service`. (Vd: `ProductMgmtService`).
      * Service có thể gọi các Repository hoặc các Service khác.
      * Service được inject vào Controller thông qua constructor (Dependency Injection).

  * **Ví dụ:**

    ```php
    use App\Interfaces\Management\ProductMgmtInterface;
    use App\Resources\Management\ProductMgmtResource;

    class ProductMgmtService
    {
        protected $productRepo;

        public function __construct(ProductMgmtInterface $productRepo)
        {
            $this->productRepo = $productRepo;
        }

        public function createProduct(array $data): ProductMgmtResource
        {
            // Có thể có thêm logic ở đây:
            // - Gửi email thông báo
            // - Ghi log nghiệp vụ
            // - Gọi một service khác để cập nhật kho hàng
            $product = $this->productRepo->create($data);
            return new ProductMgmtResource($product);
        }
    }
    ```

#### **5.6. Interface & Repository**

  * **Nhiệm vụ:** Trừu tượng hóa lớp truy cập dữ liệu, giúp Service không phụ thuộc vào Eloquent hay một nguồn dữ liệu cụ thể.

  * **Interface:**

      * **Nhiệm vụ:** Định nghĩa các "hợp đồng" (các phương thức) mà Repository phải tuân theo.
      * **Tên:** `[ModelName]Interface`. (Vd: `ProductMgmtInterface`).

  * **Repository:**

      * **Nhiệm vụ:** Triển khai (implement) Interface, chứa các truy vấn đến cơ sở dữ liệu (sử dụng Eloquent).
      * **Tên:** `[ModelName]Repository`. (Vd: `ProductMgmtRepository`).

  * **Binding:** Đăng ký việc triển khai Interface trong `app/Providers/RepositoryServiceProvider.php`.

  * **Ví dụ Binding:**

    ```php
    // app/Providers/RepositoryServiceProvider.php
    public function register()
    {
        $this->app->bind(
            \App\Interfaces\Management\ProductMgmtInterface::class,
            \App\Repository\Management\ProductMgmtRepository::class
        );
    }
    ```

#### **5.7. Model**

  * **Nhiệm vụ:** Đại diện cho một bảng trong cơ sở dữ liệu, định nghĩa các mối quan hệ (relationships) và các thuộc tính.

  * **Lệnh tạo:** `php artisan make:model [Path]/[ModelName]`

  * **Quy ước:**

      * Tên Model là `PascalCase` từ tên bảng. (Vd: `product_mgmt` -\> `ProductMgmt`).
      * Khai báo rõ `protected $table = 'table_name';`.
      * Sử dụng `protected $fillable = [...];` để định nghĩa các cột được phép mass-assignment.
      * Các hàm quan hệ đặt tên theo `camelCase`.

  * **Ví dụ:**

    ```php
    // app/Models/Management/ProductMgmt.php
    class ProductMgmt extends Model
    {
        protected $table = 'product_mgmt';

        protected $fillable = ['name', 'price', 'is_active', 'category_id'];

        public function categoryMst(): BelongsTo
        {
            return $this->belongsTo(CategoryMst::class, 'category_id');
        }
    }
    ```

#### **5.8. API Resource**

  * **Nhiệm vụ:** Biến đổi (transform) dữ liệu từ Model/Collection thành định dạng JSON trả về cho client.

  * **Quy ước:**

      * Tách biệt cấu trúc cơ sở dữ liệu khỏi cấu trúc response của API.
      * Cho phép tùy chỉnh key, định dạng dữ liệu và tải các quan hệ một cách có điều kiện.

  * **Lệnh tạo:** `php artisan make:resource [Path]/[ResourceName]Resource`

  * **Ví dụ:**

    ```php
    // app/Http/Resources/Management/ProductMgmtResource.php
    public function toArray($request): array
    {
        return [
            'productId' => $this->id,
            'productName' => $this->name, // Thay đổi key 'name' -> 'productName'
            'price' => (float) $this->price,
            'status' => $this->is_active ? 'active' : 'inactive',
            'category' => new CategoryMstResource($this->whenLoaded('categoryMst')),
            'createdAt' => $this->created_at->toIso8601String(),
        ];
    }
    ```

-----

## **6. Xử lý Lỗi và Chuẩn hóa Response**

Để đảm bảo mọi phản hồi từ API đều nhất quán, ta sử dụng một `Trait` chung và `Handler` để xử lý lỗi.

#### **6.1. Trait `ApiResponse`**

  * **Nhiệm vụ:** Cung cấp một phương thức `renderResponse` để chuẩn hóa cấu trúc JSON trả về.
  * **Ví dụ:**
    ```php
    // app/Traits/ApiResponse.php
    namespace App\Traits;

    trait ApiResponse {
        public function renderResponse(mixed $data, bool $status, int $code, mixed $messages)
        {
            return response()->json([
                'data' => $data,
                'error' => [
                    'status' => $status,
                    'code' => $code,
                    'messages' => $messages
                ]
            ], $code);
        }

        public function successResponse($data, $code = 200)
        {
            return $this->renderResponse($data, false, $code, null);
        }

        public function errorResponse($message, $code)
        {
            return $this->renderResponse(null, true, $code, $message);
        }
    }
    ```

#### **6.2. Exception Handler (`app/Exceptions/Handler.php`)**

  * **Nhiệm vụ:** Bắt các Exception phát sinh trong ứng dụng và sử dụng `ApiResponse` trait để trả về lỗi theo định dạng chuẩn.

  * **Quy ước:**

      * Sử dụng phương thức `register()` để định nghĩa cách xử lý cho từng loại Exception.

  * **Ví dụ:**

    ```php
    // app/Exceptions/Handler.php
    use App\Traits\ApiResponse;
    use Illuminate\Database\Eloquent\ModelNotFoundException;
    use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

    class Handler extends ExceptionHandler
    {
        use ApiResponse;

        public function register()
        {
            $this->renderable(function (NotFoundHttpException $e, $request) {
                if ($request->is('api/*')) {
                    return $this->errorResponse('Resource not found.', 404);
                }
            });

            $this->renderable(function (ModelNotFoundException $e, $request) {
                if ($request->is('api/*')) {
                    return $this->errorResponse('The requested item was not found.', 404);
                }
            });
        }
    }
    ```

-----

## **7. Quy trình Phát triển một Tính năng mới**

Khi bắt đầu một tính năng, hãy thực hiện tuần tự các bước sau để đảm bảo tuân thủ kiến trúc:

1.  **Xác định Phạm vi:** Quyết định tính năng thuộc `Master`, `Management`, hay `History`.
2.  **Migration & Database:**
      * Tạo file migration cho bảng (`table`), view, procedure...
      * Chạy migrate để cập nhật CSDL.
      * (Tùy chọn) Tạo Seeder để có dữ liệu mẫu.
3.  **Model:** Tạo Eloquent Model, định nghĩa `$table`, `$fillable` và các quan hệ (relationships).
4.  **Repository & Interface:**
      * Định nghĩa các phương thức cần thiết trong Interface.
      * Tạo Repository để triển khai (implement) Interface và viết logic truy vấn CSDL.
      * Binding Interface và Repository trong `RepositoryServiceProvider`.
5.  **Service:** Tạo Service, inject Repository, và viết toàn bộ logic nghiệp vụ (business logic).
6.  **Validation:** Tạo Form Request để định nghĩa các `rules` và `messages` cho dữ liệu đầu vào.
7.  **Controller:** Tạo Controller, inject Service. Viết các phương thức để nhận Form Request, gọi Service và trả về response.
8.  **API Resource:** Tạo Resource để định dạng dữ liệu trả về từ Service/Model.
9.  **Route:** Khai báo endpoint trong file `routes/api.php`, trỏ đến phương thức trong Controller.
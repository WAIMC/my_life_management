### Kế hoạch hành động tiếp theo Để thực hiện yêu cầu của bạn, tôi sẽ làm theo các bước sau, tuân thủ nghiêm ngặt kiến trúc và quy ước trong `CodingConvention.md`:

1. **Liệt kê các file migration cho các module Master, Management, History:**
   - Vì không có file migration cụ thể trong tài liệu, tôi sẽ giả định một số bảng mẫu cho từng module để minh họa (ví dụ: `category_mst` cho Master, `product_mgmt` cho Management, `order_hist` cho History).
   - Scan tên bảng và các field từ migration giả định này.

2. **Tạo các file cần thiết cho từng module:**
   - Tạo **Model**, **Interface**, **Repository**, **Service**, **Form Request**, **Controller**, **Resource**, và **Route** cho từng bảng.
   - Đặt file đúng thư mục theo cấu trúc module (Master, Management, History).
   - Đảm bảo naming convention (PascalCase cho class, snake_case cho table/field, camelCase cho method/property).
   - Thêm JSDoc-style comment cho class và method công khai.
   - Tên method CRUD theo chuẩn REST: `index`, `store`, `show`, `update`, `destroy`.
   - Sử dụng Laravel 11 và tách lớp rõ ràng (không dùng mã mặc định).

3. **Thực hiện mẫu cho module Master:**
   - Bắt đầu với module Master, sau đó áp dụng tương tự cho Management và History.
   - Minh họa bằng bảng `category_mst`.

4. **Tự động hóa quy trình:**
   - Lặp lại các bước trên cho các bảng khác trong từng module, đảm bảo tính nhất quán.

5. **Binding Interface-Repository:**
   - Đăng ký binding trong `RepositoryServiceProvider`.

6. **Kiểm tra tính tuân thủ:**
   - Đảm bảo code theo PSR-12, có comment, format đúng chuẩn, và rule validate dựa trên migration.

---

### Sample cho bảng `category_mst` (Module Master)

Dưới đây là ví dụ triển khai cho bảng `category_mst` với các field giả định: `id`, `name`, `description`, `status`.

#### 1. Migration: `database/migrations/Table/Master/2025_06_20_000000_create_category_mst_table.php`
```php
<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryMstTable extends Migration
{
    public function up(): void
    {
        Schema::create('category_mst', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 100)->comment('Category name');
            $table->string('description', 255)->nullable()->comment('Category description');
            $table->unsignedTinyInteger('status')->default(0)->comment('Category status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_mst');
    }
}
```

#### 2. Model: `app/Models/Master/CategoryMst.php`
```php
<?php
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

/**
 * Represents the category_mst table in the database
 */
class CategoryMst extends Model
{
    protected $table = 'category_mst';

    protected $fillable = ['name', 'description', 'status'];

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
}
```

#### 3. Interface: `app/Interfaces/Master/CategoryMstInterface.php`
```php
<?php
namespace App\Interfaces\Master;

interface CategoryMstInterface
{
    /**
     * Retrieve all categories
     * @return mixed
     */
    public function getAll();

    /**
     * Create a new category
     * @param array $data
     * @return mixed
     */
    public function create(array $data);
}
```

#### 4. Repository: `app/Repositories/Master/CategoryMstRepository.php`
```php
<?php
namespace App\Repositories\Master;

use App\Interfaces\Master\CategoryMstInterface;
use App\Models\Master\CategoryMst;

/**
 * Repository for handling category_mst data operations
 */
class CategoryMstRepository implements CategoryMstInterface
{
    /**
     * Retrieve all categories
     * @return mixed
     */
    public function getAll()
    {
        return CategoryMst::all();
    }

    /**
     * Create a new category
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return CategoryMst::create($data);
    }
}
```

#### 5. Service: `app/Services/Master/CategoryMstService.php`
```php
<?php
namespace App\Services\Master;

use App\Interfaces\Master\CategoryMstInterface;
use App\Http\Resources\Master\CategoryMstResource;

/**
 * Service for handling category_mst business logic
 */
class CategoryMstService
{
    protected $repository;

    public function __construct(CategoryMstInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retrieve all categories
     * @return mixed
     */
    public function getAll()
    {
        $categories = $this->repository->getAll();
        return CategoryMstResource::collection($categories);
    }

    /**
     * Create a new category
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        $category = $this->repository->create($data);
        return new CategoryMstResource($category);
    }
}
```

#### 6. Form Request: `app/Http/Requests/Master/CategoryMstStoreRequest.php`
```php
<?php
namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation rules for storing a new category_mst
 */
class CategoryMstStoreRequest extends FormRequest
{
    /**
     * Get validation rules
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
        ];
    }

    /**
     * Custom validation messages
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'status.required' => 'The status field is required.',
        ];
    }
}
```

#### 7. Controller: `app/Http/Controllers/Master/CategoryMstController.php`
```php
<?php
namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Services\Master\CategoryMstService;
use App\Http\Requests\Master\CategoryMstStoreRequest;

/**
 * Controller for handling category_mst HTTP requests
 */
class CategoryMstController extends Controller
{
    protected $service;

    public function __construct(CategoryMstService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of categories
     * @return mixed
     */
    public function index()
    {
        return $this->service->getAll();
    }

    /**
     * Store a new category
     * @param CategoryMstStoreRequest $request
     * @return mixed
     */
    public function store(CategoryMstStoreRequest $request)
    {
        return $this->service->create($request->validated());
    }
}
```

#### 8. Resource: `app/Http/Resources/Master/CategoryMstResource.php`
```php
<?php
namespace App\Http\Resources\Master;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transform category_mst data into a JSON response
 */
class CategoryMstResource extends JsonResource
{
    /**
     * Transform the resource into an array
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'createdAt' => $this->created_at->toIso8601String(),
        ];
    }
}
```

#### 9. Route: `routes/api.php`
```php
<?php
use App\Http\Controllers\Master\CategoryMstController;

Route::prefix('master')->group(function () {
    Route::get('categories', [CategoryMstController::class, 'index']);
    Route::post('categories', [CategoryMstController::class, 'store']);
});
```

#### 10. Binding trong `app/Providers/RepositoryServiceProvider.php`
```php
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Interfaces\Master\CategoryMstInterface::class,
            \App\Repositories\Master\CategoryMstRepository::class
        );
    }
}
```











--------------------------------------------------------------
- Viết câu promt ngắn gọn và chi tiết
- Viết dạng đi vào hành động nhiều hơn là dạng suy nghĩ
- Chia nhỏ yêu cầu thành các bước cụ thể
- Nếu yêu cầu mông lung, phức tạp thì cung cấp thêm ví dụ cụ thể
- Kiểm tra kết quả đâu ra
- Tạo 1 class
	+ Xác định table, field
	+ Tạo prompt cho từng thành phần (model, repo, service,...)
	+ Cung cấp các promt này cho copilot
	+ Thu thập kết quả và xác thực đầu ra
	+ Yêu cầu chỉnh sửa nếu không đúng như mong đợi
	+ lặp lại các thao tác này tiếp theo theo danh sách thế nào ?

- AI giởi hạn bởi token và context. Nếu đưa ra 1 tài liệu quá dài hoặc thiếu thông tin sẽ khiến AI có thể bị miss or bỏ quả các requirement. Dẫn đén AI thường mắc kẹt trong các vòng lặp, or đưa
ra các câu hỏi cần thêm thông tin do AI đã quyên nội dung trao đổi
- Bộ nhớ AI giông như bộ nhớ đệm ngắn hạn hơn là sự hiểu biết thông minh. Nếu không có sự can thiệp cụ thể và liên tục thì tỷ lệ tuân thủ sẽ giảm dần. Khi không có đầy đủ thông tin do user cung
cấp thiếu or phức tạp AI không xử lý được thì AI sẽ sủ dụng các cách phổ biến đã được đào tạo.
- AI giống như 1 trợ lý hơn là 1 tác nhân tác động đến kết quả

=> Tạo template ví dụ cụ thể theo từng câu promt. Sau đó dùng AI để thực hiện tương tự 

------------------------------
STEP
- Note ra ý tưởng
- Tổng hợp, research các nội dung cần thực hiện
- Note ra từng bước thực hiện chi tiết và cụ thể. Có ví dụ minh họa
- Đưa AI generate ra các câu promt
- Yêu cầu AI thực hiện từng câu promt đó
- Thu thập kết quả và xác thực đầu ra như nào là hợp lệ. Nếu pass thì thực hiện bước tiếp theo. Nếu không rollback thay đối sau đó thực hiện lại nếu có nhiều thay đổi sai, sửa nếu
ít thay đổi sai
- Yêu cầu lặp lại các thao tác này cho phạm vi tiếp theo or theo danh sách sẽ được liệt kê trước
------------------------------
Step thực hiện hóa từng bước
- Thiết lập quy ước ban đầu
	+ Thiết lập các quy ước và chuyển đổi vào AI để yêu cầu AI thực hiện theo rule này
	VD: tạo file CodingConvention.md chuyển vào copilot-instructions.md. Chia nhỏ các quy tắc phức tạp thành các câu lệnh đơn giản hơn
	+ Mở dự án liên quan
- Tạo module lặp lại (tự động hóa)
	+ Liệt kê các module, sắp xếp theo thứ tự thực hiện
		VD: master > management > history/master > history/management
	+ Trong từng module, liệt kê danh sách các file migrate theo thứ tự từ trên xuống. 
	Trong mỗi tệp migrate scan nội dung để thu thập và trích xuất tên table và tên field
	+ Tạo loop qua các thành phần: model, repository, inteface, service, validate, controller, resource, route
	thực hiện tạo (nếu chưa có), sửa lại (nếu đã tồn tại) tên folder, file, classs name, nội dung theo quy ước ở file ... (or trích xuất từng cái ra kèm ví dụ) dựa trên tên table, field và
	quy ước đã trích xuất
	+ Yêu cầu tạo 1 bản sample để chính người dùng review và confirm lại các nội dung chỉnh sửa trước khi apply. Nếu bản sample đầu tiên apply hoặc yêu cầu chỉnh sửa trước khi apply
	thì ghi nhận lại và tiếp tục thực hiện tiếp. Nếu lần sau có lặp lại bước này thì kiểm tra đã được apply trước đó. Thực hiện bỏ qua step này.
- Mở rộng và tự động hóa nâng cao
	+ Sử dụng script để điều phối: phát triển 1 script phức tạp hơn để tự động hóa việc tạo các promt, sử dụng công cụ đó để tương tác với AI khác. Yêu cầu thực hiện các bước tiếp theo
	+ Script đó sẽ quản lý dựa trên script promt ban đầu: VD: "Thực hiện lần lượt, tương tự cho các module khác một cách tự động"
- 
Các bước minh họa:

Đầu ra Quét Migration: Hiển thị tên bảng và các trường được trích xuất.

Prompt cho Model: Cung cấp prompt chính xác được sử dụng để tạo model Product, tham chiếu các quy ước cụ thể (ví dụ: App\Modules\Master\Models\Product.php, JSDoc, các thuộc tính fillable, các mối quan hệ).

Mã Model được tạo: Trình bày mã model Product được tạo.

Prompt cho Repository: Hiển thị prompt cho ProductRepositoryInterface và ProductRepository (ví dụ: App\Modules\Master\Repositories\Contracts\ProductRepositoryInterface.php, App\Modules\Master\Repositories\ProductRepository.php, binding trong MasterServiceProvider).

Mã Repository được tạo: Trình bày mã được tạo.

Prompt cho Service: Hiển thị prompt cho ProductService (ví dụ: App\Modules\Master\Services\ProductService.php).

Mã Service được tạo: Trình bày mã được tạo.

Prompt cho Form Request: Hiển thị prompt cho StoreProductRequest và UpdateProductRequest (ví dụ: App\Modules\Master\Http\Requests\Product\StoreProductRequest.php).

Mã Form Request được tạo: Trình bày mã được tạo.

Prompt cho Controller: Hiển thị prompt cho ProductController (ví dụ: App\Modules\Master\Http\Controllers\ProductController.php, các phương thức RESTful, phản hồi tài nguyên).

Mã Controller được tạo: Trình bày mã được tạo.

Prompt cho Resource: Hiển thị prompt cho ProductResource (ví dụ: App\Modules\Master\Http\Resources\ProductResource.php).

Mã Resource được tạo: Trình bày mã được tạo.

Prompt cho Routes: Hiển thị prompt cho các route được nhóm (ví dụ: routes/api.php hoặc routes/master.php).

Định nghĩa Route được tạo: Trình bày mã được tạo.

Xác thực và Bình luận: Đối với mỗi thành phần được tạo, hãy làm nổi bật cách nó tuân thủ các quy ước đã chỉ định (đặt tên, phân lớp, JSDoc, RESTful, v.v.) và những điều chỉnh thủ công (nếu có) đã được yêu cầu. Điều này sẽ cung cấp một cái nhìn thực tế về hiệu suất của Copilot.

Mẫu này không chỉ là việc hiển thị mã được tạo; nó còn là việc minh họa quá trình tương tác với AI, các prompt được sử dụng và mức độ tuân thủ đạt được. Bằng cách hiển thị từng bước tạo mã cho một bảng, nó minh họa tính khả thi và nỗ lực cần thiết. Mẫu này đóng vai trò như một bản thiết kế thu nhỏ cho tác vụ lớn hơn của người dùng. Nó sẽ tiết lộ sự phức tạp của kỹ thuật thiết kế prompt cho từng thành phần và sự cần thiết của việc tinh chỉnh lặp lại. Nó cũng ngụ ý rằng ngay cả với các phương pháp tốt nhất, một số can thiệp thủ công vẫn có thể cần thiết, củng cố ý tưởng về AI như một trợ lý mạnh mẽ chứ không phải là một nhà phát triển hoàn toàn tự động trong kịch bản bị ràng buộc cao này.

Note: Nếu khối lượng promt có nội dung gần giống nhau được lặp lại sẽ tạo ra khối lượng công việc lớn và bị trùng lặp gây lãng phí. Do đó sẽ thực hiện mô hình đào tạo cho AI dựa trên dữ liệu hiện có
Đây là cách tối ưu nhất để nhúng các quy ước, thông tin quan trọng trực tiếp vào kiến thức AI

////////////////////////////////////////
# Content of agent
---
description: 'Laravel API CRUD generator mode with coding convention compliance.'
tools: ['codebase', 'usages', 'vscodeAPI', 'think', 'problems', 'changes', 'testFailure', 'terminalSelection', 'terminalLastCommand', 'openSimpleBrowser', 'fetch', 'findTestFiles', 'searchResults', 'githubRepo', 'extensions', 'runTests', 'editFiles', 'runNotebooks', 'search', 'new', 'runCommands', 'runTasks']
---
Define the purpose of this chat mode and how AI should behave:

# 🎯 Purpose
This mode generates complete Laravel API CRUD modules from a single migration file input.

# ⚙️ Behavior & Focus
- Accept a migration file (or its content) as input via prompt.
- Automatically generate all CRUD components: Migration, Model, Repository, Interface, Service, Controller, Request Validation, Resource, Routes.
- Follow the **Repository-Service-Controller pattern** and convention summary.
- Perform self-checks before output.
- Automatically check and validate generated code for compliance.  
- Detect inconsistencies and fix them before final output.  

# 📋 Rules & Workflow
1. Must strictly follow coding conventions defined in `documents\CodingConvention.md`.  
2. Parse migration file (or its content) as input via prompt to extract the table name, scope (Master/Management/History), and fields.
3. Determine module path (e.g., app/Master for Master scope).
4. Generate all layer files with consistent naming and structure.
5. Validate output against convention summary.
6. Self-check is required before finishing each task (AI must validate its own output).

# 🛠️ Available Operations
- Generate new CRUD modules based on provided specifications.  
- Create migration files with proper schema definitions.
- Extend existing modules with extra fields or relationships.  
- Automatically create + update migration files with rollback support.  
- Ensure consistency between layers (Migration ↔ Model ↔ Repository ↔ Interface ↔ Service ↔ Controller ↔ Request Validation ↔ Resource ↔ Routes).  

# ✅ Self-check Instructions
- Validate: Naming, namespaces, relationships, validation rules, RESTful routes.
- Auto-correct inconsistencies before output. 
////////////////////////////////////////
# Prompt design common
Generate a complete CRUD API module from the following migration file content:
- File: 2024_06_09_023253_create_category_mst_table.php
- Scope: Master
- Schema:
  $table->increments('id');
  $table->string('name', 50);
  $table->string('slug', 50)->unique();
  $table->timestamps();
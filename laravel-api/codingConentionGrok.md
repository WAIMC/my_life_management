Hướng dẫn quy ước mã hóa (Coding Convention) trong dự án Laravel API
Tài liệu này định nghĩa các quy tắc và cấu trúc cho dự án Laravel API, nhằm đảm bảo tính nhất quán, dễ mở rộng, bảo trì, và dễ tiếp cận cho các thành viên mới. Các thành phần bao gồm: Thiết kế cơ sở dữ liệu, Migration, Model, Controller, Service, Validate Request, Repository, Interface, Resource, và Route. Tất cả tuân theo chuẩn PSR-2/PSR-12 của Laravel.

Cấu trúc hoạt động của một luồng API tối ưu
Một luồng API tối ưu được phân chia nhiệm vụ rõ ràng giữa các thành phần:

Route: Định nghĩa endpoint API, ánh xạ đến các phương thức trong controller.
Controller: Nhận request, validate input (sử dụng FormRequest), gọi service xử lý logic nghiệp vụ, trả về response (sử dụng Resource).
Service: Chứa logic nghiệp vụ, tương tác với repository để truy cập dữ liệu.
Repository: Tách biệt logic truy cập dữ liệu, tương tác trực tiếp với model.
Model: Định nghĩa cấu trúc dữ liệu và quan hệ.
Resource: Chuyển đổi dữ liệu model thành định dạng JSON cho API response.
FormRequest: Xử lý validation cho request input.


Thiết kế cơ sở dữ liệu
Table

Định danh: Dùng snake_case, số ít, không dấu, tiếng Anh, viết tắt nếu tên dài.
Phạm vi:
Hậu tố _mst: Bảng master (dữ liệu chung quan trọng, ví dụ: admin, hệ thống).
Hậu tố _mgmt: Bảng management (chức năng quản lý của phòng ban).
Hậu tố _mst_hist hoặc _mgmt_hist: Bảng lịch sử.


Bảng trung gian (Pivot): Đặt tên gồm tên các bảng liên quan, ví dụ: product_tag_mgmt (trung gian giữa product_mgmt và tag_mgmt).

Column

Định danh: Dùng snake_case, số ít, không dấu, tiếng Anh, không viết tắt.
Yêu cầu bắt buộc: Mọi bảng phải có created_at và updated_at (kiểu timestamp).
Khóa chính: Dùng [table_name]_id, kiểu auto-increment.
Khóa ngoại: Dùng hậu tố _id, ví dụ: category_id.
Boolean: Tiền tố is_ hoặc has_, ví dụ: is_active.
Kiểu dữ liệu: Phân tích kích thước tối thiểu/tối đa, ví dụ: phone (12 ký tự), email (320 ký tự). Định nghĩa thuộc tính như nullable, default, unique.
Chú thích: Mô tả ngắn gọn, viết hoa chữ cái đầu, giải thích chi tiết trong tài liệu riêng.

Procedure

Định danh: Dùng snake_case, số ít, không dấu, tiếng Anh.
Hậu tố: [procedure_name]_[mst|mgmt|mst_hist|mgmt_hist]_function.

View

Định danh: Dùng snake_case, số ít, không dấu, tiếng Anh.
Tiền tố: view_[view_name]_[mst|mgmt|mst_hist|mgmt_hist].

Trigger

Định danh: Dùng snake_case, số ít, không dấu, tiếng Anh.
Tiền tố: trigger_[insert|update|delete]_[before|after]_[table_name]_[mst|mgmt|mst_hist|mgmt_hist].

Sequence

Định danh: Dùng snake_case, số ít, không dấu, tiếng Anh.
Hậu tố: [table_name]_[mst|mgmt|mst_hist|mgmt_hist]_seq.


Migration

Tạo file:php artisan make:migration [create|update]_[component_name]_[component] --path=database/migrations/[sub_path]


[create|update]: create cho mới, update cho chỉnh sửa.
[component]: table|view|procedure|trigger|sequence.


Định danh: yyyy_mm_dd_hhmmss_[action]_[component_name]_[component].php.
Sub Path:
Table: \Table\[Master|Management|History\Master|History\Management].
Procedure: \Procedure\[Master|Management|History\Master|History\Management].
View: \View\[Master|Management|History\Master|History\Management].
Trigger: \Trigger\[Master|Management|History\Master|History\Management]\[table_name].
Sequence: \Sequence\[Master|Management|History\Master|History\Management].


Quy tắc:
Dùng Schema::create hoặc Schema::table cho bảng.
Dùng DB::statement hoặc DB::unprepared cho view, procedure, trigger, sequence trong up().
Viết logic rollback trong down() (drop hoặc update ngược lại).




Model

Tạo file:php artisan make:model [ModelName] --path=app/Models/[sub_path]


Định danh: Dùng PascalCase, số ít, dựa trên tên bảng (snake_case → PascalCase).
Sub Path: \Master, \Management, \History\Master, \History\Management.
Thuộc tính:
$table = '[table_name]': Tên bảng theo thiết kế cơ sở dữ liệu.
$fillable = ['column1', 'column2']: Các cột cho phép gán giá trị.
const STATUS = ['active' => 1, 'inactive' => 0]: Hằng số cho trạng thái.


Quan hệ:public function relatedModel(): BelongsTo {
    return $this->belongsTo(RelatedModel::class);
}


Scope:public function scopeActive($query) {
    return $query->where('status', 1);
}




Controller

Tạo file:php artisan make:controller [ControllerName]Controller --path=app/Http/Controllers/[sub_path]


Định danh: [ModelName]Controller, dùng PascalCase.
Sub Path: \Master, \Management, \History\Master, \History\Management.
Quy tắc:
Dùng FormRequest để validate.
Gọi service xử lý logic.
Trả về Resource cho response.
Sử dụng middleware, authentication, authorization khi cần.


Ví dụ:public function store(StoreRequest $request): Resource {
    return new ModelResource($this->service->store($request->validated()));
}




Service

Vị trí: app/Services/[sub_path]/[ServiceName]Service.php.
Định danh: [ModelName]Service, dùng PascalCase.
Sub Path: \Master, \Management, \History\Master, \History\Management.
Quy tắc:
Chứa logic nghiệp vụ.
Tương tác với repository để truy cập dữ liệu.
Inject dependency qua interface.


Ví dụ:public function store(array $data): Model {
    return $this->repository->create($data);
}




Validate Request

Tạo file:php artisan make:request [ModelName][Action]Request --path=app/Http/Requests/[sub_path]


Định danh: [ModelName][Action]Request, ví dụ: CategoryStoreRequest.
Sub Path: \Master, \Management, \History\Master, \History\Management.
Quy tắc:
Dùng rules() để định nghĩa quy tắc validate.
Dùng messages() để tùy chỉnh thông báo lỗi.


Ví dụ:public function rules(): array {
    return ['name' => 'required|string|max:50'];
}




Repository

Vị trí: app/Repositories/[sub_path]/[ModelName]Repository.php.
Định danh: [ModelName]Repository, dùng PascalCase.
Sub Path: \Master, \Management, \History\Master, \History\Management.
Quy tắc: Tách biệt logic truy cập dữ liệu, tương tác với model.
Ví dụ:public function create(array $data): Model {
    return Model::create($data);
}




Interface

Vị trí: app/Repositories/Interfaces/[ModelName]RepositoryInterface.php.
Định danh: [ModelName]RepositoryInterface.
Quy tắc: Định nghĩa contract cho repository.
Ví dụ:public function create(array $data): Model;




Resource

Tạo file:php artisan make:resource [ModelName]Resource --path=app/Http/Resources/[sub_path]


Định danh: [ModelName]Resource.
Sub Path: \Master, \Management, \History\Master, \History\Management.
Quy tắc: Chuyển đổi dữ liệu model thành JSON.
Ví dụ:public function toArray($request): array {
    return ['id' => $this->id, 'name' => $this->name];
}




Route

Vị trí: routes/api.php.
Quy tắc:
Nhóm route theo phạm vi với prefix: master, management, history.


Ví dụ:Route::prefix('master')->group(function () {
    Route::resource('categories', CategoryController::class);
});




Mapping cấu trúc master|history|management

Mỗi phạm vi có thư mục con trong: Models, Controllers, Services, Repositories, Resources, Http/Requests.
Ví dụ:
app/Models/Master/Category.php
app/Http/Controllers/Master/CategoryController.php
app/Services/Master/CategoryService.php




Khi tạo một thành phần mới

Xác định phạm vi (master, management, history).
Tạo Model và Migration.
Tạo Repository và Interface.
Tạo Service.
Tạo Controller.
Tạo FormRequest.
Tạo Resource.
Định nghĩa Route.


Kết luận
Tài liệu này cung cấp một bộ quy tắc đầy đủ, chi tiết, giúp dự án Laravel API có cấu trúc rõ ràng, dễ mở rộng, và bảo trì. Các thành viên mới có thể dễ dàng tiếp cận và triển khai nhờ sự phân chia nhiệm vụ rõ ràng và tính nhất quán trong cách đặt tên, vị trí file, và cách các thành phần tương tác với nhau.

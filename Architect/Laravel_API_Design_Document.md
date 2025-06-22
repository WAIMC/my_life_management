# Tài liệu Thiết kế cho Dự án API Laravel

## Giới thiệu

Tài liệu này tích hợp các quy tắc từ **Quy ước mã hóa & Kiến trúc** và quy trình từ **Phân tích hệ thống từ yêu cầu**, áp dụng cụ thể vào dự án API trên nền tảng Laravel. Nó cung cấp hướng dẫn chi tiết để phát triển API, từ phân tích yêu cầu đến triển khai.

---

## Phân tích Hệ thống

### Lĩnh vực Vấn đề

> [Điền cụ thể dựa trên yêu cầu dự án, ví dụ: "Xây dựng API để quản lý sản phẩm, danh mục và lịch sử giao dịch."]

### Các Bên Liên Quan

> [Điền cụ thể, ví dụ: "Người dùng cuối, quản trị viên, đội ngũ phát triển, đội ngũ vận hành."]

### Yêu cầu

- **Yêu cầu chức năng**:  
  > [Ví dụ: "API phải hỗ trợ CRUD cho sản phẩm, quản lý danh mục và tra cứu lịch sử giao dịch."]

- **Yêu cầu phi chức năng**:  
  > [Ví dụ: "Hệ thống phải xử lý 1000 yêu cầu/giây, đảm bảo bảo mật dữ liệu bằng JWT."]

### Use Case

> [Ví dụ:  
> **Use Case**: Đăng ký sản phẩm mới  
> **Actor**: Quản trị viên  
> **Preconditions**: Đã đăng nhập với quyền quản trị  
> **Main Flow**: Gửi POST với dữ liệu → Xác thực → Lưu DB → Trả JSON  
> **Alternative Flow**: Nếu dữ liệu không hợp lệ, trả về lỗi xác thực]

---

## Kiến trúc Hệ thống

### Kiến trúc Tầng

Sử dụng mô hình **Layered Architecture**:

- **Service Layer**: Chứa logic kinh doanh.
- **Repository Layer**: Truy cập dữ liệu.
- **Domain Layer**: Đại diện dữ liệu và quan hệ.

**Tổ chức theo module**: `Master`, `Management`, `History`.

### Luồng Yêu cầu và Phản hồi

- **Yêu cầu**:  
  `Client → Route → Middleware → Controller → Form Request → Service → Repository → Model → Database`

- **Phản hồi**:  
  `Database → Model → Repository → Service → Controller → API Resource → JSON Response`

### Các Thành phần Chính

| Thành phần     | Vai trò                                                                 |
|----------------|-------------------------------------------------------------------------|
| Route          | Định nghĩa endpoint trong `routes/api.php`                              |
| Controller     | Gọi Service, trả phản hồi JSON thông qua Resource                       |
| Form Request   | Xác thực đầu vào (`Master/CategoryMstRequest.php`)                      |
| Service        | Logic nghiệp vụ, kế thừa `SingletonService`                             |
| Repository     | Truy cập dữ liệu, thực hiện Interface                                   |
| Model          | Đại diện bảng dữ liệu (`Master/CategoryMst.php`)                        |
| API Resource   | Chuyển dữ liệu thành JSON (`CategoryResource.php`)                      |

---

## Cấu trúc Thư mục

- Tổ chức theo module:

  app/Http/Controllers/ → Master/CategoryMstController.php
  app/Http/Requests/ → Master/CategoryMstRequest.php
  app/Services/ → Master/CategoryService.php
  app/Repositories/ → Master/CategoryRepository.php
  app/Interfaces/ → Master/CategoryRepositoryInterface.php
  app/Models/ → Master/CategoryMst.php
  routes/api.php → Nhóm theo tiền tố (Route::prefix('master'))


---

## Quy ước Mã hóa

- Tuân thủ tài liệu **Quy ước mã hóa & Kiến trúc**
- Sử dụng Laravel:
  - `artisan make:*` để tạo nhanh migration, model, controller,...
  - **Eloquent ORM** để xử lý dữ liệu và quan hệ.
  - **Middleware** cho xác thực, phân quyền.
- Binding Repository trong `RepositoryServiceProvider`.

---

## Thiết kế Cơ sở Dữ liệu

### Bảng

- Tên bảng: `snake_case`, số ít.
- Hậu tố:
  - `_mst`: dữ liệu gốc.
  - `_mgmt`: dữ liệu quản lý.
  - `_hist`: dữ liệu lịch sử.

> Ví dụ: `category_mst`, `product_mgmt_hist`

### Cột

| Loại cột     | Quy tắc                    | Ví dụ           |
|--------------|-----------------------------|------------------|
| Khóa chính    | `id`, kiểu `bigIncrements` | `id`             |
| Khóa ngoại    | `<table>_id`               | `category_id`    |
| Boolean       | `is_`, `has_`              | `is_active`      |
| Timestamps    | Laravel mặc định           | `created_at`     |

### Các Đối tượng Khác

- **View**: `view_<name>_<scope>` → `view_category_mst`
- **Procedure**: `<name>_<scope>_function` → `get_category_mst_function`
- **Trigger**: `trigger_<action>_<scope>` → `trigger_update_before_product_mgmt`
- **Sequence**: `<table>_<scope>_seq` → `product_mgmt_seq`

---

## Quy trình Phát triển API

1. **Xác định phạm vi**: Master, Management, History
2. **Tạo migration**:  
   ```bash
   php artisan make:migration create_category_mst_table
Viết logic rollback trong down()

Tạo Model:

Khai báo $fillable, định nghĩa quan hệ.

php
Copy
Edit
protected $fillable = ['name', 'slug'];
Tạo Repository + Interface:

Binding trong RepositoryServiceProvider

Phát triển Service:

Kế thừa SingletonService

Thêm xác thực tùy chỉnh nếu cần

Thiết lập Form Request:

Rule dựa vào schema DB

php
Copy
Edit
'name' => 'required|string|max:50|unique:category_mst,name'
Controller:

Kiểm tra HTTP method, gọi Service, trả JSON

API Resource:

Format đầu ra JSON

php
Copy
Edit
'createdAt' => $this->created_at->toIso8601String()
Định nghĩa Route:

php
Copy
Edit
Route::prefix('master')->group(function () {
    Route::get('categories', [CategoryMstController::class, 'list']);
});
Khuyến nghị Bổ sung
Kiểm thử:

Thêm Unit test, Feature test với PHPUnit

Tài liệu API:

Sử dụng Swagger/OpenAPI để sinh tài liệu tự động

Cấu hình cụ thể:

SingletonService là thiết kế riêng, có thể thay bằng service container hoặc DI chuẩn của Laravel
# Quy ước Mã hóa & Kiến trúc

## Giới thiệu

Tài liệu này định nghĩa các quy tắc mã hóa và nguyên tắc kiến trúc để phát triển hệ thống, đảm bảo tính nhất quán, khả năng mở rộng và dễ bảo trì. Các quy tắc này có thể áp dụng cho nhiều nền tảng công nghệ (ví dụ: Laravel, Spring Boot, Django) với các điều chỉnh nhỏ.

---

## Quy ước Mã hóa

### Ngôn ngữ và Đặt tên

- Sử dụng **tiếng Anh** cho tất cả tên (file, class, method, variable, comment).
- Sử dụng **số ít** cho tên.

### Quy tắc Đặt tên

| Loại                         | Quy tắc        | Ví dụ                          |
|-----------------------------|----------------|---------------------------------|
| Class (Model, Controller...)| PascalCase     | `ProductService`               |
| Method, Variable            | camelCase      | `getAllProducts`               |
| Constant                    | CONSTANT_CASE  | `STATUS_ACTIVE = 1`            |
| Database Table, Column      | snake_case     | `product_mgmt`, `rank_order`   |
| Route File                  | kebab-case     | `user-permissions`             |
| Route Parameter             | snake_case     | `created_from=2024-01-01`      |

### Phương thức RESTful

Sử dụng các phương thức HTTP tiêu chuẩn: `GET`, `POST`, `PUT`, `PATCH`, `DELETE`.

**Ví dụ URL nhất quán:**
- `GET`: `/api/user-permissions?created_from=2024-01-01&is_active=true`
- `POST`: `/api/user-permissions`
- `PUT`: `/api/user-permissions/{123}`
- `DELETE`: `/api/user-permissions/{123}`

### Bình luận và Tài liệu

- Các `method` và `class` phải có **bình luận mô tả** mục đích, kiểu tham số và kiểu trả về.
- Các phần tử **cơ sở dữ liệu** (bảng, cột) cần có **giải thích ngắn gọn**, có thể bổ sung tài liệu chi tiết riêng.

---

## Tiêu chuẩn Mã hóa

- Tuân thủ các tiêu chuẩn mã hóa phổ biến (ví dụ: **PSR-12** cho PHP hoặc tương đương với các ngôn ngữ khác).
- Đảm bảo mã nguồn dễ đọc: sử dụng thụt lề, dấu ngoặc, khoảng trắng **nhất quán**.

---

## Kiến trúc Hệ thống

### Kiến trúc Tầng (Layered Architecture)

- **Service Layer**: Chứa logic kinh doanh, phối hợp hoạt động dữ liệu.
- **Repository Layer**: Trừu tượng hóa truy cập dữ liệu, tách khỏi nguồn dữ liệu cụ thể (DB, API...).
- **Domain Layer**: Đại diện cho lĩnh vực kinh doanh – gồm model, entity.

### Luồng Yêu cầu và Phản hồi

- **Yêu cầu**:  
  `Client → Route → Middleware → Controller → Form Request → Service → Repository → Model → Database`

- **Phản hồi**:  
  `Database → Model → Repository → Service → Controller → API Resource → JSON Response`

### Các Thành phần Chính

| Thành phần     | Vai trò                                                        |
|----------------|---------------------------------------------------------------|
| Route          | Định nghĩa các endpoint API.                                  |
| Controller     | Phối hợp request và response, **không chứa** logic nghiệp vụ. |
| Form Request   | Xác thực dữ liệu đầu vào.                                     |
| Service        | Chứa **logic nghiệp vụ**, phối hợp với Repository.            |
| Repository     | Truy cập dữ liệu, implement Interface.                        |
| Model          | Đại diện bảng DB và các quan hệ.                              |
| API Resource   | Chuyển đổi dữ liệu sang JSON chuẩn.                           |

---

## Cấu trúc Module

Tổ chức dự án thành các module theo chức năng, ví dụ: `Master`, `Management`, `History`.

Mỗi module bao gồm các thư mục con:

- `Migrations`: Quản lý cấu trúc DB.
- `Models`: Đại diện dữ liệu.
- `Repositories`: Truy cập dữ liệu.
- `Services`: Logic nghiệp vụ.
- `Request Validations`: Xác thực input.
- `Controllers`: Xử lý yêu cầu và gọi Service.
- `Resources`: Chuẩn hóa dữ liệu trả ra.
- `Routes`: Định nghĩa endpoint.

---

## Khuyến nghị Bổ sung

- **Kiểm thử**: Thêm Unit Test và Feature Test để đảm bảo chất lượng.
- **Tài liệu API**: Sử dụng công cụ như **Swagger** để tạo tự động.
- **Tái sử dụng**: Tùy biến tên module và cấu hình phù hợp từng nền tảng.


- Vấn đề, các AI huấn luyện dựa trên khối lượng lớn mã nguồn và lựa chọn các "best practices" của cộng đồng để ưu tiên đề xuất
- Nội dung trải dài dễ khiến AI bị miss, chưa chỉ định rõ chi tiết từng thành phần bên trong controller và service
- Sử dụng dependence injection: sử dụng trong constructor injection vd: constructor injection của controller là service, của service là repository, form request inject trực tiếp vào method của controller
-> Mục đích là để dễ kiểm thử, quản lý phụ thuộc, giữ cho service tập trung vào logic nghiệp vụ
# Giải thích ý nghĩa và mục đích sử dụng của các thành phần trong project laravel

## Migration

* **Mục đích:** Migration là “phiên bản điều khiển” của cơ sở dữ liệu, giúp định nghĩa và thay đổi cấu trúc bảng. Mỗi migration có hai phương thức `up()` (tạo bảng/cột) và `down()` (hoàn tác).

## View (Giao diện Blade)

* **Vị trí:** Blade files đặt trong thư mục `resources/views`. Nên tổ chức theo thư mục tương ứng với module hoặc controller (ví dụ `resources/views/admins/index.blade.php`).
* **Định danh:** Tên file blade dùng **snake\_case** (chữ thường, nối bằng gạch dưới). Ví dụ: `list_admins.blade.php`, `show_profile.blade.php`. Không dùng dấu cách hay ký tự đặc biệt. Ngoài ra, dùng `.blade.php` làm hậu tố.
* **Nội dung:** Chỉ chứa HTML và cú pháp Blade, tránh viết logic PHP phức tạp trong view. Sử dụng **@extends**, **@section**, **@include** để tái sử dụng layout, template. Định dạng HTML nên gọn gàng, tuân PSR về indent, khoảng cách.
* **Linguistics:** Sử dụng cú pháp đọc được (ví dụ `{{ $admin->name }}` để hiển thị) và `@if`, `@foreach` chuẩn. Chia nhỏ view nếu lớn (vd: view con chứa form, modals, v.v.).

## Service

* **Mục đích:** Đưa phần xử lý nghiệp vụ (business logic) ra khỏi controller cho sạch sẽ. Service chứa các phương thức thao tác dữ liệu, logic tính toán trước khi lưu/đọc database.
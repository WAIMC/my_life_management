- Tôi muốn tạo đầy đủ chức năng CRUD cho bảng `table name`. Bảng này được thiết kế theo cấu trúc như sau:
vd:
    - id (bigint, UNSIGNED, PK, NOT NULL, UNIQUE, auto-increment, remarks: 連番)
    - amount (DECIMAL, length: 10, decimal places: 0, NOT NULL)
    - donated_at (datetime, NULL)
    - is_deleted (tinyint, UNSIGNED, NOT NULL, default: 0, remarks: 削除フラグ)
    - created_by (int, NULL)
    - created (datetime, NOT NULL, default: CURRENT_TIMESTAMP, remarks: 登録日時)
    - modified_by (int, NULL)
    - modified (datetime, NOT NULL, default: CURRENT_TIMESTAMP, remarks: 更新日時)
or hình ảnh or tài liệu

- Tuân thủ đúng chuẩn kiến trúc và coding convention đã mô tả trong file CodingConvention.md.
Thực hiện với yêu cầu:
    + Tạo migration, model, repository, interface, service, form request (validate), controller, resource, và route cho module này, đúng chuẩn phân lớp `module (history/management)` và naming convention.
    + Đảm bảo các file đặt đúng vị trí của module `Master|Management|History/Master|History/Management`, tên class, tên method, property, comment, format code, rule validate, binding interface-repository, resource response, grouping route, v.v. theo đúng hướng dẫn trong CodingConvention.md.
    + Đảm bảo code có đầy đủ JSDoc-style comment cho các class và method công khai.
    + Đảm bảo service xử lý logic, validate, gọi repository, trả về resource. Như vd trong rule
    + Tên method CRUD theo chuẩn REST
    + Sử dụng laravel phiên bản 11
    + Không dùng mã mẫu Laravel mặc định, phải tách lớp rõ ràng như Convention
    + Thực hiện ưu tiên các quy tắc tôi đã định nghĩa trước, những phạm vi không có trong quy ước thì hãy sử dụng các "mẫu thiết kế phổ biến (common design patterns)".

- Các vấn đề hiện tại là...Hãy đề xuất,..

- Các vấn đề hiện tại là...Hãy đề xuất,..

- Hãy cho tôi biết bạn sẽ làm gì tiếp theo ? kế hoạch hành động tiếp theo của bạn là gì ?
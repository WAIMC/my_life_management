- CodingConvention.md (chuẩn kiến trúc + naming + convention rõ ràng)
- Agent (mô tả mục tiêu, rule, behavior, workflow)
- Prompt mẫu (cách bạn feed input: scope, table, schema, FK, status)
- Một vài module mẫu đã hoàn chỉnh (để AI và Copilot “học” cách triển khai thực tế).
- Migration meta data dạng yaml/json. Có thể tạo từ migrate, file .sql, ERD,... mô tả table, column, fk, status.

**************************
Đề xuất Biểu Mẫu Prompt Chuẩn:

Persona: <Nhập vai trò kỹ thuật cụ thể> <Bạn là một Kỹ sư Backend cao cấp>

Context: <Đường dẫn tới coding convention, các tệp liên quan>

Task Breakdown (Chain-of-Thought): <Liệt kê các bước chi tiết để tạo module>

Schema Input: <JSON string của một bảng cụ thể>

Relationship Mapping: <Hướng dẫn rõ ràng về cách xử lý các foreign_keys, ví dụ: "Bảng 'A' có quan hệ một-một với bảng 'B' qua trường 'B_id', hãy nhúng đối tượng 'B' vào đối tượng 'A'.">

Constraints & Requirements: <Các yêu cầu cụ thể khác, ví dụ: xử lý lỗi, bảo mật, v.v.>

+++
- Trước mỗi lần enter prompt
+ Đính kèm codingConvention và metadata vào context
+ Chọn agent
+ paste propmt + enter
*********************
parse schema + convention → Generate prompt → Feed vào agent -> enter prompt

- Resource
    - Coding convention
    - meta data json schema
    - agent (của github copilot)

Dựa vào coding convention và đọc nội dung file /database/schema/pgsql-schema.json từ dòng
0 -> 458 hãy generate promt cho mỗi object (table) trong meta data json schema:
    + Xác đinh scope
    + Dựa vào quy định forein key trong coding convention để xác định forein key của table đang được trỏ đến table nào, nếu có
    + Tạo các prompt cho từng object (table)
    + Luôn tạo line mới và Fill nội dung vào file projects/my_life_management/laravel-api/temp/promt-common.md. Không replace nội dung cũ
    vd mẫu prompt: 
        Generate a complete CRUD API module from the following migration file content:
        - Scope: master
        - Table: api_mst
        - Column 
            id serial4 NOT NULL,
            "type" int2 DEFAULT '0'::smallint NOT NULL,
            "name" varchar(50) NOT NULL,
            "path" varchar(100) NOT NULL,
            is_active bool DEFAULT false NOT NULL,
            feature_id int4 NOT NULL,
            created_at timestamp(0) NULL,
            updated_at timestamp(0) NULL,


*************************************************************************************************************************************
GIAI ĐOẠN

+++ Giai đoạn 1 (Hiện tại): Triển khai quy trình tạo mã API CRUD. Tập trung vào việc xây dựng nền tảng vững chắc, bao gồm xác thực schema, tự động hóa prompt và tích hợp kiểm tra chất lượng cơ bản.

Giai đoạn 2 (Trung hạn): Mở rộng quy trình để tự động hóa việc tạo ORM (Object-Relational Mapping) và các migration files dựa trên schema. Điều này sẽ giúp đồng bộ hóa giữa lược đồ cơ sở dữ liệu và mã nguồn ứng dụng một cách tự động.   

Giai đoạn 3 (Dài hạn): Tích hợp AI sâu hơn vào quy trình, không chỉ để tạo mã mà còn để viết tài liệu , tái cấu trúc mã  và tự động gỡ lỗi. Mục tiêu là biến AI trở thành một thành viên tích cực của đội ngũ phát triển, không chỉ là một công cụ tạo mã đơn thuần.   


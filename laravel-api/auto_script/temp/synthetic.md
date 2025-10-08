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
parse schema + convention → Generate prompt → Feed vào agent -> enter prompt

*************************************************************************************************************************************
GIAI ĐOẠN

+++ Giai đoạn 1 (Hiện tại): Triển khai quy trình tạo mã API CRUD. Tập trung vào việc xây dựng nền tảng vững chắc, bao gồm xác thực schema, tự động hóa prompt và tích hợp kiểm tra chất lượng cơ bản.

Giai đoạn 2 (Trung hạn): Mở rộng quy trình để tự động hóa việc tạo ORM (Object-Relational Mapping) và các migration files dựa trên schema. Điều này sẽ giúp đồng bộ hóa giữa lược đồ cơ sở dữ liệu và mã nguồn ứng dụng một cách tự động.   

Giai đoạn 3 (Dài hạn): Tích hợp AI sâu hơn vào quy trình, không chỉ để tạo mã mà còn để viết tài liệu , tái cấu trúc mã  và tự động gỡ lỗi. Mục tiêu là biến AI trở thành một thành viên tích cực của đội ngũ phát triển, không chỉ là một công cụ tạo mã đơn thuần.   

**********************************************************************************************************************
- Hiện tại, t mở rộng quy trình tự động hóa ORM. KHi có sự thay đổi database và muốn nhanh chóng apply cho toàn hệ thống: collect DDL -> convert json object (Run shell script file: auto_script/dataSchema/convert_ddl_to_json.py trong container docker) -> run shell script file auto_script/run_generator.sh (file này sẽ run các shell script trong folder auto_gen chứa các shell script tự động generate các module theo json object)

**************************************************************
- Bộ 3: ERD schema, DDL, json schema
    + ERD và DDL có thể sử dụng đồng thời ở dbeaver
    + Convert DDL -> json schema (để apply các tính năng mapping trong code):
        cài đặt package python simple-ddl-parser để convert, coding file, đọc nội dung file DDL, convert qua json schema
**************************************************************

tôi đang sử dụng laravel 11 làm api, hiện tại tôi đã có 1 middleware để generate response api. Tôi cần làm gì để setting api bảo mật, Như: http only, sercure, cookie, same-site, allow method, allow header, allow origin, type authorzation beaver, content type application json, content-length, host, connection,... hãy nghiên cứu phân tích và hướng dẫn tôi thực hiện bảo mật cho api của mình thật chuẩn và tối ưu.
tôi muốn set thủ công tất cả các thành phần để hiểu chúng hơn và dễ tùy chỉnh

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
QUESTION:
tôi là dev, tôi tổng hợp các hành động của dev khi đối mặt và giải quyêt vấn đề một luồng và có thể dự đoán trước như sau:

+ Phân tích yêu cầu và làm rõ vấn đề
+ Đọc và phân tích nội dung để hiểu cấu trúc và logic hiện tại của hệ thống. Ở đây là source code để suy luận logic hiện tại
+ Hiểu từng thành phần đang có hiện tại. Xác định đối tượng cần thay đổi
+ Tìm và lựa chọn giải pháp tốt nhất để xử lý vấn đề đó
+ Xác định mục tiêu sau cùng
+ Lên ý tưởng triển khai, phác thảo ý tưởng một cách chi tiết từng bước thực hiện
+ Yêu cầu AI trợ lý thực thi từng hành động. VD: Tiến hành format, refactor,... lại nội dung (code)
+ Dựa vào kết quả trả về. Kiểm tra lại kết quả so khớp với mục tiêu cần đạt của từng bước. Sau đó, điều chỉnh nội dung yêu cầu AI trợ lý thực hiện lại bước đó cho đúng mục tiêu or tiếp tục thực hiện bước tiếp theo
**************************** Tác tử ReAct (Lý luận + Hành động AI) **********************************************
**************************** Web learn và ngâm cứu AI : https://www.promptingguide.ai/ **************************
**********************************************************

Mindset thao tác CRUD với cơ sở dũ liệu quan hệ
| Loại bảng / Quan hệ         | Create                                                                                   | Read        | Update                                             | Delete                                                                                   |
| --------------------------- | ---------------------------------------------------------------------------------------- | ----------- | -------------------------------------------------- | ---------------------------------------------------------------------------------------- |
| **Bảng độc lập (không FK)** | – (chỉ check dữ liệu chính nó)                                                           | Tìm theo PK | Check bản ghi chính tồn tại trước khi update       | Check bản ghi chính tồn tại trước khi delete                                             |
| **1-1 (phía 1)**            | –                                                                                        | –           | –                                                  | ✅ Check xem phía còn lại (FK) có đang tham chiếu không                                   |
| **1-1 (phía N)**            | ✅ Check tồn tại bản ghi phía 1 (FK `exists`)                                             | –           | ✅ Check tồn tại bản ghi phía 1 trước khi update FK | –                                                                                        |
| **1-N (phía 1)**            | –                                                                                        | –           | –                                                  | ✅ Check xem phía N có đang tham chiếu không (con tồn tại thì chặn)                       |
| **1-N (phía N)**            | ✅ Check tồn tại bản ghi phía 1 (FK `exists`)                                             | –           | ✅ Check tồn tại bản ghi phía 1 trước khi update FK | –                                                                                        |
| **N-N (bảng trung gian)**   | ✅ Check tồn tại từng FK trong 2 bảng liên kết<br>✅ Check uniqueness nếu có rule business | –           | ✅ Check tồn tại từng FK khi update pivot           | – (xóa pivot thường không cần check vì chỉ xóa quan hệ, không ảnh hưởng bản ghi cha/con) |

-> Kiểm tra phụ thuộc trước khi thao tác tiếp theo ở validate. Case sử dụng dữ liệu thay đổi trong luồng logic run time thì check trong service
**********************************************************
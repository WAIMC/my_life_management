# Phân Tích Hệ Thống Từ Yêu Cầu

## Giới thiệu

Tài liệu này cung cấp một quy trình tổng quát để phân tích yêu cầu và thiết kế hệ thống, áp dụng được cho bất kỳ dự án nào – từ ứng dụng web đến hệ thống nhúng. Quy trình này giúp đảm bảo hệ thống được thiết kế đúng với nhu cầu của các bên liên quan và đáp ứng các thuộc tính chất lượng cần thiết.

---

## Quy trình Phân tích Hệ thống

### 1. Hiểu Lĩnh vực Vấn đề *(Understand the Problem Domain)*

- Xác định **vấn đề kinh doanh** hoặc **hoạt động nghiệp vụ** mà hệ thống cần giải quyết.
- Xác định **phạm vi** và **ranh giới hệ thống**, bao gồm:
  - Các chức năng chính.
  - Các giới hạn, ràng buộc (hệ thống, pháp lý, nghiệp vụ).

---

### 2. Xác định Các Bên Liên Quan *(Identify Stakeholders)*

- Liệt kê tất cả các bên có liên quan, ví dụ:
  - Người dùng cuối, quản lý, khách hàng, đội phát triển, QA, DevOps...
- Thu thập nhu cầu và kỳ vọng của họ thông qua:
  - Phỏng vấn.
  - Khảo sát.
  - Workshop/thảo luận nhóm.

---

### 3. Định nghĩa Yêu cầu *(Define Requirements)*

#### a. Yêu cầu Chức năng *(Functional Requirements)*

- Mô tả **các chức năng cụ thể** mà hệ thống cần thực hiện.
- Ví dụ:  
  > "Hệ thống phải cho phép người dùng đăng nhập bằng email và mật khẩu."

#### b. Yêu cầu Phi chức năng *(Non-Functional Requirements)*

- Mô tả các **thuộc tính chất lượng** của hệ thống, bao gồm:

| Thuộc tính        | Mô tả                                                                 |
|-------------------|------------------------------------------------------------------------|
| Hiệu suất          | Thời gian phản hồi, tốc độ xử lý, thông lượng.                         |
| Bảo mật            | Xác thực người dùng, phân quyền, mã hóa dữ liệu.                       |
| Khả năng mở rộng   | Hệ thống có thể xử lý số lượng người dùng tăng theo thời gian.         |
| Khả năng sử dụng   | Giao diện thân thiện, dễ học, dễ dùng.                                 |

---

### 4. Tạo Use Case hoặc User Story *(Create Use Cases or User Stories)*

- Mô tả các tình huống tương tác giữa **người dùng** và **hệ thống**.
- Mỗi use case bao gồm các phần:

| Thành phần        | Mô tả                                                                 |
|-------------------|------------------------------------------------------------------------|
| **Actor**          | Người hoặc hệ thống thực hiện hành động.                               |
| **Preconditions**  | Điều kiện cần thỏa mãn trước khi thực hiện use case.                   |
| **Postconditions** | Trạng thái hệ thống sau khi thực hiện use case.                        |
| **Main Flow**      | Dòng chảy chính của các bước trong use case.                           |
| **Alternative Flow** | Các nhánh phụ, ngoại lệ có thể xảy ra.                             |

---

### 5. Thiết kế Kiến trúc Hệ thống *(Design System Architecture)*

- Chọn mô hình kiến trúc phù hợp với dự án:
  - Kiến trúc tầng (Layered Architecture).
  - Microservices.
  - Event-driven.
  - Client-server...
- Xác định:
  - Các **thành phần chính**.
  - **Giao diện giữa các thành phần**.
  - **Cách các thành phần tương tác** với nhau.
- Vẽ sơ đồ minh họa:
  - UML, ERD, DFD,...

---

### 6. Xem xét Các Thuộc tính Chất lượng *(Consider Quality Attributes)*

Đảm bảo thiết kế thỏa mãn các thuộc tính sau:

- **Khả năng mở rộng**: Dễ dàng tăng dung lượng xử lý/người dùng.
- **Khả năng bảo trì**: Cấu trúc rõ ràng, dễ chỉnh sửa, sửa lỗi.
- **Độ tin cậy**: Giảm thiểu lỗi, đảm bảo thời gian hoạt động cao.
- **Khả năng sử dụng**: Giao diện người dùng đơn giản, dễ tiếp cận.

---

### 7. Lập Tài liệu Phân tích *(Document the Analysis)*

Tài liệu nên bao gồm:

- Mô tả **vấn đề và phạm vi hệ thống**.
- Danh sách **bên liên quan và yêu cầu của họ**.
- Các **Use Case** hoặc **User Story**.
- **Sơ đồ kiến trúc** và mô tả các thành phần hệ thống.

> ✅ *Tài liệu phải rõ ràng, dễ hiểu, và có thể chia sẻ với các bên liên quan.*

---

## Khuyến nghị Bổ sung

- **Xác nhận yêu cầu** với các bên liên quan trước khi chuyển sang giai đoạn thiết kế.
- **Sử dụng công cụ quản lý yêu cầu**, ví dụ:
  - Jira, Confluence, Trello, Notion...
- **Lặp lại quy trình**: Quá trình phân tích nên được cập nhật theo phản hồi, thay đổi từ khách hàng và người dùng cuối.

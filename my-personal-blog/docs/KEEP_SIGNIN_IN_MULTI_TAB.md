# **Tài liệu xử lý đa tab – Quản lý Access Token / Refresh Token trong Client**

Tài liệu mô tả chi tiết toàn bộ luồng xử lý duy trì đăng nhập, refresh token, multi-tab synchronization trong client (cùng origin, cùng browser).
Bao gồm: giai đoạn khởi động, duy trì, phục hồi, hết hạn, lỗi và đăng xuất đồng bộ.

---

## ## **Luồng xử lý tổng quan Client**

Khi truy cập một page bất kỳ và call API trả về **401 Unauthorized** -> gọi api refresh token
  - Nếu không lỗi thực hiện **logic 1**.
  - Nếu lỗi thì **logic 2**: Kiểm tra có tab nào đang cùng hoạt động không ?
    - Nếu có thì **logic 2.1**
    - Nếu không thì **logic 2.2**
---

# **Logic 1 – Token hết hạn**

* Khi API trả về **401** do token hết hạn:

  * Thực hiện **Logic 3** để cập nhật token mới.
  * Sau khi Logic 3 hoàn thành -> Thực hiện lại API bị trả về 401 ban đầu.

---

# **Logic 2 – Token lỗi (refresh thất bại)**

Khi refresh token lỗi (khác với hết hạn token tiêu chuẩn của Logic 1):

### **Kiểm tra trạng thái tab**

* Kiểm tra xem **có tab nào cùng origin đang hoạt động hay không**.

---

## **Logic 2.1 – Khi mở nhiều tab, reload nhiều lần**

Điều kiện: Có tab khác đang hoạt động.

* Lấy thông tin:

  * access token
  * refresh_at_time
  * tạo mới **leader_id** thông qua BroadcastChannel
* Thực hiện **Logic 3**.
* Redirect sang URL đã bị trả về **401**.

### **Nguyên nhân**

Khi mở nhiều tab và reload liên tục:

* Tất cả state trong RAM bị reset (state, timer…)
* Mỗi tab phải call refresh lại để có access token
* Dẫn đến:

  > **Tạo nhiều token mới cùng lúc → không tối ưu**

---

## **Logic 2.2 – Truy cập page lần đầu**

Điều kiện: Không có tab nào khác đang hoạt động hoặc hoạt động nhưng access token + refresh_at_time + leader_id là null.

* Redirect sang **page login**.
* Nếu login trả về 401 → vẫn ở page login (không được phép gọi refresh token tại đây).
* Nếu login success:

  * Thực hiện **Logic 3**.
  * Redirect về URL ban đầu bị trả về 401.

---

# **Logic 3 – Đồng bộ trạng thái đăng nhập giữa các tab**

Khi đăng nhập thành công hoặc refresh token thành công:

* Cập nhật:

  * access token
  * refresh_at_time
  * tạo **tab_id mới** lưu trong sessionStorage (mất khi đóng tab, giữ khi reload)
  * cập nhật **leader_id = tab_id** nếu tab hiện tại đang focus
* Gửi broadcast để đồng bộ cho **tất cả tab cùng origin**.

### Nếu tab hiện tại đang focus:

* Tạo **timer** với giá trị:
  **ttl(access_token) – 10 giây**
* Timer dùng để auto refresh token trước khi hết hạn 10s → đảm bảo trải nghiệm liên tục.

---

# **Logic 4 – Auto Refresh Token (từ Timer)**

Khi timer đến thời điểm chạy:

1. **Kiểm tra tab có đang focus không?**

   * Nếu user đang focus tab khác ngoài hệ thống → không refresh để tiết kiệm tài nguyên.

2. **Kiểm tra leader_id có bằng tab_id không?**

   * leader_id === tab_id ?
   * Điều này quan trọng khi người dùng chia đôi màn hình → nhiều tab focus đồng thời → tránh refresh song song.

### Nếu cả 2 điều kiện đúng:

* Thực hiện refresh token.
* Nếu refresh thành công → quay lại **Logic 3**.
* Nếu lỗi 401 → redirect về login page.

---

# **Logic 5 – Đăng xuất tất cả tab cùng origin**

Khi người dùng chọn logout:

1. Gửi request revoke với:

   * access token trong payload
   * refresh token trong cookie
2. Nếu revoke lỗi → hiển thị thông báo.
3. Nếu thành công:

   * Thực hiện **Logic 3** với dữ liệu rỗng (clear toàn bộ thông tin đăng nhập).
   * Redirect tất cả các tab về login page.

---

# **7. Tổng kết cơ chế**

Giải pháp xử lý toàn bộ các giai đoạn:

* **Khởi động ứng dụng**
* **Duy trì đăng nhập**
* **Phục hồi lỗi**
* **Hết hạn sử dụng token**
* **Đăng xuất đồng bộ nhiều tab**

### **Bảo đảm:**

* **Chỉ duy nhất 1 tab / 1 lần refresh / 1 browser**
* Tránh refresh trùng lặp gây sinh **nhiều token hợp lệ cùng lúc**
* Tối ưu thời gian sử dụng token
* Multi-tab đồng bộ nhanh và nhất quán
* Giảm tài nguyên server và client
* Không xảy ra tình trạng “thừa token hợp lệ” chạy song song

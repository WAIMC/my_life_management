# **Tài liệu xử lý đa tab – Quản lý Access Token / Refresh Token trong Client**

Tài liệu mô tả chi tiết toàn bộ luồng xử lý duy trì đăng nhập, refresh token, multi-tab synchronization trong client (cùng origin, cùng browser).
Bao gồm: giai đoạn khởi động, duy trì, phục hồi, hết hạn, lỗi và đăng xuất đồng bộ.

---

## ## **Luồng xử lý tổng quan Client**

- Mỗi khi khởi tạo xử lý **Logic 10.1**


### **Nguyên nhân**

Khi mở nhiều tab và reload liên tục:

* Tất cả state trong RAM bị reset (state, timer…)
* Mỗi tab phải call refresh lại để có access token
* Dẫn đến:

  > **Tạo nhiều token mới cùng lúc → không tối ưu**



## **Logic 10.1 – logic kiểm tra tình trạng đăng nhập hiện tại**
* Kiểm tra state hiện tại có đang trong tình trạng đăng nhập hợp lệ hay không bằng cách kiểm tra state access_token = string, leader_id = string, refresh_at_time = string -> tình trạng đăng nhập hợp lệ. Ban đầu khởi tạo app thì access_token, leader_id, refresh_at_time là null -> tình trạng đăng nhập không hợp lệ
  * Nếu tình trạng đăng nhập hợp lệ (có thể do user cố tình truy cập page /login): thì kiểm tra page hiện tại không phải là page /login và page hiện tại có dạng /admin/* đúng không ?
    * Nếu đúng thì tiếp tục xử lý tiếp theo
    * Nếu không thì redirect page /admin
  * Nếu tình trạng đăng nhập không hợp lệ (có thể do đăng xuất, mở tab mới, truy cập lần đầu, reload page, close tất cả các tab cùng origin, close browser) thì thực hiện **Logic 10.2**

## **Logic 10.2 – logic chia sẻ dữ liệu login multi tab**
* Kiểm tra các tab khác có hoạt động không ? bằng cách thử gửi broadcast channel kiểm tra các tab khác cùng origin + port + browser có tồn tại và lấy giá trị access token + refresh_at_time + leader_id là string. Nếu không thì không hợp lệ
  * Nếu hợp lệ (nguyên nhân do mở tab mới, reload page mà tab khác vẫn còn hoạt động), thì thực hiện **Logic 10.6**
  * Không Hợp lệ (nguyên nhân do đăng xuất, truy cập lần đầu, close tất cả các tab, close browser), thì thực hiện **Logic 10.3**

## **Logic 10.3 – lỗi 401 do hết hạn token or chưa login or login fail**
* Kiểm tra hiện tại có phải đang ở page /login không ?
  * Nếu có (nguyên nhân lỗi do login fail, đăng xuất) thì để user login lại. Thực hiện **Logic 10.4**
    * Nếu thành công thực hiện logic tiếp theo là redirect về page /admin
  * Nếu không thì (nguyên nhân do hết hạn token, chưa login mà vẫn thực hiện truy cập, reload page or close tab mà không có tab khác cùng duy trì đăng nhập, close browser) thì thực hiện **Logic 10.5**

## **Logic 10.4 – logic login**
* Note: Nếu có lỗi 401 + đang ở page /login thì không được gọi api refresh token. Check handle common lỗi 401 logic có apply logic này không ? nếu thiếu thêm vào.
* Call api login và kiểm tra kết quả
  * Nếu thành công thực hiện **Logic 10.6** + thực hiện tiếp logic tiếp theo
  * Nếu thất bại thì thực hiện **Logic 10.3** và thông báo kết quả lỗi

## **Logic 10.5 – logic refresh token**
* Call api refesh token và kiểm tra kết quả
  * Nếu thành công thì thực hiện **Logic 10.6** và thực hiện lại api bị lỗi status code 401 trước đó bị lỗi
  * Nếu thất bại thực hiện redirect qua page /login

---

## **Logic 10.6 – Đồng bộ trạng thái đăng nhập giữa các tab**

Khi đăng nhập or refresh token thành công or mở tab mới or reload page trong khi tab khác đang còn duy trì đăng nhập or đăng xuất:

* Cập nhật state cho tab hiện tại:
  * access_token
  * refresh_at_time
  * Tạo **tab_id mới** lưu trong sessionStorage nếu chưa có (mất khi đóng tab, giữ khi reload)
  * cập nhật **leader_id = tab_id** nếu tab hiện tại đang focus
* Gửi broadcast channel đến các tab khác để đồng bộ giá trị state như: access_token, leader_id, refresh_at_time cho tất cả tab cùng origin + cùng port + cùng browser.
* Ở tab hiện tại và các tab khác tạo 1 logic khi nhận event gửi dữ liệu từ broadcast channel, thực hiện kiểm dữ liệu access_token, refresh_at_time, leader_id là null thì thực hiện, cập nhật lại state tại tab đó và thực hiện **Logic 10.8**

### Nếu tab hiện tại đang focus:

* Tạo **timer** với giá trị:
  **ttl(access_token) – 10 giây**
* Timer dùng để auto refresh token trước khi hết hạn 10s → đảm bảo trải nghiệm duy trì đăng nhập liên tục.

---

## **Logic 10.7 – Auto Refresh Token (từ Timer)**

Khi timer đến thời điểm chạy:

1. **Kiểm tra tab có đang focus không?**

   * Nếu user đang focus tab khác ngoài hệ thống → không refresh để tiết kiệm tài nguyên.

2. **Kiểm tra leader_id có bằng tab_id không?**

   * leader_id === tab_id ?
   * Điều này quan trọng khi người dùng chia đôi màn hình → nhiều tab focus đồng thời → tránh refresh song song.

### Nếu cả 2 điều kiện đúng:

* Thực hiện refresh token.
* Nếu refresh thành công → quay lại **Logic 10.6**.
* Nếu lỗi 401 → redirect về login page.

---

## **Logic 10.8 – Đăng xuất tất cả tab cùng origin**

Khi người dùng chọn logout:

1. Gửi request revoke với:
  * access token trong auth beaver header request
  * refresh token trong cookie
2. Kiểm tra kết quả logout
  * Nếu revoke lỗi → hiển thị thông báo.
  * Nếu thành công Redirect tất cả các tab về page /login

---

# **Tổng kết cơ chế**

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

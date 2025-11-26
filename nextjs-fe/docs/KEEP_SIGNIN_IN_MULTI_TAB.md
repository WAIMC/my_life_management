# **Tài liệu Xử Lý Đa Tab – Quản Lý Access Token / Refresh Token Trong Client (Phiên Bản Tối Ưu Hóa)**

Tài liệu này mô tả chi tiết luồng xử lý duy trì đăng nhập, refresh token, và đồng bộ multi-tab trong client (cùng origin, cùng browser). Phiên bản này được tối ưu hóa để:
- Giảm thiểu duplicate refresh bằng leader election robust với heartbeat.
- Thêm fallback mechanisms để xử lý edge cases.
- Tích hợp refresh token rotation (nếu server hỗ trợ).
- Cải thiện đồng bộ state sử dụng BroadcastChannel kết hợp localStorage fallback.
- Cover đầy đủ các trường hợp người dùng, đảm bảo seamless experience.

Bao gồm: giai đoạn khởi động, duy trì, phục hồi, hết hạn, lỗi, và đăng xuất đồng bộ.

---

## **Luồng Xử Lý Tổng Quan Client**

- Mỗi khi khởi tạo app (load/reload page), thực hiện **Logic 1.1**.
- Giả định: Access token lưu ở memory/state (không persist). Refresh token lưu ở HttpOnly Secure Cookie (tăng bảo mật). Tab_id lưu ở sessionStorage (mất khi đóng tab, giữ khi reload).

### **Nguyên Nhân Vấn Đề Cũ Và Cải Thiện**
- Khi mở nhiều tab/reload: State reset, dẫn đến multiple refresh → duplicate tokens.
- Cải thiện: Leader election với heartbeat để chỉ 1 tab refresh. Fallback sync qua localStorage nếu broadcast fail. Thêm locking để tránh race conditions.

---

## **Logic 1.1 – Kiểm Tra Tình Trạng Đăng Nhập Hiện Tại**
- Kiểm tra state: access_token (string), leader_id (string), refresh_at_time (timestamp string), tab_id (từ sessionStorage).
  - Ban đầu: Các giá trị null → Không hợp lệ.
- Nếu hợp lệ (có access_token hợp lệ, chưa hết hạn):
  - Kiểm tra page hiện tại: Không phải /login và là /admin/*?
    - Đúng: Tiếp tục flow app.
    - Sai: Redirect /admin.
- Nếu không hợp lệ (đăng xuất, tab mới, reload, close all tabs/browser): Thực hiện **Logic 1.2**.

## **Logic 1.2 – Đồng Bộ Dữ Liệu Login Multi-Tab**
- Tạo tab_id mới nếu chưa có (sessionStorage.setItem('tab_id', crypto.randomUUID())).
- Kiểm tra các tab khác qua BroadcastChannel (channel = 'auth-sync'):
  - Gửi message {type: 'query-state'} và listen response trong timeout (2s).
  - Nếu nhận state hợp lệ (access_token, refresh_at_time, leader_id): Sync state → **Logic 1.6**.
- Nếu không nhận (không tab khác hoạt động): Fallback check localStorage.getItem('last_auth_state') (chứa {refresh_at_time, leader_id} – không lưu token để bảo mật).
  - Nếu có và chưa hết hạn (dựa refresh_at_time): Thực hiện **Logic 1.5** (refresh).
  - Nếu không: Thực hiện **Logic 1.3**.

## **Logic 1.3 – Xử Lý Lỗi 401 (Hết Hạn Token / Chưa Login / Login Fail)**
- Kiểm tra page hiện tại là /login?
  - Có (login fail/đăng xuất): Cho user login lại → **Logic 1.4**.
  - Không (hết hạn/chưa login/reload/close tabs/browser): **Logic 1.5**.
- Note: Không gọi refresh nếu đang ở /login và lỗi 401 (thêm check ở error handler).

## **Logic 1.4 – Logic Login**
- Call API login.
- Thành công: Lưu access_token/refresh_at_time vào state. Nếu server rotate refresh token, update cookie. → **Logic 1.6** + Redirect /admin.
- Thất bại: Thông báo lỗi → **Logic 1.3**.

## **Logic 1.5 – Logic Refresh Token**
- Sử dụng locking (Promise queue) để serialize nếu concurrent calls.
- Call API refresh (sử dụng refresh token từ cookie).
- Thành công: Update access_token/refresh_at_time. Nếu server rotate, update refresh token cookie. Retry API gốc nếu từ lỗi 401. → **Logic 1.6**.
- Thất bại (401/others): Redirect /login.

---

## **Logic 1.6 – Đồng Bộ Trạng Thái Đăng Nhập Giữa Các Tab**
- Cập nhật state tab hiện tại: access_token, refresh_at_time.
- Set leader_id = tab_id nếu tab focused (document.hasFocus()).
- Gửi broadcast {type: 'sync-state', data: {access_token, refresh_at_time, leader_id}}.
- Fallback: Lưu {refresh_at_time, leader_id} vào localStorage.setItem('last_auth_state', JSON.stringify(...)) – expire sau TTL refresh token.
- Tất cả tabs listen broadcast event:
  - Nhận data: Update state nếu hợp lệ → **Logic 1.7** (elect leader nếu cần) + **Logic 1.8** (set timer nếu là leader).
- Nếu đăng xuất: Set state null, xóa localStorage/cookie → Broadcast {type: 'logout'}.

## **Logic 1.7 – Leader Election Với Heartbeat**
- Tất cả tabs: Listen heartbeat từ leader (broadcast {type: 'heartbeat', leader_id} mỗi 5s nếu là leader).
- Nếu không nhận heartbeat trong 7s (timeout): Tabs tự elect – tab với tab_id nhỏ nhất (sort alphabetically) trở thành leader nếu focused hoặc visible (document.visibilityState === 'visible').
- Leader: Chỉ leader refresh. Nếu tab unfocus/close, failover auto qua heartbeat.
- Tránh multiple focus (split screen): Sử dụng Web Locks API nếu hỗ trợ (navigator.locks.request('auth-leader-lock', ...)) để exclusive leader.

## **Logic 1.8 – Auto Refresh Token (Từ Timer)**
- Chỉ nếu leader_id === tab_id và tab visible/focused.
- Set timer: TTL(access_token) - 10s (hoặc min 30s để tránh spam).
- Khi timer fire:
  - Check vẫn là leader và tab active.
  - **Logic 1.5** (refresh).
  - Thành công: **Logic 1.6**.
  - Fail: Broadcast {type: 'refresh-fail'} → All tabs redirect /login.
- Exponential backoff nếu fail retry (1s, 2s, 4s,...).

## **Logic 1.9 – Đăng Xuất Tất Cả Tabs**
- User click logout: Call API revoke (access_token ở header, refresh_token ở cookie).
- Thành công: Set state null, xóa cookie/localStorage/sessionStorage.
- Broadcast {type: 'logout'} → All tabs nhận: Redirect /login.
- Fail: Thông báo, nhưng vẫn local logout.

---

## **Tiêu Chí Cần Đạt Được**
- **Tối ưu thời gian sử dụng token**: Refresh trước 10s hết hạn, kéo dài session seamless.
- **Giảm tải backend**: Chỉ 1 leader/tab refresh, tránh duplicate tokens (heartbeat + locking giảm race conditions).
- **Đồng bộ nhanh/multi-tab**: Broadcast real-time, fallback localStorage cho persistence cross-reload/browser close.
- **Bảo mật cao**: Không lưu sensitive data persist (token ở memory), rotate refresh token, HttpOnly cookie.
- **Robustness**: Handle edge cases (browser crash, multiple focus) với heartbeat/failover, cover 100% scenarios.
- **Tối ưu resource client/server**: Chỉ refresh khi cần (visible/focused), giảm API calls không cần thiết.
- **Seamless UX**: Không gián đoạn (auto-refresh), đồng bộ logout/login cross-tabs.
- **Scalability**: Hỗ trợ nhiều tabs (10+), không phụ thuộc focus duy nhất.

---

## **Cơ Chế Xử Lý Cho Tất Cả Các Trường Hợp Người Dùng Sử Dụng Trên Browser**
Dưới đây là mapping chi tiết cách logic xử lý từng scenario, đảm bảo cover đầy đủ mà không gián đoạn.

- **Đăng nhập lần đầu**: Logic 1.1 (không hợp lệ) → 1.2 (không tab khác) → 1.3 (không ở /login) → 1.5 (refresh fail vì chưa có) → Redirect /login → User login → 1.4 (thành công) → 1.6 (sync, set leader/timer).
- **Mở tab mới**: Logic 1.1 (không hợp lệ) → 1.2 (query broadcast từ tab cũ) → Sync state → 1.6 (update, check leader election nếu focused).
- **Đóng tab (lần đầu truy cập)**: Không ảnh hưởng (chưa state). Nếu đóng leader, heartbeat timeout → Các tab còn elect leader mới → Continue refresh.
- **Đóng các tab khác cùng host**: Nếu đóng non-leader: Không ảnh hưởng. Nếu đóng leader: Heartbeat fail → Failover elect leader mới từ tabs còn lại.
- **Reload page**: State reset → 1.1 (không hợp lệ) → 1.2 (broadcast từ tabs khác hoặc fallback localStorage) → Sync → 1.6.
- **Đóng browser**: State/sessionStorage mất. Mở lại: 1.1 → 1.2 (fallback localStorage nếu chưa expire, hoặc cookie refresh) → 1.5 (refresh nếu cookie còn) hoặc redirect /login nếu hết hạn.
- **Hết hạn token**: Timer ở leader fire trước 10s → 1.8 → 1.5 (refresh) → 1.6 (sync all tabs).
- **Hết hạn cookie (refresh token)**: Khi refresh (1.5) fail → Broadcast fail → All tabs redirect /login.
- **Đăng xuất 1 tab khi dùng đồng thời nhiều tab**: 1.9 (revoke) → Broadcast logout → All tabs clear state + redirect /login.
- **Sử dụng nhiều tab cùng lúc**: Đồng bộ qua broadcast. Leader election đảm bảo chỉ 1 refresh. Heartbeat handle multiple focus/split screen.
- **Mở lại browser**: Giống đóng browser. Nếu localStorage/cookie còn hạn → Auto-refresh và sync. Nếu không → Login.
- **Browser crash**: Tương tự đóng browser. Tabs khác detect leader mất qua heartbeat → Elect mới.
- **Tab background (không focus)**: Không refresh (check focused/visible), nhưng sync state khi broadcast.
- **Multiple browsers/devices**: Không hỗ trợ (khác origin/browser), cần server-side sync (không trong scope).
- **Lỗi network tạm thời**: Exponential backoff ở 1.5/1.8, retry 3 lần trước fail.
- **User switch tabs nhanh**: Leader có thể chuyển (dựa focus + heartbeat), nhưng locking tránh duplicate.

Logic này đảm bảo 100% coverage, với testing recommendations: Sử dụng console.log trace, dev tools simulate close/reload, và verify no duplicate API calls.
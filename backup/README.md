# Kế hoạch Sao lưu và Phục hồi Hệ thống (Backup & Restore Strategy)

Tài liệu này định nghĩa quy trình chuẩn hóa cho việc sao lưu và phục hồi hệ thống dựa trên Docker (PostgreSQL, MinIO, NextJS, Laravel). Cả hai quy trình đã được tự động hóa hoàn toàn thông qua Bash Scripts, hoạt động trơn tru trên bất kỳ môi trường nào.

---

## 1. Cấu hình Môi trường (Prerequisites)

Thay vì quản lý nhiều file cấu hình rời rạc, chúng ta sử dụng một file `.env` thống nhất đóng vai trò là "Single Source of Truth" cho toàn bộ hệ thống.

1. **Khởi tạo thông tin cấu hình:** 
   - Từ thư mục `docker/`, copy `docker/.env.example` thành `docker/.env` và điền hoặc điều chỉnh các tham số cần thiết. Không cần thiết phải quản lý `.env.fresh` hay `.env.restore` nào khác.

2. **Cài đặt Rclone & Cấu hình Cloud:**
   - Cài đặt công cụ [Rclone](https://rclone.org/downloads/) trên server.
   - Chạy lệnh `rclone config` để xác thực với nền tảng Cloud (VD: Google Drive, OneDrive).
   - Đảm bảo tham số `RCLONE_REMOTES` trong file `.env` khai báo đúng tên Remote. Ví dụ: `RCLONE_REMOTES=ggdrive:second-memory-backups`.

---

## 2. Quy trình Sao lưu Dữ liệu (Automated Backup Lifecycle)

Hệ thống sử dụng kịch bản `docker/backup.sh` để khóa nguyên trạng (Full Snapshot) toàn bộ dữ liệu ở thời điểm chạy, sau đó đẩy an toàn lên Cloud.

**Quy trình chạy tự động 3 bước của script:**
1. **Trích xuất Database:** Dùng `pg_dump` nén cơ sở dữ liệu và file cấu hình `.env` lại.
2. **Kéo Media:** Sync thư mục chứa ảnh/File của hệ thống từ trong `ml-minio` ra ổ cứng cục bộ.
3. **Đóng gói và Dọn dẹp:** 
   - Đóng gói Database và Media thành 1 file ZIP duy nhất: `system_backup_YYYYMMDD_HHMMSS.tar.gz`.
   - Upload file lưu trữ duy nhất đó lên các nền tảng Cloud đã cấu hình trong `RCLONE_REMOTES`.
   - Dọn sạch các file backup cũ trên Cloud **khỏi hệ thống (lưu giữ 3 ngày theo chính sách Retention)**.
   - Xóa các file rác trung gian cục bộ.

**Cách chạy Backup thủ công (hoặc qua Cronjob):**
```bash
bash backup/backup.sh
```

---

## 3. Quy trình Khôi phục Dữ liệu (Automated Restore)

Dành cho các trường hợp chuyển server mới, dựng mới hoàn toàn (fresh build) hay khôi phục sau sự cố. Kịch bản `docker/restore.sh` sẽ tự động hóa từ A-Z.

**Cách thực hiện:**

1. **Chuẩn bị môi trường:** 
   Tải source code về, cấu hình file `docker/.env` theo đúng cổng (port) hoạt động mới, và xác thực `rclone` cho server mới (như Bước 1). Cấu hình Mật khẩu và thông số Database / MinIO phải trỏ đúng nơi cần restore.

2. **Khởi động Nền tảng Core:**
   Không cần chạy toàn bộ hệ thống, chỉ đưa Database và MinIO Server lên trước để hứng dữ liệu:
   ```bash
   cd docker
   docker compose up -d ml-postgres ml-minio
   ```
   *Chờ khoảng 5-10 giây để container sẵn sàng.*

3. **Chạy kịch bản Restore thần tốc:**
   ```bash
   bash backup/restore.sh
   ```
   - Script tự động đọc `RCLONE_REMOTES` từ file `.env`.
   - Lấy danh sách kiểm tra trên Google Drive / OneDrive và tải file `system_backup_...tar.gz` MỚI NHẤT về tĩnh.
   - Tự động xả nén, nạp ngược lại dữ liệu cho PostgreSQL và đẩy toàn bộ lượng Media thẳng vào lại Storage (MinIO).
   - Tự động dọn rác ngay khi kết thúc thành công!

4. **Khởi chạy lại Toàn Server:**
   Chạy tiếp các container phục vụ Ứng dụng:
   ```bash
   docker compose up -d ml-php ml-reverb ml-queue ml-nextjs ml-nextjs-docs ml-nginx
   ```
   *Quá trình khôi phục hoàn tất!*

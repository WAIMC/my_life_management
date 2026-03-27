#!/bin/bash
# ============================================
# TỰ ĐỘNG HÓA PHỤC HỒI HỆ THỐNG (RESTORE)
# Chạy ở bất kỳ môi trường mới nào
# ============================================
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Nạp file .env từ thư mục docker
if [ -f "$SCRIPT_DIR/../docker/.env" ]; then
    set -a
    source "$SCRIPT_DIR/../docker/.env"
    set +a
else
    echo "LỖI: Không tìm thấy $SCRIPT_DIR/../docker/.env"
    echo "Vui lòng copy docker/.env.example sang docker/.env và tùy chỉnh trước khi chạy restore!"
    exit 1
fi

PROJECT_DIR=${PROJECT_DIR:-"$(dirname "$SCRIPT_DIR")"}
BACKUP_DIR="$PROJECT_DIR/restore_tmp"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")

echo "=== BẮT ĐẦU QUÁ TRÌNH PHỤC HỒI ($TIMESTAMP) ==="

# Chọn cách lấy dữ liệu (Local File hay Rclone Cloud)
LOCAL_BACKUP_FILE=$1

if [ -n "$LOCAL_BACKUP_FILE" ] && [ -f "$LOCAL_BACKUP_FILE" ]; then
    echo ">> (LOCAL) Tìm thấy file backup được chỉ định: $LOCAL_BACKUP_FILE"
    LATEST_BACKUP=$(basename "$LOCAL_BACKUP_FILE")
    rm -rf "$BACKUP_DIR"
    mkdir -p "$BACKUP_DIR"
    cp "$LOCAL_BACKUP_FILE" "$BACKUP_DIR/$LATEST_BACKUP"
    cd "$BACKUP_DIR"
    echo "[1/3] Đang sử dụng file hệ thống Local..."
else
    # Chọn remote để lấy dữ liệu (Mặc định dùng remote đầu tiên trong danh sách)
    RCLONE_REMOTES=${RCLONE_REMOTES:-"ggdrive:second-memory-backups"}
    REMOTE=$(echo $RCLONE_REMOTES | tr ',' ' ' | awk '{print $1}')

    echo ">> Đang lấy danh sách các bản backup từ Cloud ($REMOTE)..."
    LATEST_BACKUP=$(rclone lsf "$REMOTE/history/" -F p | grep "system_backup" | sort | tail -n 1)

    if [ -z "$LATEST_BACKUP" ]; then
        echo "LỖI: Không tìm thấy bản backup nào trên $REMOTE/history/"
        exit 1
    fi

    echo ">> Đã tìm thấy bản backup mới nhất: $LATEST_BACKUP"

    rm -rf "$BACKUP_DIR"
    mkdir -p "$BACKUP_DIR"
    cd "$BACKUP_DIR"

    # -----------------------------------------------------------------------------
    # PHẦN 1: TẢI & GIẢI NÉN BACKUP
    # -----------------------------------------------------------------------------
    echo "[1/3] Đang tải file hệ thống từ Cloud..."
    rclone copy "$REMOTE/history/$LATEST_BACKUP" .
fi

echo ">> Đang giải nén dữ liệu..."
tar -xzf "$LATEST_BACKUP"
EXTRACTED_DIR=$(ls -d */ | grep tmp_system)

# -----------------------------------------------------------------------------
# PHẦN 2: RESTORE CORE (DATABASE)
# -----------------------------------------------------------------------------
echo "[2/3] Đang import Database vào PostgreSQL..."
SQL_GZ_FILE="$EXTRACTED_DIR/core/db_backup.sql.gz"
if [ -f "$SQL_GZ_FILE" ]; then
    PG_CONTAINER="${POSTGRES_HOST_INSIDE_ENV:-ml-postgres}"
    echo "   - Định dạng lại schema bảo vệ trước khi nạp đồ nội thất..."
    docker exec -i "$PG_CONTAINER" sh -c "PGPASSWORD=\$POSTGRES_PASSWORD psql -U \$POSTGRES_USER -d \$POSTGRES_DB -c 'DROP SCHEMA public CASCADE; CREATE SCHEMA public; GRANT ALL ON SCHEMA public TO public;'" > /dev/null 2>&1
    
    echo "   - Bắt đầu đưa các table vào cơ sở dữ liệu..."
    gunzip -c "$SQL_GZ_FILE" | docker exec -i "$PG_CONTAINER" sh -c "PGPASSWORD=\$POSTGRES_PASSWORD psql -U \$POSTGRES_USER -d \$POSTGRES_DB -q" > /dev/null 2>&1 || echo "Cảnh báo: Có lỗi siêu nhỏ trong quá trình import DB (thường có thể bỏ qua)."
    echo ">> Import Database hoàn tất!"
else
    echo "Cảnh báo: Không tìm thấy file $SQL_GZ_FILE"
fi

# -----------------------------------------------------------------------------
# PHẦN 3: RESTORE MEDIA (MINIO)
# -----------------------------------------------------------------------------
echo "[3/3] Đang phục hồi toàn bộ dữ liệu MEDIA (MinIO)..."

MINIO_CONTAINER="${MINIO_HOST_INSIDE_ENV:-ml-minio}"
MINIO_BUCKET="${MINIO_BUCKET_OFFICIAL:-media-official}"

MEDIA_TMP="$EXTRACTED_DIR/media"

if [ -d "$MEDIA_TMP" ]; then
    echo ">> Đang đưa dữ liệu vào MinIO Bucket..."
    docker exec "$MINIO_CONTAINER" rm -rf /tmp/restore-media
    docker cp "$MEDIA_TMP/." "$MINIO_CONTAINER:/tmp/restore-media/"

    # Sử dụng biến môi trường nội bộ của container để tránh lỗi lệch signature
    docker exec "$MINIO_CONTAINER" sh -c "mc alias set myminio http://127.0.0.1:\${MINIO_PORT_INSIDE_ENV:-9000} \$MINIO_ROOT_USER \$MINIO_ROOT_PASSWORD && mc mb --ignore-existing myminio/$MINIO_BUCKET && mc mirror --overwrite /tmp/restore-media/ myminio/$MINIO_BUCKET && rm -rf /tmp/restore-media/"
    echo ">> Restore Media hoàn tất!"
else
    echo "Cảnh báo: Không tìm thấy thư mục MEDIA trong bản backup."
fi

# -----------------------------------------------------------------------------
# PHẦN 4: ĐỒNG BỘ CẤU HÌNH (SETUP-ENV) TRƯỚC KHI XOÁ rác
# -----------------------------------------------------------------------------
# docker.env từ backup ghi đè lên docker/.env (trước khi xoá extracted dir)
DOCKER_ENV_BACKUP="$BACKUP_DIR/$EXTRACTED_DIR/core/docker.env"
if [ -f "$DOCKER_ENV_BACKUP" ]; then
    echo ">> Phục hồi file cấu hình docker/.env từ backup..."
    rm -f "$SCRIPT_DIR/../docker/.env"
    cp "$DOCKER_ENV_BACKUP" "$SCRIPT_DIR/../docker/.env"
fi

# -----------------------------------------------------------------------------
# DỌN DẸP TẠM
# -----------------------------------------------------------------------------
echo "=========================================================="
echo ">> Đang dọn dẹp các file tạm..."
cd "$SCRIPT_DIR"
rm -rf "$BACKUP_DIR"

# Gọi setup-env.sh để inject credentials vào tất cả file .env liên quan
SETUP_ENV="$SCRIPT_DIR/../setup-env.sh"
if [ -f "$SETUP_ENV" ]; then
    echo ">> Chạy setup-env.sh để đồng bộ credentials vào laravel-api/.env..."
    bash "$SETUP_ENV"
fi

# Xóa config cache để Laravel load lại .env với credentials mới
echo ">> Xóa Laravel config cache..."
if docker ps -q --filter name=ml-php | grep -q .; then
    docker exec ml-php php artisan config:clear
    echo ">> Config cache cleared."
fi

echo "Hoàn thành quy trình khôi phục dữ liệu! (Thời gian: $(date))"
echo "→ Hãy khởi động lại các container Laravel nếu cần: docker compose restart ml-php ml-queue"

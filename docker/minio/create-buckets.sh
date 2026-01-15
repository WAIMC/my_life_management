#!/bin/sh
set -e

# Wait for MinIO to be ready
echo "Waiting for MinIO..."
# Using a loop to ensure MinIO is responsive before attempting alias set
# We try up to 30 times (30 seconds)
i=0
until mc alias set myminio http://ml-minio:${MINIO_PORT_INSIDE_ENV} ${MINIO_ROOT_USER} ${MINIO_ROOT_PASSWORD}; do
  echo "MinIO not ready, retrying in 1s..."
  sleep 1
  i=$((i+1))
  if [ $i -ge 30 ]; then
    echo "Timeout waiting for MinIO"
    exit 1
  fi
done

echo "MinIO is ready. Configuring buckets..."

# Create legacy/env-defined buckets (ignore if they exist)
if [ ! -z "${MINIO_BUCKET}" ]; then
  mc mb myminio/${MINIO_BUCKET} --ignore-existing
  mc anonymous set download myminio/${MINIO_BUCKET}
fi

if [ ! -z "${MINIO_PREVIEW_BUCKET}" ]; then
  mc mb myminio/${MINIO_PREVIEW_BUCKET} --ignore-existing
  mc anonymous set download myminio/${MINIO_PREVIEW_BUCKET}
fi

# Create requested specific buckets
echo "Creating '${MINIO_BUCKET_OFFICIAL}' and '${MINIO_BUCKET_TEMP}' buckets..."
mc mb myminio/${MINIO_BUCKET_OFFICIAL} --ignore-existing
mc mb myminio/${MINIO_BUCKET_TEMP} --ignore-existing

# Set public download policies
mc anonymous set download myminio/${MINIO_BUCKET_OFFICIAL}
# Note: temp-uploads might not need public download, but setting it for consistency with user's likely need for temporary media serving.
mc anonymous set download myminio/${MINIO_BUCKET_TEMP}

# Configure Lifecycle for temp-uploads
echo "Configuring lifecycle for ${MINIO_BUCKET_TEMP} (1 day expiry)..."

# Remove existing rules to strictly enforce the current policy (idempotency)
# The previous --id command failed, and remove requires --all --force to clean up
mc ilm rule remove myminio/${MINIO_BUCKET_TEMP} --all --force || true

# Add the rule (without --id which caused errors)
mc ilm rule add myminio/${MINIO_BUCKET_TEMP} --expire-days 1

echo "MinIO setup script completed successfully."
exit 0

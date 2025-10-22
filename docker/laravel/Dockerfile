FROM php:8.2-fpm-alpine

# Cài đặt các extension cần thiết và build tools
RUN apk add --no-cache \
    postgresql-dev \
    autoconf \
    gcc \
    g++ \
    make \
    libc-dev \
    redis \
    postgresql-client \
    nodejs \
    npm \
    python3 \
    py3-pip \
    sudo \
    jq \
    curl \
    git \
    file \
    libxml2-dev \
    openssl-dev \
    && docker-php-ext-install pdo_pgsql \
    && pecl install redis-5.3.7 \
    && docker-php-ext-enable redis pdo_pgsql

RUN python3 -m venv /opt/venv
ENV PATH="/opt/venv/bin:$PATH"
RUN pip install simple-ddl-parser

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/laravel-api

# Chú ý: Không cần tạo user và thiết lập quyền ở đây
# Do sử dụng docker-compose.simple.yml với cấu hình đơn giản hóa
# Quyền được thiết lập bằng script start.sh

CMD ["php-fpm"]
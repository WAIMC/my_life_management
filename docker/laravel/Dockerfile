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
    && docker-php-ext-install pdo_pgsql \
    && pecl install redis-5.3.7 \
    && docker-php-ext-enable redis pdo_pgsql

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/laravel-api

# Cài đặt dependencies từ mã nguồn được ánh xạ qua volume
# Lưu ý: composer install sẽ được chạy sau khi volume được gắn
CMD composer install --no-dev --optimize-autoloader \
    && chown -R www-data:www-data /var/www/laravel-api/storage /var/www/laravel-api/bootstrap/cache \
    && php-fpm
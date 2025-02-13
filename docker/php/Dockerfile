FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    curl \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libjpeg-dev \
    libfreetype6-dev \
    postgresql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        gd \
        pdo_pgsql \
        pgsql \
        mbstring \
        exif \
        pcntl \
        bcmath \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy custom PHP configuration
COPY php.ini /usr/local/etc/php/

# Expose the PHP-FPM port
EXPOSE 9000

CMD ["php-fpm"]

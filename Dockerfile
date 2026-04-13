# ============================================
# CineRate - Railway Deployment Dockerfile
# Simplified: PHP built-in server (no nginx/supervisor)
# ============================================
FROM php:8.3-cli-alpine

# Install system dependencies
RUN apk add --no-cache \
    curl \
    zip \
    unzip \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    sqlite-dev \
    icu-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    pdo_sqlite \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    xml \
    intl \
    opcache

# Configure OPcache
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.validate_timestamps=0'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

# Configure PHP limits
RUN { \
    echo 'upload_max_filesize=64M'; \
    echo 'post_max_size=64M'; \
    echo 'memory_limit=256M'; \
    } > /usr/local/etc/php/conf.d/limits.ini

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy application code
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --prefer-dist --no-interaction

# Install Node dependencies, build assets, cleanup
RUN npm install --no-audit --no-fund && npm run build && rm -rf node_modules

# Create required directories
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chmod -R 777 storage \
    && chmod -R 777 bootstrap/cache

# Copy and fix entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN sed -i 's/\r$//' /entrypoint.sh && chmod +x /entrypoint.sh

EXPOSE 8080

CMD ["/entrypoint.sh"]

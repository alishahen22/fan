# ---- Build Stage ----
FROM composer:2 as composer

WORKDIR /var/www

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-progress --prefer-dist

# ---- App Stage ----
FROM php:8.2-fpm

# Fix DNS issue inside container (if any)
RUN echo "nameserver 8.8.8.8" > /etc/resolv.conf

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Copy PHP config
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy app files
COPY . .

# Copy vendor from build
COPY --from=composer /var/www/vendor ./vendor

# Permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# Nginx will be handled in another container
EXPOSE 9000

CMD ["php-fpm"]

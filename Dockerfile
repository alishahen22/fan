# ✅ Base image
FROM php:8.2-fpm

# ✅ Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    nginx \
    supervisor \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# ✅ Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ✅ Set working directory
WORKDIR /var/www

# ✅ Copy project files
COPY . .

# ✅ Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev

# ✅ Set permissions
RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

# ✅ Copy Nginx config
COPY ./docker/nginx.conf /etc/nginx/sites-available/default

# ✅ Copy Supervisor config
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# ✅ Expose port
EXPOSE 80

# ✅ Start Supervisor (which runs both PHP-FPM & Nginx)
CMD ["/usr/bin/supervisord"]

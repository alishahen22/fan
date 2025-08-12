# ---------- Base PHP-FPM ----------
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies + nginx + supervisor
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . .

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Copy Supervisor config
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Laravel permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose ports
EXPOSE 9001

# Start supervisord (which will run PHP-FPM & Nginx)
CMD ["/usr/bin/supervisord", "-n"]

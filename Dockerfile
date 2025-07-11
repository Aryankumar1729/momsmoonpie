# Start with PHP + Apache image
FROM php:8.2-apache

# Install system dependencies & PHP extensions
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy Composer from composer image (do this BEFORE copying app)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy app files
COPY . .

# Install PHP dependencies using Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache

# Expose Apache
# EXPOSE 80
# Expose Laravel port
EXPOSE 8080

# Start Laravel using Artisan (not Apache)
CMD ["apache2-foreground"]
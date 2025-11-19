# Dockerfile

# Sử dụng PHP 8.2 + Apache
FROM php:8.2-apache

# Cài các extension cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Bật Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy source code vào container
COPY . /var/www/html

# Copy Composer từ image composer chính thức
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Cài các package PHP
RUN composer install --optimize-autoloader --no-dev

# Set quyền cho storage và cache (trên Linux container)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy Apache config để Laravel hoạt động đúng /public
COPY ./docker/000-default.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80 cho container
EXPOSE 80

FROM php:8.1-fpm

# Install required PHP extensions and utilities
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    zip \
    curl \
    && docker-php-ext-install pdo_mysql mysqli

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

COPY composer* /var/www/html

# Install PHPMailer dependencies
RUN composer install

COPY . .

# Default command to start PHP-FPM
CMD ["sh", "-c", "chmod -R 775 /var/www/html/rsrcs/public && php-fpm"]

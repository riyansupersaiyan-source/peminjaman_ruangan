FROM php:8.1-cli

# Install dependencies + ekstensi penting
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Install MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader

# Clear config
RUN php artisan config:clear

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000
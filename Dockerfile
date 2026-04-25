FROM php:8.1-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev curl \
    && docker-php-ext-install zip

# Install MongoDB extension
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Node.js (WAJIB untuk Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Install & build frontend (INI YANG FIX ERROR KAMU)
RUN npm install
RUN npm run build

# Permission fix
RUN chmod -R 775 storage bootstrap/cache

# Clear config
RUN php artisan config:clear

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000
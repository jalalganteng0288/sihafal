FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libxml2-dev libzip-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip gd bcmath \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install and build frontend
RUN npm install && npm run build

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan migrate --force && php artisan db:seed --class=SurahSeeder --force && php artisan serve --host=0.0.0.0 --port=8080

FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:clear \
    && php artisan route:clear \
    && php artisan view:clear \
    && php artisan l5-swagger:generate

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
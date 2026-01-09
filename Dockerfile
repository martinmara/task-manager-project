# 1) Frontend build (Vite)
FROM node:20-bookworm-slim AS nodebuild
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# 2) PHP dependencies
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
COPY . .

# 3) Runtime
FROM php:8.2-fpm-bookworm

RUN apt-get update && apt-get install -y \
    nginx unzip libzip-dev libpng-dev libonig-dev libxml2-dev \
  && docker-php-ext-install pdo pdo_mysql zip \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html

COPY --from=vendor /app /var/www/html
COPY --from=nodebuild /app/public/build /var/www/html/public/build

COPY ./docker/nginx.conf /etc/nginx/nginx.conf

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8080
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]

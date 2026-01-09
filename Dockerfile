# syntax=docker/dockerfile:1

############################################
# 1) PHP deps (Composer) on PHP 8.2
############################################
FROM php:8.2-cli-alpine AS phpdeps
WORKDIR /app

# system deps for composer + typical Laravel packages
RUN apk add --no-cache git unzip curl icu-dev oniguruma-dev libzip-dev

# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# copy only composer files first for better caching
COPY task-manager/composer.json task-manager/composer.lock ./

# install vendor (no scripts for speed/safety in build)
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

# now copy the app source
COPY task-manager/ ./

# run again without --no-scripts in case your app needs package discovery, etc.
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress


############################################
# 2) Frontend build (Node 20 for Vite 6+)
############################################
FROM node:20-alpine AS frontend
WORKDIR /app

# bring the FULL app (including vendor)
COPY --from=phpdeps /app /app

# build assets (Laravel/Vite default: /resources)
RUN set -eux; \
  echo "Node:"; node -v; npm -v; \
  if [ -f ./resources/package.json ]; then FRONTEND_DIR="./resources"; \
  elif [ -f ./frontend/package.json ]; then FRONTEND_DIR="./frontend"; \
  elif [ -f ./client/package.json ]; then FRONTEND_DIR="./client"; \
  elif [ -f ./package.json ]; then FRONTEND_DIR="."; \
  else \
    echo "ERROR: package.json not found inside /app"; \
    echo "Contents:"; ls -la; \
    exit 1; \
  fi; \
  echo "Using frontend dir: $FRONTEND_DIR"; \
  cd "$FRONTEND_DIR"; \
  npm ci --no-audit --no-fund || npm install --no-audit --no-fund; \
  npm run build; \
  if [ -d dist ]; then OUT="dist"; \
  elif [ -d build ]; then OUT="build"; \
  elif [ -d public/build ]; then OUT="public/build"; \
  else \
    echo "ERROR: Build finished but no dist/build output folder found."; \
    echo "Listing:"; ls -la; \
    exit 1; \
  fi; \
  mkdir -p /out; \
  cp -R "$OUT"/. /out/


############################################
# 3) Runtime image: PHP-FPM + Nginx + Supervisor
############################################
FROM php:8.2-fpm-alpine AS runtime
WORKDIR /var/www/html

# runtime packages
RUN apk add --no-cache \
    nginx supervisor bash curl icu-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    oniguruma-dev zip unzip \
    postgresql-dev

# php extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) pdo pdo_mysql pdo_pgsql mbstring gd intl opcache

# copy app (including vendor) from phpdeps stage
COPY --from=phpdeps /app /var/www/html

# bring built frontend bundle
COPY --from=frontend /out /out

# copy built frontend output into Laravel public build location
RUN set -eux; \
  mkdir -p /var/www/html/public/build; \
  rm -rf /var/www/html/public/build/*; \
  cp -R /out/. /var/www/html/public/build/

# Ensure permissions (important on Fly)
RUN chown -R www-data:www-data /var/www/html \
  && chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache || true

############################################
# Nginx + Supervisor config
############################################
RUN mkdir -p /run/nginx /var/log/supervisor

# Nginx config
RUN printf '%s\n' \
'server {' \
'  listen 8080;' \
'  server_name _;' \
'  root /var/www/html/public;' \
'  index index.php index.html;' \
'' \
'  location / {' \
'    try_files $uri $uri/ /index.php?$query_string;' \
'  }' \
'' \
'  location ~ \.php$ {' \
'    try_files $uri =404;' \
'    fastcgi_pass 127.0.0.1:9000;' \
'    fastcgi_index index.php;' \
'    include fastcgi_params;' \
'    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;' \
'    fastcgi_param PATH_INFO $fastcgi_path_info;' \
'  }' \
'' \
'  location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|map)$ {' \
'    expires 30d;' \
'    access_log off;' \
'    add_header Cache-Control "public";' \
'  }' \
'}' \
> /etc/nginx/http.d/default.conf

# Supervisor config: run php-fpm + nginx
RUN printf '%s\n' \
'[supervisord]' \
'nodaemon=true' \
'' \
'[program:php-fpm]' \
'command=/usr/local/sbin/php-fpm -F' \
'autostart=true' \
'autorestart=true' \
'stdout_logfile=/dev/stdout' \
'stdout_logfile_maxbytes=0' \
'stderr_logfile=/dev/stderr' \
'stderr_logfile_maxbytes=0' \
'' \
'[program:nginx]' \
'command=/usr/sbin/nginx -g "daemon off;"' \
'autostart=true' \
'autorestart=true' \
'stdout_logfile=/dev/stdout' \
'stdout_logfile_maxbytes=0' \
'stderr_logfile=/dev/stderr' \
'stderr_logfile_maxbytes=0' \
> /etc/supervisord.conf

EXPOSE 8080
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

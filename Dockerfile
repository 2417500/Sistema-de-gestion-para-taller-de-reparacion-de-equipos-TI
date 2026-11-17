# syntax=docker/dockerfile:1

# ETAPA 1: Compilación de assets Frontend (Vite / Tailwind)
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json package-lock.json* ./
RUN npm ci
COPY resources ./resources
COPY public ./public
COPY vite.config.js postcss.config.js* tailwind.config.js* ./
RUN npm run build

# ETAPA 2: Aplicación Laravel PHP 8.4 FPM sin usuario root
FROM php:8.4-fpm-alpine

# Instalar dependencias del sistema y extensiones PHP necesarias para Laravel, MySQL, GD y PDF
RUN apk add --no-cache \
    bash \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    sqlite-dev \
    mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# Obtener Composer oficial
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Crear usuario y grupo sin privilegios de root (UID 1000 / GID 1000)
ARG UID=1000
ARG GID=1000
RUN addgroup -g ${GID} -S soportec \
    && adduser -u ${UID} -S soportec -G soportec

# Configurar PHP-FPM para escuchar en puerto 9000 y correr sin root
RUN sed -i 's/listen = 127.0.0.1:9000/listen = 9000/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/user = www-data/user = soportec/g' /usr/local/etc/php-fpm.d/www.conf \
    && sed -i 's/group = www-data/group = soportec/g' /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html

# Copiar código fuente con permisos del usuario no-root
COPY --chown=soportec:soportec . .

# Copiar los assets compilados desde la etapa frontend
COPY --from=frontend --chown=soportec:soportec /app/public/build ./public/build

# Instalar dependencias de Composer como usuario no-root
USER soportec
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Asegurar directorios de almacenamiento y caché escribibles por el usuario soportec
RUN mkdir -p storage/framework/sessions \
             storage/framework/views \
             storage/framework/cache \
             storage/app/public \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
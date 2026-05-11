FROM php:8.4-fpm

# ── Composer memory limit (prevents OOM kills on large installs) ──────────────
ENV COMPOSER_MEMORY_LIMIT=-1

# ── System dependencies ──────────────────────────────────────────────────────
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    zip \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ── Composer ─────────────────────────────────────────────────────────────────
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ── Node.js 20 (for Vite / npm run build) ────────────────────────────────────
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ── Working directory ─────────────────────────────────────────────────────────
WORKDIR /var/www

# ── Install PHP dependencies (layer cache: copy lock files first) ─────────────
COPY composer.json composer.lock ./
RUN composer install \
    --no-scripts \
    --no-autoloader \
    --no-interaction \
    --prefer-dist

# ── Copy full project ─────────────────────────────────────────────────────────
COPY . .

# ── Provide a .env so artisan package:discover doesn't fail during build ──────
RUN cp .env.example .env

# ── Finish Composer (run scripts + optimised autoloader) ─────────────────────
RUN composer dump-autoload --optimize

# ── Install and build frontend assets ────────────────────────────────────────
RUN npm install && npm run build

# ── File permissions (Laravel needs write access to storage & cache) ──────────
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]


FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    curl-dev \
    supervisor \
    autoconf \
    gcc \
    g++ \
    make \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip intl mbstring bcmath opcache pcntl

RUN pecl install redis && docker-php-ext-enable redis \
    && apk del autoconf gcc g++ make

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

COPY . .
RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/supervisord.conf /etc/supervisord.conf

HEALTHCHECK --interval=60s --timeout=10s --retries=3 \
    CMD php artisan schedule:test --name="health" 2>/dev/null || curl -f http://localhost:8080/health/ping || exit 1

STOPSIGNAL SIGTERM

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]

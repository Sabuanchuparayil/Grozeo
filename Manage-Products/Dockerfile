FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libzip-dev libicu-dev \
    libonig-dev libcurl4-openssl-dev libxml2-dev unzip curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip intl mbstring bcmath opcache curl \
    && pecl install redis && docker-php-ext-enable redis \
    && a2enmod rewrite headers \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-available/allowoverride.conf \
    && a2enconf allowoverride

WORKDIR /var/www/html
COPY . .

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

ENV APACHE_PORT=80
RUN sed -i "s/Listen 80/Listen \${APACHE_PORT}/" /etc/apache2/ports.conf \
    && sed -i "s/:80/:${APACHE_PORT}/" /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]

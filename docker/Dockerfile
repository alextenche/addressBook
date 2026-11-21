# syntax=docker/dockerfile:1
FROM php:8.4-apache AS base

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN docker-php-ext-install pdo_mysql \
 && a2enmod rewrite \
 && sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf \
 && printf 'ServerTokens Prod\nServerSignature Off\n' > /etc/apache2/conf-enabled/zz-hardening.conf
WORKDIR /var/www/html

# Development image: source and vendor/ are bind-mounted by compose.yaml.
FROM base AS dev
RUN apt-get update && apt-get install -y --no-install-recommends unzip && rm -rf /var/lib/apt/lists/* \
 && mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
CMD ["sh", "-c", "[ -d vendor ] || composer install --no-interaction; php bin/migrate --seed && exec apache2-foreground"]

# Production dependencies only, resolved in the official Composer image.
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist --optimize-autoloader

# Production image: no Composer, no dev tools, errors hidden.
FROM base AS prod
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY --from=vendor /app/vendor ./vendor
COPY . .
ENV APP_ENV=production APP_DEBUG=false
CMD ["sh", "-c", "php bin/migrate && exec apache2-foreground"]

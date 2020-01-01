FROM php:7.4-cli AS builder

RUN apt-get update && apt-get install -y unzip git

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet \
    && composer global require hirak/prestissimo --no-plugins --no-scripts \
    && rm -rf /root/.composer/cache

ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR /app

COPY ./composer.json ./composer.lock ./

RUN composer install --no-dev --prefer-dist --no-progress --no-suggest --noscripts --optimize-autoloader \
    && rm -rf /root/.composer/cache

### FPM ###


FROM php:7.4-fpm

RUN docker-php-ext-install opcache

RUN mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini

COPY ./docker/common/php/conf.d /usr/local/etc/php/conf.d
COPY ./docker/production/php/conf.d /usr/local/etc/php/conf.d

WORKDIR /app

COPY --from=builder /app ./
COPY ./ ./
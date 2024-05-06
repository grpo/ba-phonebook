FROM php:8.2-fpm
WORKDIR /var/www
EXPOSE 8000
MAINTAINER <GREPOV@GMAIL.COM>
RUN apt-get update

RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql

RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

RUN apt-get install -y curl gnupg zip unzip git && \
    apt-get update && \
    curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/bin/ --filename=composer

COPY ./docker/php/conf.d/xdebug.ini "${PHP_INI_DIR}/conf.d"
COPY ./docker/php/conf.d/error_reporting.ini "${PHP_INI_DIR}/conf.d"

CMD composer install && \
    composer dump-autoload && \
    php-fpm
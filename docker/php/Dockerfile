FROM php:${PHP_VERSION:-8.1-fpm-bullseye}

ARG WWWUSER
ARG WWWGROUP

RUN docker-php-ext-install pdo_mysql

WORKDIR /var/www/html

RUN groupadd --force -g $WWWGROUP phpuser
RUN useradd -ms /usr/sbin/nologin --no-user-group -g $WWWGROUP -u $WWWUSER phpuser
RUN mkdir -p /var/www/html/app/$APP_DIRECTORY/storage
RUN chown -R $WWWUSER:$WWWGROUP /var/www/html/app/$APP_DIRECTORY/storage /usr/local/etc/php*

USER phpuser

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

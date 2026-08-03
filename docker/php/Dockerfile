FROM php:${PHP_VERSION:-8.2-fpm-bookworm}

ARG WWWUSER
ARG WWWGROUP
ARG NODE_VERSION=18
ARG POSTGRES_VERSION=15

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN docker-php-ext-install pdo_mysql

RUN apt-get update \
    && apt-get install -y --no-install-recommends git default-mysql-client gnupg gosu curl ca-certificates zip unzip sqlite3 ssh \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -sLS https://deb.nodesource.com/setup_$NODE_VERSION.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /tmp

RUN apt-get update \
    && apt-get install -y xfonts-75dpi \
    && curl -LO http://security.debian.org/debian-security/pool/updates/main/o/openssl/libssl1.1_1.1.1w-0+deb11u8_amd64.deb \
    && apt install -y ./libssl1.1_1.1.1w-0+deb11u8_amd64.deb \
    && rm ./libssl1.1_1.1.1w-0+deb11u8_amd64.deb \
    && curl -LO https://github.com/wkhtmltopdf/packaging/releases/download/0.12.6.1-2/wkhtmltox_0.12.6.1-2.bullseye_amd64.deb \
    && apt install -y ./wkhtmltox_0.12.6.1-2.bullseye_amd64.deb \
    && rm ./wkhtmltox_0.12.6.1-2.bullseye_amd64.deb \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN apt-get update \
    && apt-get install -y libzip-dev libpng-dev libonig-dev \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*


RUN docker-php-ext-install zip gd ctype mbstring
RUN docker-php-ext-enable zip gd ctype mbstring

WORKDIR /var/www/html

RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u $WWWUSER sail
RUN mkdir -p /var/www/html/storage
RUN chown -R $WWWUSER:$WWWGROUP /var/www/html/storage /usr/local/etc/php*

USER sail

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

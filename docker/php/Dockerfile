FROM php:8.2-alpine

# Install Xdebug
RUN apk add --no-cache --virtual .build-deps linux-headers $PHPIZE_DEPS \
    && pecl install xdebug \
    && apk del .build-deps \
    && rm -r /tmp/pear/*

COPY xdebug.ini ${PHP_INI_DIR}/conf.d/xdebug.ini
WORKDIR /app

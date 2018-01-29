FROM php:7.1-fpm

RUN apt-get update \
   && apt-get install -y --no-install-recommends \
       libcurl4-openssl-dev \
       libmagickwand-dev \
       imagemagick \
   && docker-php-ext-install -j$(nproc) json mbstring \
   && docker-php-ext-install -j$(nproc) curl \
   && docker-php-ext-install -j$(nproc) zip \
   && pecl install imagick \
   && docker-php-ext-enable imagick \
   && rm -rf /var/lib/apt/lists/*

# Composer
RUN curl -sS https://getcomposer.org/installer | php \
   && mv composer.phar /usr/local/bin/composer \
   && chmod +x /usr/local/bin/composer \
   && composer global require hirak/prestissimo

#xDebug
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini
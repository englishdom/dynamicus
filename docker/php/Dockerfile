FROM php:7.1-fpm

RUN apt-get update

# Some basic extensions
RUN docker-php-ext-install -j$(nproc) json mbstring

# Curl
RUN apt-get install -y libcurl4-openssl-dev
RUN docker-php-ext-install -j$(nproc) curl

# IMagick
RUN apt-get update && apt-get -y install libmagickwand-dev imagemagick --no-install-recommends \
   && rm -rf /var/lib/apt/lists/* \
   && pecl install imagick \
   && docker-php-ext-enable imagick

# Zip
RUN docker-php-ext-install -j$(nproc) zip

# Composer
RUN curl -sS https://getcomposer.org/installer | php \
   && mv composer.phar /usr/local/bin/composer \
   && chmod +x /usr/local/bin/composer

#xDebug
RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini
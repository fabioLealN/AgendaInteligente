ARG PHP_VERSION="8.1"

FROM php:${PHP_VERSION}-fpm

RUN apt-get update && apt-get -y --no-install-recommends install \
git \
curl \
libpng-dev \
libonig-dev \
libxml2-dev \
zip \
unzip \
supervisor \
&& apt-get autoremove --purge -y && apt-get clean \
&& rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install Postgre PDO
RUN apt-get update

RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN docker-php-ext-install -j$(nproc) \
opcache \
intl \
fileinfo \
pdo_pgsql \
pgsql \
mbstring \
exif \
pcntl \
bcmath \
gd

RUN pecl install apcu-5.1.20 && docker-php-ext-enable apcu

COPY docker/app/php.ini   $PHP_INI_DIR/conf.d/

RUN curl -sS https://getcomposer.org/installer | \
php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/app

COPY . /var/www/app

RUN composer update

RUN chmod -R 000 storage/*

ADD docker/app/supervisor.conf /etc/supervisor/conf.d/worker.conf

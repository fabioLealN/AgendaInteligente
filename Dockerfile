FROM wyveo/nginx-php-fpm:latest

WORKDIR /usr/share/nginx/

COPY ./.docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

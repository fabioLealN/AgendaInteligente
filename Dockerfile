FROM wyveo/nginx-php-fpm:latest

WORKDIR /usr/share/nginx/

COPY ./.docker/nginx/default.conf /etc/nginx/conf.d/default.conf
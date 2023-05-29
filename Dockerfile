FROM phpdockerio/php:8.2-fpm

RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    curl \
    php8.2-mysql \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html

COPY ./phpdocker/nginx/nginx.conf /etc/nginx/conf.d/default.conf


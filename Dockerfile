FROM php:7.4-apache

RUN apt-get update -y && apt-get install -y libmcrypt-dev && apt-get install libssl-dev -y && apt-get install git -y
RUN docker-php-ext-install mysqli pdo pdo_mysql
RUN apt-get update && apt-get install -y \
    zlib1g-dev \
    libzip-dev
RUN docker-php-ext-install zip


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . /app

RUN composer install

EXPOSE 8001
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8001"]

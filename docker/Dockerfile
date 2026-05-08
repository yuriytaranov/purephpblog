# docker/Dockerfile
FROM php:8.5-fpm-trixie

# Get frequently used tools
RUN apt-get update && apt-get install -y \
    build-essential \
    libicu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    locales \
    zip \
    unzip \
    jpegoptim optipng pngquant gifsicle \
    git \
    curl \
    libpq-dev \
    wget

RUN docker-php-ext-configure zip

RUN docker-php-ext-install \
        bcmath \
        mbstring \
        pcntl \
        intl \
        zip \
        mysqli \
        pdo_mysql

RUN docker-php-ext-enable mysqli pdo_mysql
# apcu for caching, xdebug for debugging and also phpunit coverage
RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY ./docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /var/www
# Copy and run composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

CMD ["php-fpm"]
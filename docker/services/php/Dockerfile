FROM php:7.4-fpm

# Arguments defined in docker-compose.yml
ARG uname
ARG gid
ARG uid

# Install system dependencies
RUN apt-get update \
    && apt-get install -y \
        git \
        curl \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        cron

RUN pecl install xdebug-2.9.8
RUN docker-php-ext-enable xdebug

ADD ./php.ini /usr/local/etc/php/php.ini

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN groupadd --gid $gid $uname
RUN useradd -G www-data,root -s /bin/bash --uid $uid --gid $gid $uname

RUN mkdir -p /home/$uname/.composer && \
    chown -R $uname:$uname /home/$uname

# Set working directory
WORKDIR /var/www

USER $uname

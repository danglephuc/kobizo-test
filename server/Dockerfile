FROM php:8.0-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
	libzip-dev \
    zip \
    unzip \
	libssl-dev \
	dos2unix \
    sudo

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install mbstring pcntl bcmath zip

# Install mongodb extensions
RUN pecl install mongodb \
    && echo "extension=mongodb.so" > $PHP_INI_DIR/conf.d/mongodb.ini

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY .docker/php.ini-production /usr/local/etc/php/php.ini
COPY .docker/entrypoint.sh /entrypoint.sh
RUN dos2unix /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Set working directory
WORKDIR /var/www

ENTRYPOINT ["/entrypoint.sh"]

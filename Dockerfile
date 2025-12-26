FROM composer:2.7 AS composer

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize

FROM php:8.2-fpm-alpine

# Arguments
ARG user=www
ARG uid=1000

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    oniguruma-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Redis extension
RUN apk add --no-cache autoconf g++ make \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del autoconf g++ make

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Create system user (Alpine uses adduser differently)
RUN addgroup -g $uid $user \
    && adduser -u $uid -G $user -s /bin/sh -D $user \
    && adduser $user www-data

# Set working directory
WORKDIR /var/www

# Copy application files from composer stage
COPY --from=composer /app /var/www
COPY --chown=$user:$user . /var/www

# Set permissions
RUN chown -R $user:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Switch to non-root user
USER $user

# Expose port 9000
EXPOSE 9000

CMD ["php-fpm"]

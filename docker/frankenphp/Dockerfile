FROM dunglas/frankenphp:latest

# Install PHP extensions in a single layer to reduce build time
RUN install-php-extensions \
    pdo_mysql \
    gd \
    zip \
    intl \
    opcache \
    redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . /var/www

# Copy Caddyfile
COPY Caddyfile /etc/caddy/Caddyfile

# Set PHP configuration for production
RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

# Add custom PHP configuration
RUN echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "opcache.enable=1" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "opcache.memory_consumption=128" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "opcache.interned_strings_buffer=8" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "opcache.max_accelerated_files=4000" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "opcache.revalidate_freq=2" >> /usr/local/etc/php/conf.d/custom.ini && \
    echo "opcache.fast_shutdown=1" >> /usr/local/etc/php/conf.d/custom.ini

# Set permissions
RUN chown -R www-data:www-data /var/www && \
    chmod -R 755 /var/www && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Create log directory
RUN mkdir -p /var/log/caddy && chown -R www-data:www-data /var/log/caddy

# Expose port
EXPOSE 80

# Use the default FrankenPHP entrypoint
CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]
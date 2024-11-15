FROM php:8.0-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd mysqli

# Enable Apache modules
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Configure Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Expose port 80
EXPOSE 80

# Configurar sesiones de PHP
RUN mkdir -p /var/lib/php/sessions \
    && chown -R www-data:www-data /var/lib/php/sessions \
    && chmod 1733 /var/lib/php/sessions

# Copiar configuración personalizada de PHP
COPY php.ini /usr/local/etc/php/conf.d/custom.ini

# Start Apache
CMD ["apache2-foreground"]

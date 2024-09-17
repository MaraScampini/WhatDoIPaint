# Use the official PHP image with Apache, specifying PHP 8.2
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable Apache mod_rewrite for Symfony
RUN a2enmod rewrite

# Allow .htaccess to override configurations in the public directory
RUN echo '<Directory /var/www/html/public>\n\
    AllowOverride All\n\
</Directory>\n' > /etc/apache2/conf-available/symfony.conf

RUN a2enconf symfony

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy project files to the working directory
COPY . .

# Create the var directory if it doesn't exist
RUN mkdir -p /var/www/html/var

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Set appropriate permissions for the Symfony cache and log directories
RUN chown -R www-data:www-data /var/www/html/var \
    && chmod -R 775 /var/www/html/var

# Set Apache DocumentRoot to /var/www/html/public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]

# Use the official PHP image with Apache
FROM php:8.2-apache

# Install necessary PHP extensions and other dependencies
RUN apt-get update && apt-get install -y net-tools \
    && docker-php-ext-install mysqli pdo pdo_mysql exif \
    && a2enmod rewrite \
    && mkdir -p /var/www/html/uploads \
    && chmod -R 755 /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads

# Copy the application files into the container
COPY . /var/www/html

# Copy the flag file to a secure location
COPY flag.txt /var/flag.txt

# Copy the custom Apache configuration file
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Set PHP configurations for production
RUN echo "display_errors = Off" >> /usr/local/etc/php/php.ini \
    && echo "error_reporting = E_ALL & ~E_WARNING & ~E_NOTICE" >> /usr/local/etc/php/php.ini

# Expose port 80 to the host
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]

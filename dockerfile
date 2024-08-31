# Use the official PHP image from the Docker Hub
FROM php:8.2-apache

# Install any required PHP extensions
RUN docker-php-ext-install mysqli

# Copy application code to the container
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html/

# Expose port 80 to the outside world
EXPOSE 80

# Command to run Apache in the foreground
CMD ["apache2-foreground"]

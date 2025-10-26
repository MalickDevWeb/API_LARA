#!/bin/sh
# Set Apache port
sed -i "s/Listen 80/Listen ${PORT:-80}/g" /etc/apache2/ports.conf

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Apache
apache2-foreground
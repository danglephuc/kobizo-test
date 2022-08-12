#!/bin/sh

set -e
composer install
rm -rf /var/www/var/cache

php-fpm

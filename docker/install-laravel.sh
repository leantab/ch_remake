#!/bin/bash

cd /var/www/html
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate:fresh --seed
chmod 777 storage -R
chmod 777 bootstrap -R

#!/bin/bash

composer dump-autoload
php artisan migrate:refresh --seed
./vendor/bin/phpunit
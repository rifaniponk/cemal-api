#!/bin/bash

php artisan migrate:refresh --seed
./vendor/bin/phpunit
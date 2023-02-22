#!/bin/bash

./vendor/bin/phpcbf
clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan route:cache
./vendor/bin/sail test
./vendor/bin/phpcs -q
./vendor/bin/phpstan --no-progress

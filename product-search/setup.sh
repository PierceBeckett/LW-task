#!/bin/bash                                                                                                                                                                                                        

docker run --rm     -u "$(id -u):$(id -g)"     -v $(pwd):/opt     -w /opt     laravelsail/php82-composer:latest     composer install --ignore-platform-reqs

cp .env.example .env

./vendor/bin/sail build
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan key:generate

#!/bin/bash

cd /var/www/api

if [[ ! -d /var/www/api/vendor ]]; then
    /usr/local/bin/composer install --no-progress --prefer-dist
fi

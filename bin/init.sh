#!/bin/sh

port=':80'

if  [[ -z "$1" ]] ; then
    echo ''
else
    port=":$1"
fi

composer install

php bin/console d:d:d --force
php bin/console d:d:c
php bin/console d:m:m --no-interaction

curl -o /dev/null "localhost$port/import-society"
curl -o /dev/null "localhost$port/import-user/0"
curl -o /dev/null "localhost$port/import-gamme"
curl -o /dev/null "localhost$port/import-order-product"
curl -o /dev/null "localhost$port/import-order"
curl -o /dev/null "localhost$port/import-order-line"

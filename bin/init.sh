#!/bin/sh

port=':80'

if  [[ -z "$1" ]] ; then
    echo ''
else
    port=":$1"
fi

# composer install

php bin/console d:d:d --force
php bin/console d:d:c
php bin/console d:m:m --no-interaction

echo "localhost$port/import-society"
curl -o /dev/null "localhost$port/import-society"

echo "localhost$port/import-user/0"
curl -o /dev/null "localhost$port/import-user/0"

echo "localhost$port/import-gamme"
curl -o /dev/null "localhost$port/import-gamme"

echo "localhost$port/import-order-item"
curl -o /dev/null "localhost$port/import-order-item"

echo "localhost$port/import-order"
curl -o /dev/null "localhost$port/import-order"

echo "localhost$port/import-order-line"
curl -o /dev/null "localhost$port/import-order-line"

echo "localhost$port/import-price-item"
curl -o /dev/null "localhost$port/import-price-item"

echo "localhost$port/import-quantity-item"
curl -o /dev/null "localhost$port/import-quantity-item"

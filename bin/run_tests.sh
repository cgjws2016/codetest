#!/usr/bin/env bash

if [ ! -d "vendor" ]; then
  echo "Installing dependencies..."
  composer install --dev --prefer-dist
fi

set -x

./vendor/bin/behat
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests


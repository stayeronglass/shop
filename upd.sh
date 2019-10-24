#!/bin/bash

git pull

php bin/console cache:clear
php bin/console doctrine:cache:clear-metadata
php bin/console doctrine:cache:clear-result

php bin/console cache:pool:clear cache.app

#!/bin/bash

git pull

php7.3 bin/console cache:clear
php7.3 bin/console doctrine:cache:clear-metadata
php7.3 bin/console doctrine:cache:clear-result

php7.3 bin/console cache:pool:clear cache.app

composer dump-autoload --optimize --classmap-authoritative

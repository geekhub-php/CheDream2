#!/bin/bash

php composer.phar update
php vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/bin/build_bootstrap.php

rm -rf app/cache/*
rm -rf app/logs/*

APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data' | grep -v root | head -1 | cut -d\  -f1`

setfacl -R -m u:"$APACHEUSER":rwX -m u:`whoami`:rwX app/cache app/logs web/upload
setfacl -dR -m u:"$APACHEUSER":rwX -m u:`whoami`:rwX app/cache app/logs web/upload

php app/console doctrine:database:drop --force
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
php app/console cache:clear
php app/console doctrine:fixtures:load --no-interaction
php app/console assets:install --symlink
php app/console assetic:dump
php app/console cache:clear

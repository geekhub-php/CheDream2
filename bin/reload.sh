#!/bin/bash

echo ""
echo "Выберите необходимое действие:"
echo "    1 - Стандартный reload."
echo "    2 - Перезагрузка БД."
echo "    3 - ТЕСТЫ."
echo "    0 - Выход. \n"
read reload

case $reload in
1)
    echo "Стандартный reload. \n"
    php composer.phar update
    php vendor/sensio/distribution-bundle/Sensio/Bundle/DistributionBundle/Resources/bin/build_bootstrap.php

    rm -rf app/cache/*
    rm -rf app/logs/*
    touch app/logs/error.log

    APACHEUSER=`ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data' | grep -v root | head -1 | cut -d\  -f1`

    mkdir -p web/upload/media
    rm -rf web/upload/media/*
    rm -rf web/upload/dream/*
    rm -rf web/upload/tmp/*

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
;;
2)
    echo "Перезагрузка БД. \n"
    php app/console doctrine:database:drop --force
    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force
    php app/console doctrine:fixtures:load --no-interaction
    php app/console assets:install --symlink
    php app/console assetic:dump
    php app/console cache:clear
;;
3)
    echo ""
    echo "ТЕСТЫ: Выберите необходимое действие:"
    echo "    1 - Запуск всех тестов."
    echo "    2 - Запуск юнит тестов."
    echo "    0 - Выход. \n"
    read testing

    case $testing in
    1)
        echo "Запуск всех тестов. \n"
        php app/console doctrine:database:drop --force
        php app/console doctrine:database:create
        php app/console doctrine:schema:update --force
        php app/console cache:clear
        php app/console doctrine:fixtures:load --no-interaction
        php app/console cache:clear

        sh bin/tests.sh
    ;;
    2)
        echo "Запуск юнит тестов. \n"
        php app/console doctrine:database:drop --force
        php app/console doctrine:database:create
        php app/console doctrine:schema:update --force
        php app/console cache:clear
        php app/console doctrine:fixtures:load --no-interaction
        php app/console cache:clear

        bin/phpunit -c app
    ;;
    0)
        exit 0
    ;;
    *)
        echo "Введите правильное действие! \n"
        sh bin/reload.sh

    esac
;;
0)
    exit 0
;;
*)
    echo "Введите правильное действие! \n"
    sh bin/reload.sh

esac

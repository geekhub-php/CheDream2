#!/bin/bash
pid=`ps aux | grep selenium | awk '{print $2}'`
if [ -z "$var" ]; then
  kill -9 $pid;
fi

java -jar ./selenium-server-standalone-2.41.0.jar  > /dev/null &
sleep 5

cp -u src/Geekhub/DreamBundle/DataFixtures/ORM/images/enakin.jpg web/upload/tmp/enakin.jpg

bin/phpunit -c app

bin/behat @GeekhubUserBundle
bin/behat src/Geekhub/DreamBundle/Features/newDreamRedirect.feature
bin/behat src/Geekhub/DreamBundle/Features/dreamLifeCycle.feature
#bin/behat @GeekhubDreamBundle
bin/behat @GeekhubResourceBundle

pid=`ps aux | grep selenium | awk '{print $2}'`
kill -9 $pid;

#!/bin/bash
pid=`ps aux | grep selenium | awk '{print $2}'`
if [ -z "$var" ]; then
  kill -9 $pid;
fi

java -jar ./selenium-server-standalone-2.41.0.jar  > /dev/null &
sleep 5

bin/phpunit -c app
bin/behat @GeekhubUserBundle
bin/behat @GeekhubDreamBundle
bin/behat @GeekhubResourceBundle

pid=`ps aux | grep selenium | awk '{print $2}'`
kill -9 $pid;

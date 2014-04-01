CheDream [![Build Status](https://travis-ci.org/geekhub-php/CheDream2.png?branch=develop)](https://travis-ci.org/geekhub-php/CheDream2) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/geekhub-php/CheDream2/badges/quality-score.png?s=4ecfb52f0cdd36aa70177671d39b84303806c548)](https://scrutinizer-ci.com/g/geekhub-php/CheDream2/)
========

Chedream - This is an open source project for the Cherkasy city administration.
The project is developed through the efforts of the project [Geekhub][1]

Tests
-----
Use this command for run unit tests:
```bash
bin/phpunit -c app
```
Before run behavior tests you need to download Selenium server
http://selenium-release.storage.googleapis.com/2.41/selenium-server-standalone-2.41.0.jar
And run it:
```bash
java -jar -Dselenium.LOGGER=/tmp/selenium /your/destination/dir/selenium-server-standalone-2.41.0.jar &
```

Use bin/behat @BundleName
```bash
bin/behat @GeekhubDreamBundle
bin/behat @GeekhubResourceBundle
```

Api documentation
-----------------

http://chedream.local/api/doc/

Bug tracking
------------

Chedream uses [GitHub issues](https://github.com/geekhub-php/CheDream2/issues).
If you have found bug, please create an issue.

Authors
-------

Chedream was originally created by [Geekhub Project Team](http://geekhub.ck.ua).

[1]:  http://geekhub.ck.ua/

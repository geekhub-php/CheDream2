CheDream [![Build Status](https://travis-ci.org/geekhub-php/CheDream2.png?branch=develop)](https://travis-ci.org/geekhub-php/CheDream2) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/geekhub-php/CheDream2/badges/quality-score.png?s=4ecfb52f0cdd36aa70177671d39b84303806c548)](https://scrutinizer-ci.com/g/geekhub-php/CheDream2/)
========
!DEPRECATED!

For js deps use:
- node v0.10.29
- bower@1.3.8
- less@1.7.4

Chedream - This is an open source project for the Cherkasy city administration.
The project is developed through the efforts of the project [Geekhub][1]

##Installation

1. Clone project to your local place

    ```sh
    git clone https://github.com/geekhub-php/CheDream2.git /path
    ```
2. Install Node.js Download sources from official web [site][2]
    - Extract node.js archive to your local directory
    - Run terminal and go to extracted node.js directory node/lib/node_modules/npm/
    - Enter next commands

        ```sh
        ./configure
        make
        make install
        ```
3. In terminal enter next command for create sym link

    ```sh
    ln -s /usr/bin/node /usr/local/bin/node
    ```
4. Install global less module for node.js

    ```sh
    npm -g install less
    ```
5. Install global Bower (the browser package manager)

    ```sh
    npm -g install bower
    ```
6. Expand you project, enter in web_root/path/to/you/project/web next command and answer configuration questions:

    ```sh
    sh bin/reload.sh
    ```

7. That all.


Tests
-----
Use this command for run tests:
```bash
sudo sh bin/tests.sh
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
[2]:  http://nodejs.org/

CheDream [![Build Status](https://travis-ci.org/geekhub-php/CheDream2.png?branch=develop)](https://travis-ci.org/geekhub-php/CheDream2) [![Scrutinizer Quality Score](https://scrutinizer-ci.com/g/geekhub-php/CheDream2/badges/quality-score.png?s=4ecfb52f0cdd36aa70177671d39b84303806c548)](https://scrutinizer-ci.com/g/geekhub-php/CheDream2/)
========

Chedream - This is an open source project for the Cherkasy city administration.
The project is developed through the efforts of the project [Geekhub][1]

Installation
-----
<ol>
<li>
Clone project to you local place

```
git clone https://github.com/geekhub-php/CheDream2.git /path
```
</li>
<li>
Install Node.js Download sources from official web [site][2]
<ol>
<li>Extract node.js archive to you local directory</li>
<li>Run terminal and go to extracted node.js directory</li>
<li>Enter next commands:

```bash
./configure
make
make install
```
</li>
</ol>
</li>
<li>In terminal enter next command for create sym link

```bash
ln -s /usr/bin/node /usr/local/bin/node
```
</li>
<li>Install global less module for node.js

```bash
npm -g install less
```
</li>
<li>Install global Bower (the browser package manager)

```bash
npm -g install bower
```
</li>
<li>Expand you project, enter in web_root/path/to/you/project/web next command and answer configuration questions:

```bash
sh bin/reload.sh
```
</li>
<li>That all.</li>
</ol>

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

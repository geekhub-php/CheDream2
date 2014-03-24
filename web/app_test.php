<?php

use Symfony\Component\HttpFoundation\Request;

$loader = require __DIR__.'/../app/bootstrap.php.cache';
require __DIR__.'/../app/AppKernel.php';
var_dump('app_dev.php_require_works');
$kernel = new AppKernel('test', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

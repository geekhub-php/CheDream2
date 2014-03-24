<?php

use Symfony\Component\HttpFoundation\Request;

var_dump(file_exists(__DIR__.'/../app/bootstrap.php.cache'));
var_dump($loader = require_once __DIR__.'/../app/bootstrap.php.cache');
exit;
$loader = require_once __DIR__.'/../app/bootstrap.php.cache';
require_once __DIR__.'/../app/AppKernel.php';

$kernel = new AppKernel('test', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

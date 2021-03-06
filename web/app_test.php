<?php

use Symfony\Component\HttpFoundation\Request;

$bootstrapPath = realpath(__DIR__.'/../app/bootstrap.php.cache');
$appKernerl = realpath(__DIR__.'/../app/AppKernel.php');

$loader = require_once $bootstrapPath;
require_once $appKernerl;

$kernel = new AppKernel('test', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

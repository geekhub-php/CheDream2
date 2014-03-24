<?php

use Symfony\Component\HttpFoundation\Request;

$bootstrapPath = realpath(__DIR__.'/../app/bootstrap.php.cache');
$appKernerl = realpath(__DIR__.'/../app/AppKernel.php');

var_dump($bootstrapPath);
var_dump($appKernerl);

try {
    $loader = require $bootstrapPath;
    require $appKernerl;
} catch (\Exeption $e) {
    echo $e->getMessage();
}

var_dump('app_dev.php_require_works');
$kernel = new AppKernel('test', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);

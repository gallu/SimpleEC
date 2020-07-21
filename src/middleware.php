<?php
// Application middleware
namespace App\Middleware;

// Cookie
//$app->add(new \SlimLittleTools\Middleware\Cookie($app->getContainer()));

// routting ??
$app->addRoutingMiddleware();

// New Method Overriding Middleware
$app->add(new \Slim\Middleware\MethodOverrideMiddleware());

// CSRF
$app->add('csrf');



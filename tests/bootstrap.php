<?php

/*
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>
 */

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Adris\Silex\Provider\ControllerServiceProvider(), array(
    'adris.controllers.namespace' => 'Adris\Silex\Test\Controller',
    'adris.controllers.class.prefix' => '',
    'adris.controllers.class.suffix' => '',
    'adris.controllers.method.prefix' => '',
    'adris.controllers.method.suffix' => 'Action',
));

return $app;

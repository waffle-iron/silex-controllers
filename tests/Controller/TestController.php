<?php

/*
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>
 */

namespace Adris\Silex\Controllers\Test\Controller;

use Adris\Silex\Controllers\ContainerAware;

class TestController extends ContainerAware
{
    public function indexAction()
    {
        return 'ok';
    }
}

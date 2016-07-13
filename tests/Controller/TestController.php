<?php

/*
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>
 */

namespace Adris\Silex\Test\Controller;

use Adris\Silex\Controller\ContainerAware;

class TestController extends ContainerAware
{
    public function indexAction()
    {
        return 'ok';
    }
}

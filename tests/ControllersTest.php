<?php

/*
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>
 */

namespace Adris\Silex\Controllers\Test;

use ReflectionClass;
use Silex\WebTestCase;
use Adris\Silex\Controllers\Service\ControllerResolver;

class ControllersTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/bootstrap.php';

        return $app;
    }

    private static function getMethod()
    {
        $method = (new ReflectionClass(ControllerResolver::class))->getMethod('createController');
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Unable to find controller "NotFound"
     */
    public function testInvalidArgumentException()
    {
        $ctrlr = new ControllerResolver($this->createApplication());

        self::getMethod()->invokeArgs($ctrlr, array('NotFound'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Class "\Adris\Silex\Controllers\Test\Controller\NotFound" does not exist.
     */
    public function testClassNotFound()
    {
        $ctrlr = new ControllerResolver($this->createApplication());

        self::getMethod()->invokeArgs($ctrlr, array('NotFound::index'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Class "TestController" does not exist.
     */
    public function testClassNotFoundNamespace()
    {
        $app = $this->createApplication();
        $app['adris.controllers.namespace'] = '';
        $ctrlr = new ControllerResolver($app);

        self::getMethod()->invokeArgs($ctrlr, array('TestController::index'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Class "\Adris\Silex\Controllers\Test\Controller\NotFoundController" does not exist.
     */
    public function testClassNotFoundWithSufix()
    {
        $app = $this->createApplication();
        $app['adris.controllers.class.suffix'] = 'Controller';
        $ctrlr = new ControllerResolver($app);

        self::getMethod()->invokeArgs($ctrlr, array('NotFound::index'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Class "\Adris\Silex\Controllers\Test\Controller\IsNotFound" does not exist.
     */
    public function testClassNotFoundWithPrefix()
    {
        $app = $this->createApplication();
        $app['adris.controllers.class.prefix'] = 'Is';
        $ctrlr = new ControllerResolver($app);

        self::getMethod()->invokeArgs($ctrlr, array('NotFound::index'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Class "\Adris\Silex\Controllers\Test\Controller\IsNotFoundController" does not exist.
     */
    public function testClassNotFoundWithSufixAndPrefix()
    {
        $app = $this->createApplication();
        $app['adris.controllers.class.prefix'] = 'Is';
        $app['adris.controllers.class.suffix'] = 'Controller';
        $ctrlr = new ControllerResolver($app);

        self::getMethod()->invokeArgs($ctrlr, array('NotFound::index'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Method "notFoundAction" does not exist.
     */
    public function testMethodNotFound()
    {
        $ctrlr = new ControllerResolver($this->createApplication());

        self::getMethod()->invokeArgs($ctrlr, array('TestController::notFound'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Method "prefnotFoundAction" does not exist.
     */
    public function testMethodNotFoundWithPrefix()
    {
        $app = $this->createApplication();
        $app['adris.controllers.method.prefix'] = 'pref';
        $ctrlr = new ControllerResolver($app);

        self::getMethod()->invokeArgs($ctrlr, array('TestController::notFound'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Method "notFoundAction" does not exist.
     */
    public function testMethodNotFoundWithSufix()
    {
        $app = $this->createApplication();
        $app['adris.controllers.method.suffix'] = 'Action';
        $ctrlr = new ControllerResolver($app);

        self::getMethod()->invokeArgs($ctrlr, array('TestController::notFound'));
    }

    /**
     * @expectedException        InvalidArgumentException
     * @expectedExceptionMessage Method "prefnotFoundAction" does not exist.
     */
    public function testMethodNotFoundWithSufixAndPrefix()
    {
        $app = $this->createApplication();
        $app['adris.controllers.method.prefix'] = 'pref';
        $app['adris.controllers.method.suffix'] = 'Action';
        $ctrlr = new ControllerResolver($app);

        self::getMethod()->invokeArgs($ctrlr, array('TestController::notFound'));
    }

    public function testCallable()
    {
        $ctrlr = new ControllerResolver($this->createApplication());

        $callback = self::getMethod()->invokeArgs($ctrlr, array('TestController::index'));

        $this->assertTrue(is_callable($callback));
    }
}

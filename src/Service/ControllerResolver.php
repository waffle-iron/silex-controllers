<?php

/*
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>
 */

namespace Adris\Silex\Service;

use Silex\ControllerResolver as BaseControllerResolver;
use Adris\Silex\Controller\ContainerAware;
use InvalidArgumentException;

/**
 * Class ControllerResolver.
 *
 * http://silex.sensiolabs.org/doc/providers.html#controller-providers
 */
class ControllerResolver extends BaseControllerResolver
{
    private function prepareClass($class)
    {
        $class_namespace = $this->app['adris.controllers.namespace'];
        $class_prefix = $this->app['adris.controllers.class.prefix'];
        $class_sufix = $this->app['adris.controllers.class.suffix'];

        if (!empty($class_namespace)) {
            if (substr($class_namespace, 0, 1) !== '\\') {
                $class_namespace = '\\'.$class_namespace;
            }

            if (substr($class_namespace, -1) !== '\\') {
                $class_namespace .= '\\';
            }
        }

        // remove class prefix
        if (!empty($class_prefix)) {
            $class = preg_replace(sprintf('/^%s/', $class_prefix), '', $class);
        }

        // remove class sufix
        if (!empty($class_sufix)) {
            $class = preg_replace(sprintf('/%s$/', $class_sufix), '', $class);
        }

        // add namespace
        return sprintf('%s%s%s%s', $class_namespace, $class_prefix, $class, $class_sufix);
    }

    private function prepareMethod($method)
    {
        $method_prefix = $this->app['adris.controllers.method.prefix'];
        $method_sufix = $this->app['adris.controllers.method.suffix'];

        // remove method prefix
        if (!empty($method_prefix)) {
            $method = preg_replace(sprintf('/^%s/', $method_prefix), '', $method);
        }

        // remove method sufix
        if (!empty($method_sufix)) {
            $method = preg_replace(sprintf('/%s$/', $method_sufix), '', $method);
        }

        return sprintf('%s%s%s', $method_prefix, $method, $method_sufix);
    }

    /**
     * @see Silex\ControllerResolver::createController
     */
    protected function createController($controller)
    {
        if (false === strpos($controller, '::')) {
            throw new InvalidArgumentException(sprintf('Unable to find controller "%s".', $controller));
        }

        list($class, $method) = explode('::', $controller, 2);

        $class = $this->prepareClass($class);
        $method = $this->prepareMethod($method);

        if (!class_exists($class)) {
            throw new InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $controller = new $class();

        if ($controller instanceof ContainerAware) {
            $controller->setContainer($this->app);
        }

        $methods = get_class_methods($controller);
        if (!in_array($method, $methods)) {
            throw new InvalidArgumentException(sprintf('Method "%s" does not exist.', $method));
        }

        return [$controller, $method];
    }
}

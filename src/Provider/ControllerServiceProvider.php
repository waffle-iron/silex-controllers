<?php

/*
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>
 */

namespace Adris\Silex\Controllers\Provider;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\ServiceControllerResolver;
use Adris\Silex\Controllers\Service\ControllerResolver;

/**
 * Class ControllerServiceProvider.
 */
class ControllerServiceProvider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple['adris.controllers.namespace'] = '';
        $pimple['adris.controllers.class.prefix'] = '';
        $pimple['adris.controllers.class.suffix'] = '';
        $pimple['adris.controllers.method.prefix'] = '';
        $pimple['adris.controllers.method.suffix'] = '';

        $pimple->extend('resolver', function ($resolver, $pimple) {
            return new ServiceControllerResolver(new ControllerResolver($pimple, $pimple['logger']), $pimple['callback_resolver']);
        });
    }
}

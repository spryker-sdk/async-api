<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\Application as SprykerApplication;
use Spryker\Zed\Application\Business\Model\Router\MvcRouter;
use Symfony\Cmf\Component\Routing\ChainRouter;

/**
 * @deprecated Use {@link \Spryker\Zed\Router\Communication\Plugin\Router\ZedRouterPlugin} instead.
 */
class MvcRoutingServiceProvider implements ServiceProviderInterface
{
    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app[SprykerApplication::SERVICE_ROUTER] = $app->share(
            $app->extend(SprykerApplication::SERVICE_ROUTER, function (ChainRouter $chainRouter) use ($app) {
                $chainRouter->add(new MvcRouter($app));

                return $chainRouter;
            })
        );
    }
}

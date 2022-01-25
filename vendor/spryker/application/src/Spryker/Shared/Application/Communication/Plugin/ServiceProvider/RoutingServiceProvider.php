<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Application\Application as SprykerApplication;
use Symfony\Cmf\Component\Routing\ChainRouter;

/**
 * @deprecated Use {@link \Spryker\Zed\Router\Communication\Plugin\Application\RouterApplicationPlugin} instead.
 * @deprecated Use {@link \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin} instead.
 *
 * Requesting the `url_matcher` from the container returned an instance of the ChainRouter. Instead of using several keys
 * pointing to the ChainRouter we only use `routers` from now on.
 */
class RoutingServiceProvider implements ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['url_matcher'] = $app->share(function () use ($app) {
            /** @var \Symfony\Cmf\Component\Routing\ChainRouter $chainRouter */
            $chainRouter = $app[SprykerApplication::SERVICE_ROUTER];
            $chainRouter->setContext($app['request_context']);

            return $chainRouter;
        });

        $app[SprykerApplication::SERVICE_ROUTER] = $app->share(function () {
            return new ChainRouter();
        });
    }

    /**
     * @codeCoverageIgnore
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}

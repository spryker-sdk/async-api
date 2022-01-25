<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Twig\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Symfony\Bridge\Twig\Extension\RoutingExtension;
use Twig\Environment;

class RoutingTwigPlugin implements TwigPluginInterface
{
    /**
     * @see \Spryker\Shared\Application\Application::SERVICE_ROUTER
     */
    protected const SERVICE_ROUTERS = 'routers';

    protected const SERVICE_URL_GENERATOR = 'url_generator';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        if (!class_exists(RoutingExtension::class)) {
            return $twig;
        }

        if ($container->has(static::SERVICE_ROUTERS)) {
            $twig->addExtension(new RoutingExtension($container->get(static::SERVICE_ROUTERS)));

            return $twig;
        }

        if ($container->has(static::SERVICE_URL_GENERATOR)) {
            $twig->addExtension(new RoutingExtension($container->get(static::SERVICE_URL_GENERATOR)));

            return $twig;
        }

        return $twig;
    }
}

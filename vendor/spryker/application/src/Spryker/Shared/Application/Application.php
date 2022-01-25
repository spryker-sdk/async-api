<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application;

use Spryker\Service\Container\Container;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\TerminableInterface;
use Symfony\Component\Routing\Loader\ClosureLoader;
use Symfony\Component\Routing\Router;

class Application extends Container implements HttpKernelInterface, TerminableInterface, ApplicationInterface
{
    /**
     * @see \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @see \Symfony\Component\HttpFoundation\Request
     */
    public const SERVICE_REQUEST = 'request';

    /**
     * @see \Symfony\Component\HttpFoundation\RequestStack
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @var \Spryker\Shared\ApplicationExtension\Dependency\Plugin\BootableApplicationPluginInterface[]
     */
    protected $bootablePlugins = [];

    /**
     * @var \Spryker\Service\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * @param \Spryker\Service\Container\ContainerInterface|null $container
     * @param \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[] $applicationPlugins
     */
    public function __construct(?ContainerInterface $container = null, array $applicationPlugins = [])
    {
        parent::__construct();

        if ($container === null) {
            $container = new Container();
        }

        $this->container = $container;
        $this->enableHttpMethodParameterOverride();

        foreach ($applicationPlugins as $applicationPlugin) {
            $this->registerApplicationPlugin($applicationPlugin);
        }
    }

    /**
     * @param \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface $applicationPlugin
     *
     * @return $this
     */
    public function registerApplicationPlugin(ApplicationPluginInterface $applicationPlugin)
    {
        $this->container = $applicationPlugin->provide($this->container);

        if ($applicationPlugin instanceof BootableApplicationPluginInterface) {
            $this->bootablePlugins[] = $applicationPlugin;
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function boot()
    {
        if (!$this->booted) {
            $this->booted = true;
            $this->bootPlugins();
        }

        return $this;
    }

    /**
     * @return void
     */
    public function run(): void
    {
        $request = Request::createFromGlobals();

        $response = $this->handle($request);
        $response->send();
        $this->terminate($request, $response);
    }

    /**
     * @internal This method is called from the run() method and is for internal use only.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $type
     * @param bool $catch
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true): Response
    {
        $this->container->set('request', $request);

        if ($this->container->has('controllers')) {
            $this->flushControllers();
        }

        $response = $this->getKernel()->handle($request);

        return $response;
    }

    /**
     * @deprecated Will be removed without replacement. This method was only used for Silex Controller. Once a project moved to using Application Plugins instead of Silex Service Providers it can stop using it.
     *
     * @return void
     */
    public function flushControllers()
    {
        $routeCollection = $this->container->get('controllers')->flush();

        // `controllers` is set by the `\Silex\Provider\RoutingServiceProvider` and might not be used anymore.
        // For projects which make use of the previous router this ensures that `routes` is filled with a
        // proper RouteCollection which contains all routes.
        $this->container->get('routes')->addCollection($routeCollection);

        // When projects make use of the new Router we need to make sure that we add all `controllers` as new Router to
        // the ChainRouter.
        /** @var \Symfony\Cmf\Component\Routing\ChainRouterInterface $chainRouter */
        $chainRouter = $this->container->get(static::SERVICE_ROUTER);

        $loader = new ClosureLoader();
        $resource = function () use ($routeCollection) {
            return $routeCollection;
        };
        $router = new Router($loader, $resource);
        $chainRouter->add($router);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        $this->getKernel()->terminate($request, $response);
    }

    /**
     * @return void
     */
    protected function bootPlugins(): void
    {
        foreach ($this->bootablePlugins as $bootablePlugin) {
            $this->container = $bootablePlugin->boot($this->container);
        }
    }

    /**
     * @return \Symfony\Component\HttpKernel\HttpKernel
     */
    protected function getKernel(): HttpKernel
    {
        return $this->container->get('kernel');
    }

    /**
     * Allow overriding http method. Needed to use the "_method" parameter in forms.
     * This should not be changeable by projects
     *
     * @return void
     */
    protected function enableHttpMethodParameterOverride()
    {
        Request::enableHttpMethodParameterOverride();
    }
}

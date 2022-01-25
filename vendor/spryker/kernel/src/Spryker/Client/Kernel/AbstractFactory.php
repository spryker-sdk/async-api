<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Kernel;

use Spryker\Client\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException;
use Spryker\Shared\Kernel\ContainerGlobals;
use Spryker\Shared\Kernel\ContainerMocker\ContainerMocker;

abstract class AbstractFactory
{
    use BundleConfigResolverAwareTrait;
    use ContainerMocker;

    /**
     * @var array<\Spryker\Client\Kernel\Container>
     */
    protected static $containers = [];

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return $this
     */
    public function setContainer(Container $container)
    {
        static::$containers[static::class] = $container;

        return $this;
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Client\Kernel\Exception\Container\ContainerKeyNotFoundException
     *
     * @return mixed
     */
    protected function getProvidedDependency($key)
    {
        $container = $this->getContainer();

        if ($container->has($key) === false) {
            throw new ContainerKeyNotFoundException($this, $key);
        }

        return $container->get($key);
    }

    /**
     * @return \Spryker\Client\Kernel\Container
     */
    protected function getContainer(): Container
    {
        $containerKey = static::class;

        if (!isset(static::$containers[$containerKey])) {
            static::$containers[$containerKey] = $this->createContainerWithProvidedDependencies();
        }

        return static::$containers[$containerKey];
    }

    /**
     * @return \Spryker\Client\Kernel\Container
     */
    protected function createContainerWithProvidedDependencies()
    {
        $container = $this->createContainer();
        $dependencyProvider = $this->resolveDependencyProvider();
        $this->provideExternalDependencies($dependencyProvider, $container);

        /** @var \Spryker\Client\Kernel\Container $container */
        $container = $this->overwriteForTesting($container);

        return $container;
    }

    /**
     * @return \Spryker\Client\Kernel\Container
     */
    protected function createContainer()
    {
        $containerGlobals = $this->createContainerGlobals();
        $container = new Container($containerGlobals->getContainerGlobals());

        return $container;
    }

    /**
     * @return \Spryker\Shared\Kernel\ContainerGlobals
     */
    protected function createContainerGlobals()
    {
        return new ContainerGlobals();
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractDependencyProvider
     */
    protected function resolveDependencyProvider()
    {
        return $this->createDependencyProviderResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Client\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function createDependencyProviderResolver()
    {
        return new DependencyProviderResolver();
    }

    /**
     * @param \Spryker\Client\Kernel\AbstractDependencyProvider $dependencyProvider
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return void
     */
    protected function provideExternalDependencies(AbstractDependencyProvider $dependencyProvider, Container $container)
    {
        $dependencyProvider->provideServiceLayerDependencies($container);
    }
}

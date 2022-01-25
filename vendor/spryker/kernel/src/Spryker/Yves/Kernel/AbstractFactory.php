<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel;

use Spryker\Client\Kernel\ClassResolver\Client\ClientResolver;
use Spryker\Shared\Kernel\ContainerGlobals;
use Spryker\Shared\Kernel\ContainerMocker\ContainerMocker;
use Spryker\Yves\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver;
use Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Yves\Kernel\Dependency\Injector\DependencyInjector;
use Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface;
use Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException;

abstract class AbstractFactory implements FactoryInterface
{
    use BundleConfigResolverAwareTrait;
    use ContainerMocker;

    /**
     * @var \Spryker\Client\Kernel\AbstractClient
     */
    protected $client;

    /**
     * @var array<\Spryker\Yves\Kernel\Container>
     */
    protected static $containers = [];

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return $this
     */
    public function setContainer(Container $container)
    {
        static::$containers[static::class] = $container;

        return $this;
    }

    /**
     * @return \Spryker\Yves\Kernel\Container
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
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    protected function getClient()
    {
        if ($this->client === null) {
            $this->client = $this->resolveClient();
        }

        return $this->client;
    }

    /**
     * @return \Spryker\Client\Kernel\AbstractClient
     */
    protected function resolveClient()
    {
        return $this->createClientResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Client\Kernel\ClassResolver\Client\ClientResolver
     */
    protected function createClientResolver()
    {
        return new ClientResolver();
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Yves\Kernel\Exception\Container\ContainerKeyNotFoundException
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
     * @return \Spryker\Yves\Kernel\Container
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
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function createContainerWithProvidedDependencies()
    {
        $container = $this->createContainer();
        $dependencyInjectorCollection = $this->resolveDependencyInjectorCollection();
        $dependencyInjector = $this->createDependencyInjector($dependencyInjectorCollection);
        $dependencyProvider = $this->resolveDependencyProvider();

        $container = $this->provideDependencies($dependencyProvider, $container);
        $container = $dependencyInjector->inject($container);

        /** @var \Spryker\Yves\Kernel\Container $container */
        $container = $this->overwriteForTesting($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideDependencies(AbstractBundleDependencyProvider $dependencyProvider, Container $container)
    {
        return $dependencyProvider->provideDependencies($container);
    }

    /**
     * @return \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface
     */
    protected function resolveDependencyInjectorCollection()
    {
        return $this->createDependencyInjectorResolver()->resolve($this);
    }

    /**
     * @param \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface $dependencyInjectorCollection
     *
     * @return \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjector
     */
    protected function createDependencyInjector(DependencyInjectorCollectionInterface $dependencyInjectorCollection)
    {
        return new DependencyInjector($dependencyInjectorCollection);
    }

    /**
     * @return \Spryker\Yves\Kernel\ClassResolver\DependencyInjector\DependencyInjectorResolver
     */
    protected function createDependencyInjectorResolver()
    {
        return new DependencyInjectorResolver();
    }

    /**
     * @return \Spryker\Yves\Kernel\AbstractBundleDependencyProvider
     */
    protected function resolveDependencyProvider()
    {
        return $this->createDependencyProviderResolver()->resolve($this);
    }

    /**
     * @return \Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver
     */
    protected function createDependencyProviderResolver()
    {
        return new DependencyProviderResolver();
    }
}

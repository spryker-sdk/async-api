<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Testify\Locator\Business;

use Spryker\Shared\Kernel\BundleConfigMock\BundleConfigMock;
use Spryker\Shared\Kernel\BundleProxy as KernelBundleProxy;
use Spryker\Shared\Kernel\ContainerMocker\ContainerMocker;
use Spryker\Shared\Testify\Config\TestifyConfig;
use Spryker\Zed\Kernel\AbstractFactory;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\ClassResolver\Business\BusinessFactoryResolver;
use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver;
use Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderResolver;
use Spryker\Zed\Testify\Locator\TestifyConfigurator;

class BundleProxy extends KernelBundleProxy
{
    use ContainerMocker;

    /**
     * @var string
     */
    protected $moduleName;

    /**
     * @var \Spryker\Zed\Testify\Locator\Business\BusinessLocator
     */
    protected $innerLocator;

    /**
     * @param \Spryker\Zed\Testify\Locator\Business\BusinessLocator $locator
     */
    public function __construct(BusinessLocator $locator)
    {
        $this->innerLocator = $locator;
    }

    /**
     * @param string $methodName
     * @param array $arguments
     *
     * @return object
     */
    public function __call(string $methodName, array $arguments)
    {
        if ($methodName === 'facade') {
            return $this->createFacade($methodName, $arguments);
        }

        return parent::__call($methodName, $arguments);
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function createFacade($method, array $arguments)
    {
        $facade = $this->getFacade($method, $arguments);
        $factory = $this->getFactory($facade);
        $dependencyProvider = $this->getDependencyProvider($factory);

        $configurator = $this->getConfigurator();
        /** @var \Spryker\Zed\Kernel\Container $container */
        $container = $configurator->getContainer();
        $container = $dependencyProvider->provideBusinessLayerDependencies(
            $container,
        );
        /** @var \Spryker\Zed\Kernel\Container $container */
        $container = $this->overwriteForTesting($container);

        $bundleConfig = $this->getBundleConfig($factory);

        $factory->setContainer($container);
        $factory->setConfig($bundleConfig);
        $facade->setFactory($factory);

        return $facade;
    }

    /**
     * @return \Spryker\Shared\Testify\Locator\TestifyConfiguratorInterface
     */
    protected function getConfigurator()
    {
        $config = new TestifyConfig();
        $container = new Container();
        $container->setLocator($this->innerLocator);

        return new TestifyConfigurator($container, $config);
    }

    /**
     * @param \Spryker\Zed\Kernel\Business\AbstractFacade $facade
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    protected function getFactory(AbstractFacade $facade)
    {
        $factoryResolver = new BusinessFactoryResolver();

        return $factoryResolver->resolve($facade);
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade($method, array $arguments)
    {
        /** @var \Spryker\Zed\Kernel\Business\AbstractFacade $facade */
        $facade = parent::__call($method, $arguments);

        return $facade;
    }

    /**
     * @param \Spryker\Zed\Kernel\AbstractFactory $factory
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleDependencyProvider
     */
    protected function getDependencyProvider(AbstractFactory $factory)
    {
        $dependencyResolver = new DependencyProviderResolver();

        return $dependencyResolver->resolve($factory);
    }

    /**
     * @param \Spryker\Zed\Kernel\AbstractFactory $factory
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    protected function getBundleConfig(AbstractFactory $factory)
    {
        $bundleConfigResolver = new BundleConfigResolver();

        $config = $bundleConfigResolver->resolve($factory);
        $bundleConfig = new BundleConfigMock();

        if ($bundleConfig->hasBundleConfigMock($config)) {
            /** @var \Spryker\Zed\Kernel\AbstractBundleConfig $config */
            $config = $bundleConfig->getBundleConfigMock($config);
        }

        return $config;
    }
}

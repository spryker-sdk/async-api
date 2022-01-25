<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Closure;
use Codeception\Configuration;
use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Shared\Testify\Locator\TestifyConfiguratorInterface;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Testify\Locator\Business\BusinessLocator as Locator;

/**
 * @deprecated Use {@link \SprykerTest\Zed\Testify\Helper\BusinessHelper} instead.
 */
class BusinessHelper extends Module
{
    /**
     * @var string
     */
    protected const BUSINESS_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\Business\%3$sBusinessFactory';

    /**
     * @var array
     */
    protected $dependencies = [];

    /**
     * @var \Spryker\Zed\Kernel\Business\AbstractBusinessFactory|null
     */
    protected $factoryStub;

    /**
     * @var array
     */
    protected $mockedFactoryMethods = [];

    /**
     * @return \Spryker\Shared\Kernel\LocatorLocatorInterface&\Generated\Zed\Ide\AutoCompletion|\Generated\Service\Ide\AutoCompletion
     */
    public function getLocator()
    {
        return new Locator();
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function setDependency(string $key, $value)
    {
        $this->dependencies[$key] = $value;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function getFacade(): AbstractFacade
    {
        $facade = $this->createFacade();
        $facade->setFactory($this->getFactory());

        return $facade;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function createFacade(): AbstractFacade
    {
        $currentNamespace = Configuration::config()['namespace'];
        $namespaceParts = explode('\\', $currentNamespace);
        $moduleName = lcfirst($namespaceParts[2]);

        return $this->getLocator()->$moduleName()->facade($this->createClosure());
    }

    /**
     * @param string $methodName
     * @param mixed $return
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory|object
     */
    public function mockFactoryMethod(string $methodName, $return)
    {
        $className = $this->getFactoryClassName();

        if (!method_exists($className, $methodName)) {
            throw new Exception(sprintf('You tried to mock a not existing method "%s". Available methods are "%s"', $methodName, implode(', ', get_class_methods($className))));
        }

        $this->mockedFactoryMethods[$methodName] = $return;
        $this->factoryStub = Stub::make($className, $this->mockedFactoryMethods);

        return $this->factoryStub;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    public function getFactory(): AbstractBusinessFactory
    {
        if ($this->factoryStub !== null) {
            return $this->factoryStub;
        }

        $moduleFactory = $this->createModuleFactory();
        if ($this->hasModule('\\' . ConfigHelper::class)) {
            $moduleFactory->setConfig($this->getConfig());
        }

        return $moduleFactory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    protected function createModuleFactory(): AbstractBusinessFactory
    {
        $moduleFactoryClassName = $this->getFactoryClassName();

        return new $moduleFactoryClassName();
    }

    /**
     * @return string
     */
    protected function getFactoryClassName(): string
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        return sprintf(static::BUSINESS_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $namespaceParts[2]);
    }

    /**
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    protected function getConfig(): AbstractBundleConfig
    {
        return $this->getConfigHelper()->getModuleConfig();
    }

    /**
     * @return \SprykerTest\Shared\Testify\Helper\ConfigHelper
     */
    protected function getConfigHelper(): ConfigHelper
    {
        return $this->getModule('\\' . ConfigHelper::class);
    }

    /**
     * @return \Closure
     */
    private function createClosure(): Closure
    {
        $dependencies = $this->getDependencies();
        $callback = function (TestifyConfiguratorInterface $configurator) use ($dependencies): void {
            foreach ($dependencies as $key => $value) {
                $configurator->getContainer()->set($key, $value);
            }
        };

        return $callback;
    }

    /**
     * @return array
     */
    private function getDependencies(): array
    {
        $dependencies = $this->dependencies;
        $this->dependencies = [];

        return $dependencies;
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        $this->factoryStub = null;
        $this->mockedFactoryMethods = [];
    }
}

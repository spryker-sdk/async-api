<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Testify\Helper;

use Codeception\Configuration;
use Codeception\Module;
use Codeception\Stub;
use Codeception\TestInterface;
use Exception;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Testify\Locator\Business\BusinessLocator as Locator;
use SprykerTest\Shared\Testify\Helper\ConfigHelper;

/**
 * @deprecated Use {@link \SprykerTest\Zed\Testify\Helper\Communication\CommunicationHelper} instead.
 */
class CommunicationHelper extends Module
{
    /**
     * @var string
     */
    protected const COMMUNICATION_FACTORY_CLASS_NAME_PATTERN = '\%1$s\%2$s\%3$s\Communication\%3$sCommunicationFactory';

    /**
     * @var \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory|null
     */
    protected $factoryStub;

    /**
     * @var array
     */
    protected $mockedFactoryMethods = [];

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    public function getFacade(): AbstractFacade
    {
        $facade = $this->createFacade();

        return $facade;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function createFacade(): AbstractFacade
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        $moduleName = lcfirst($namespaceParts[2]);

        return $this->getLocator()->$moduleName()->facade();
    }

    /**
     * @return \Spryker\Shared\Kernel\LocatorLocatorInterface&\Generated\Zed\Ide\AutoCompletion&\Generated\Service\Ide\AutoCompletion
     */
    public function getLocator()
    {
        return new Locator();
    }

    /**
     * @param string $methodName
     * @param mixed $return
     *
     * @throws \Exception
     *
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory|object
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
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    public function getFactory(): AbstractCommunicationFactory
    {
        if ($this->factoryStub !== null) {
            return $this->injectConfig($this->factoryStub);
        }

        $moduleFactory = $this->createFactory();

        return $this->injectConfig($moduleFactory);
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    protected function createFactory(): AbstractCommunicationFactory
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

        return sprintf(static::COMMUNICATION_FACTORY_CLASS_NAME_PATTERN, rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $namespaceParts[2]);
    }

    /**
     * @param \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory|object $moduleFactory
     *
     * @return \Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory
     */
    protected function injectConfig($moduleFactory): AbstractCommunicationFactory
    {
        if ($this->hasConfigHelper()) {
            $moduleFactory->setConfig($this->getConfig());
        }

        return $moduleFactory;
    }

    /**
     * @return bool
     */
    protected function hasConfigHelper(): bool
    {
        return $this->hasModule('\\' . ConfigHelper::class);
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

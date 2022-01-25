<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Propel\Dependency\Facade\PropelToLogBridge;
use Spryker\Zed\Propel\Dependency\Facade\PropelToTransferFacadeBridge;
use Spryker\Zed\Propel\Dependency\Service\PropelToUtilTextServiceBridge;

/**
 * @method \Spryker\Zed\Propel\PropelConfig getConfig()
 */
class PropelDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const UTIL_TEXT_SERVICE = 'util text service';

    /**
     * @var string
     */
    public const FACADE_LOG = 'FACADE_LOG';

    /**
     * @var string
     */
    public const FACADE_TRANSFER = 'FACADE_TRANSFER';

    /**
     * @var string
     */
    public const PLUGINS_PROPEL_SCHEMA_ELEMENT_FILTER = 'PLUGINS_PROPEL_SCHEMA_ELEMENT_FILTER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addUtilTextService($container);
        $container = $this->addPropelSchemaElementFilterPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addLogFacade($container);
        $container = $this->addTransferFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container)
    {
        $container->set(static::UTIL_TEXT_SERVICE, function (Container $container) {
            return new PropelToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLogFacade(Container $container)
    {
        $container->set(static::FACADE_LOG, function (Container $container) {
            return new PropelToLogBridge($container->getLocator()->log()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTransferFacade(Container $container): Container
    {
        $container->set(static::FACADE_TRANSFER, function (Container $container) {
            return new PropelToTransferFacadeBridge($container->getLocator()->transfer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelSchemaElementFilterPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_PROPEL_SCHEMA_ELEMENT_FILTER, function () {
            return $this->getPropelSchemaElementFilterPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\Propel\Dependency\Plugin\PropelSchemaElementFilterPluginInterface>
     */
    protected function getPropelSchemaElementFilterPlugins(): array
    {
        return [];
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Monitoring;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToLocaleFacadeBridge;
use Spryker\Zed\Monitoring\Dependency\Facade\MonitoringToStoreFacadeBridge;
use Spryker\Zed\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceBridge;

/**
 * @method \Spryker\Zed\Monitoring\MonitoringConfig getConfig()
 */
class MonitoringDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const MONITORING_SERVICE = 'monitoring service';

    /**
     * @var string
     */
    public const FACADE_STORE = 'store facade';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'locale facade';

    /**
     * @var string
     */
    public const SERVICE_UTIL_NETWORK = 'util network service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addUtilNetworkService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addMonitoringService($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addUtilNetworkService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMonitoringService(Container $container): Container
    {
        $container->set(static::MONITORING_SERVICE, function (Container $container) {
            return $container->getLocator()->monitoring()->service();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            $monitoringToStoreFacadeBridge = new MonitoringToStoreFacadeBridge(
                $container->getLocator()->store()->facade(),
            );

            return $monitoringToStoreFacadeBridge;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            $monitoringToLocaleFacadeBridge = new MonitoringToLocaleFacadeBridge(
                $container->getLocator()->locale()->facade(),
            );

            return $monitoringToLocaleFacadeBridge;
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilNetworkService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_NETWORK, function (Container $container) {
            $monitoringToUtilNetworkServiceBridge = new MonitoringToUtilNetworkServiceBridge(
                $container->getLocator()->utilNetwork()->service(),
            );

            return $monitoringToUtilNetworkServiceBridge;
        });

        return $container;
    }
}

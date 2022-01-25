<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Monitoring;

use Spryker\Service\Monitoring\MonitoringServiceInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface;
use Spryker\Yves\Monitoring\EventHandler\EventHandler;
use Spryker\Yves\Monitoring\EventHandler\EventHandlerInterface;
use Spryker\Yves\Monitoring\Plugin\ControllerListener;

/**
 * @method \Spryker\Yves\Monitoring\MonitoringConfig getConfig()
 */
class MonitoringFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Monitoring\Plugin\ControllerListener
     */
    public function createControllerListener(): ControllerListener
    {
        return new ControllerListener(
            $this->getMonitoringService(),
            $this->getUtilNetworkService(),
            $this->getConfig()->getIgnorableTransactionRouteNames(),
        );
    }

    /**
     * @return \Spryker\Service\Monitoring\MonitoringServiceInterface
     */
    public function getMonitoringService(): MonitoringServiceInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::MONITORING_SERVICE);
    }

    /**
     * @return \Spryker\Yves\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface
     */
    public function getUtilNetworkService(): MonitoringToUtilNetworkServiceInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::SERVICE_NETWORK);
    }

    /**
     * @deprecated Use {@link \Spryker\Yves\Monitoring\MonitoringFactory::getUtilNetworkService()} instead.
     *
     * @return \Spryker\Yves\Monitoring\Dependency\Service\MonitoringToUtilNetworkServiceInterface
     */
    public function getSystem(): MonitoringToUtilNetworkServiceInterface
    {
        return $this->getProvidedDependency(MonitoringDependencyProvider::SERVICE_NETWORK);
    }

    /**
     * @return \Spryker\Yves\Monitoring\EventHandler\EventHandlerInterface
     */
    public function createEventHandler(): EventHandlerInterface
    {
        return new EventHandler($this->getMonitoringService(), $this->getUtilNetworkService());
    }
}

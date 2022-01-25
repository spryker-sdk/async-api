<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application;

use Silex\Provider\FormServiceProvider;
use Silex\Provider\HttpFragmentServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\ValidatorServiceProvider;
use Spryker\Shared\ErrorHandler\Plugin\ServiceProvider\WhoopsErrorHandlerServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\HeaderServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\MvcRoutingServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\RequestServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\RoutingServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\SilexRoutingServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\SslServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\SubRequestServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\TranslationServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\TwigGlobalVariablesServiceProvider;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\UrlGeneratorServiceProvider;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 */
class ApplicationDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_PROVIDER = 'SERVICE_PROVIDER';
    public const SERVICE_PROVIDER_API = 'SERVICE_PROVIDER_API';
    public const INTERNAL_CALL_SERVICE_PROVIDER = 'INTERNAL_CALL_SERVICE_PROVIDER';
    public const INTERNAL_CALL_SERVICE_PROVIDER_WITH_AUTHENTICATION = 'INTERNAL_CALL_SERVICE_PROVIDER_WITH_AUTHENTICATION';

    /**
     * @deprecated Use {@link \Spryker\Zed\Application\ApplicationDependencyProvider::PLUGINS_BACKOFFICE_APPLICATION} instead.
     */
    public const PLUGINS_APPLICATION = 'PLUGINS_APPLICATION';
    public const PLUGINS_BACKOFFICE_APPLICATION = 'PLUGINS_BACKOFFICE_APPLICATION';
    public const PLUGINS_BACKEND_GATEWAY_APPLICATION = 'PLUGINS_BACKEND_GATEWAY_APPLICATION';
    public const PLUGINS_BACKEND_API_APPLICATION = 'PLUGINS_BACKEND_API_APPLICATION';

    /**
     * @deprecated Will be removed without replacement.
     */
    public const SERVICE_ENCODING = 'util encoding service';

    /**
     * @deprecated Will be removed without replacement.
     */
    public const ENVIRONMENT = 'ENVIRONMENT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addBackofficeApplicationPlugins($container);
        $container = $this->addBackendGatewayApplicationPlugins($container);
        $container = $this->addBackendApiApplicationPlugins($container);

        $container = $this->addApplicationPlugins($container);
        $container = $this->addServiceProviders($container);
        $container = $this->addApiServiceProviders($container);
        $container = $this->addInternalCallServiceProviders($container);
        $container = $this->addInternalCallServiceProvidersWithAuthentication($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addBackofficeApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_BACKOFFICE_APPLICATION, function (Container $container): array {
            return $this->getBackofficeApplicationPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getBackofficeApplicationPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addBackendGatewayApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_BACKEND_GATEWAY_APPLICATION, function (Container $container): array {
            return $this->getBackendGatewayApplicationPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getBackendGatewayApplicationPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addBackendApiApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_BACKEND_API_APPLICATION, function (Container $container): array {
            return $this->getBackendApiApplicationPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getBackendApiApplicationPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Application\ApplicationDependencyProvider::addBackofficeApplicationPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApplicationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_APPLICATION, function (Container $container): array {
            return $this->getApplicationPlugins();
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Application\ApplicationDependencyProvider::getBackofficeApplicationPlugins()} instead.
     *
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getApplicationPlugins(): array
    {
        return [];
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container)
    {
        $container->set(static::SERVICE_ENCODING, function (Container $container) {
            return $container->getLocator()->utilEncoding()->service();
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Application\ApplicationDependencyProvider::addApplicationPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addServiceProviders(Container $container)
    {
        $container->set(static::SERVICE_PROVIDER, function (Container $container) {
            return $this->getServiceProviders($container);
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Application\ApplicationDependencyProvider::getApplicationPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getServiceProviders(Container $container)
    {
        trigger_error(
            '\Spryker\Zed\Application\ApplicationDependencyProvider::getServiceProviders is deprecated, please copy all required ServiceProvider and remove the call to this method',
            E_USER_DEPRECATED
        );

        $providers = [
            new TwigGlobalVariablesServiceProvider(),
            new RequestServiceProvider(),

            new SslServiceProvider(),

            new ServiceControllerServiceProvider(),
            new RoutingServiceProvider(),
            new MvcRoutingServiceProvider(),
            new SilexRoutingServiceProvider(),

            new ValidatorServiceProvider(),
            new FormServiceProvider(),

            // To be removed with next major
            new UrlGeneratorServiceProvider(), // requests router from container

            new HttpFragmentServiceProvider(),
            new HeaderServiceProvider(),
            new TranslationServiceProvider(),
            new SubRequestServiceProvider(),
        ];

        if ($this->getConfig()->isPrettyErrorHandlerEnabled()) {
            $providers[] = new WhoopsErrorHandlerServiceProvider();
        }

        return $providers;
    }

    /**
     * @deprecated Use {@link getServiceProviders()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getServiceProvider(Container $container)
    {
        return $this->getServiceProviders($container);
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Application\ApplicationDependencyProvider::addApplicationPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApiServiceProviders(Container $container)
    {
        $container->set(static::SERVICE_PROVIDER_API, function (Container $container) {
            return $this->getApiServiceProviders($container);
        });

        return $container;
    }

    /**
     * @deprecated Use {@link \Spryker\Zed\Application\ApplicationDependencyProvider::getApplicationPlugins()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getApiServiceProviders(Container $container)
    {
        return [];
    }

    /**
     * @deprecated Use {@link addServiceProviders()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addInternalCallServiceProviders(Container $container)
    {
        $container->set(static::INTERNAL_CALL_SERVICE_PROVIDER, function (Container $container) {
            return $this->getInternalCallServiceProviders($container);
        });

        return $container;
    }

    /**
     * @deprecated Use {@link getServiceProviders()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getInternalCallServiceProviders(Container $container)
    {
        return [];
    }

    /**
     * @deprecated Use {@link addServiceProviders()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addInternalCallServiceProvidersWithAuthentication(Container $container)
    {
        $container->set(static::INTERNAL_CALL_SERVICE_PROVIDER_WITH_AUTHENTICATION, function (Container $container) {
            return $this->getInternalCallServiceProvidersWithAuthentication($container);
        });

        return $container;
    }

    /**
     * @deprecated Use {@link getServiceProviders()} instead.
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    protected function getInternalCallServiceProvidersWithAuthentication(Container $container)
    {
        return [];
    }
}

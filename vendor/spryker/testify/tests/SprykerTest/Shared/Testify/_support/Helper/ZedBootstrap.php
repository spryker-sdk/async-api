<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use Codeception\Exception\ModuleConfigException;
use Codeception\Lib\Framework;
use Codeception\Lib\Interfaces\DependsOnModule;
use Codeception\TestInterface;
use Codeception\Util\Stub;
use Spryker\Shared\Kernel\AbstractBundleConfig;
use Spryker\Zed\Testify\Bootstrap\ZedBootstrap as TestifyBootstrap;
use Spryker\Zed\Twig\TwigConfig;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Client;
use Symfony\Component\HttpKernel\HttpKernelBrowser;

class ZedBootstrap extends Framework implements DependsOnModule
{
    /**
     * @var string
     */
    public const CONFIG_KEY_SERVICE_PROVIDER = 'serviceProvider';

    /**
     * @var string
     */
    public const CONFIG_KEY_APPLICATION_PLUGINS = 'applicationPlugins';

    /**
     * @var \Spryker\Zed\Testify\Bootstrap\ZedBootstrap
     */
    protected $application;

    /**
     * @var \SprykerTest\Shared\Testify\Helper\BundleConfig
     */
    protected $bundleConfig;

    /**
     * @var array
     */
    protected $config = [
        self::CONFIG_KEY_SERVICE_PROVIDER => [],
        self::CONFIG_KEY_APPLICATION_PLUGINS => [],
    ];

    /**
     * @return array
     */
    public function _depends(): array
    {
        return [
            BundleConfig::class => 'You need to enable \SprykerTest\Shared\Testify\Helper\BundleConfig in order to mock bundle configurations',
        ];
    }

    /**
     * @param \SprykerTest\Shared\Testify\Helper\BundleConfig $bundleConfig
     *
     * @return void
     */
    public function _inject(BundleConfig $bundleConfig): void
    {
        $this->bundleConfig = $bundleConfig;
    }

    /**
     * @return void
     */
    public function _initialize(): void
    {
        $this->loadApplication();
        $this->mockBundleConfigs();
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        if (class_exists(HttpKernelBrowser::class)) {
            $this->client = new HttpKernelBrowser($this->application->boot());

            return;
        }

        $this->client = new Client($this->application->boot());
    }

    /**
     * @throws \Codeception\Exception\ModuleConfigException
     *
     * @return void
     */
    protected function loadApplication(): void
    {
        Request::setTrustedHosts(['localhost']);

        $requestFactory = function (array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) {
            $request = new Request($query, $request, $attributes, $cookies, $files, $server, $content);
            $request->server->set('SERVER_NAME', 'localhost');

            return $request;
        };
        Request::setFactory($requestFactory);

        $this->application = new TestifyBootstrap($this->config[static::CONFIG_KEY_APPLICATION_PLUGINS], $this->config[static::CONFIG_KEY_SERVICE_PROVIDER]);

        if (!isset($this->application)) {
            throw new ModuleConfigException(static::class, 'Application instance was not received from bootstrap file');
        }
    }

    /**
     * @return void
     */
    private function mockBundleConfigs(): void
    {
        $this->bundleConfig->addBundleConfigMock($this->getTwigBundleConfigMock());
    }

    /**
     * @return \Spryker\Shared\Kernel\AbstractBundleConfig
     */
    private function getTwigBundleConfigMock(): AbstractBundleConfig
    {
        $twigConfig = new TwigConfig();
        /** @var \Spryker\Shared\Kernel\AbstractBundleConfig $twigBundleConfigMock */
        $twigBundleConfigMock = Stub::make(TwigConfig::class, [
            'getTemplatePaths' => function () use ($twigConfig) {
                $paths = $twigConfig->getTemplatePaths();
                $paths[] = APPLICATION_VENDOR_DIR . '/spryker/spryker/Bundles/%2$s/src/*/Zed/%1$s/Presentation';

                return $paths;
            },
        ]);

        return $twigBundleConfigMock;
    }
}

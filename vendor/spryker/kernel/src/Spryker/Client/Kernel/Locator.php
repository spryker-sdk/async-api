<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Kernel;

use Spryker\Service\Kernel\ServiceLocator;
use Spryker\Shared\Kernel\BundleProxy;
use Spryker\Shared\Kernel\LocatorLocatorInterface;

class Locator implements LocatorLocatorInterface
{
    /**
     * @var \Spryker\Shared\Kernel\BundleProxy
     */
    protected $bundleProxy;

    /**
     * @var array<\Spryker\Shared\Kernel\AbstractLocator>
     */
    protected $locator;

    /**
     * @var static
     */
    private static $instance;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Should be private, because this class uses `Singleton` pattern.
     */
    private function __construct()
    {
    }

    /**
     * Should be private, because this class uses `Singleton` pattern.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * @param string $bundle
     * @param array|null $arguments
     *
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    public function __call($bundle, ?array $arguments = null)
    {
        if ($this->bundleProxy === null) {
            $this->bundleProxy = $this->getBundleProxy();
        }

        return $this->bundleProxy->setBundle($bundle);
    }

    /**
     * @return \Spryker\Shared\Kernel\BundleProxy
     */
    protected function getBundleProxy()
    {
        $bundleProxy = new BundleProxy();
        if ($this->locator === null) {
            $this->locator = [
                new ClientLocator(),
                new ServiceLocator(),
            ];
        }
        $bundleProxy->setLocators($this->locator);

        return $bundleProxy;
    }
}

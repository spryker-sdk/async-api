<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel;

use Spryker\Shared\Kernel\BundleConfigMock\BundleConfigMock;
use Spryker\Zed\Kernel\ClassResolver\Config\BundleConfigResolver;

trait BundleConfigResolverAwareTrait
{
    /**
     * @var \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Kernel\AbstractBundleConfig $config
     *
     * @return $this
     */
    public function setConfig(AbstractBundleConfig $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    public function getConfig()
    {
        if ($this->config === null) {
            $this->config = $this->resolveBundleConfig();
        }

        return $this->config;
    }

    /**
     * @return \Spryker\Zed\Kernel\AbstractBundleConfig
     */
    private function resolveBundleConfig()
    {
        $resolver = new BundleConfigResolver();
        $config = $resolver->resolve($this);

        $bundleConfigMock = new BundleConfigMock();
        if ($bundleConfigMock->hasBundleConfigMock($config)) {
            return $bundleConfigMock->getBundleConfigMock($config);
        }

        return $config;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Business;

use Spryker\Zed\Kernel\ClassResolver\Business\BusinessFactoryResolver;
use Spryker\Zed\Kernel\EntityManagerResolverAwareTrait;
use Spryker\Zed\Kernel\RepositoryResolverAwareTrait;

abstract class AbstractFacade
{
    use EntityManagerResolverAwareTrait;
    use RepositoryResolverAwareTrait;

    /**
     * @var \Spryker\Zed\Kernel\Business\BusinessFactoryInterface
     */
    protected $factory;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $factory
     *
     * @return $this
     */
    public function setFactory(AbstractBusinessFactory $factory)
    {
        $this->factory = $factory;

        return $this;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\BusinessFactoryInterface
     */
    protected function getFactory()
    {
        if ($this->factory === null) {
            $this->factory = $this->resolveFactory();
        }

        return $this->factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\Business\AbstractBusinessFactory
     */
    private function resolveFactory()
    {
        /** @var \Spryker\Zed\Kernel\Business\AbstractBusinessFactory $factory */
        $factory = $this->getFactoryResolver()->resolve($this);

        return $factory;
    }

    /**
     * @return \Spryker\Zed\Kernel\ClassResolver\Business\BusinessFactoryResolver
     */
    private function getFactoryResolver()
    {
        return new BusinessFactoryResolver();
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\DependencyProvider;

use Spryker\Zed\Kernel\ClassResolver\AbstractClassResolver;

class DependencyProviderResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'ZedDependencyProvider';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Zed\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException
     *
     * @return \Spryker\Zed\Kernel\AbstractBundleDependencyProvider
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Zed\Kernel\AbstractBundleDependencyProvider|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new DependencyProviderNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}

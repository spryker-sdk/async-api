<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\ClassResolver\DependencyProvider;

use Spryker\Yves\Kernel\ClassResolver\AbstractClassResolver;

class DependencyProviderResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'YvesDependencyProvider';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Yves\Kernel\ClassResolver\DependencyProvider\DependencyProviderNotFoundException
     *
     * @return \Spryker\Yves\Kernel\AbstractBundleDependencyProvider
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Yves\Kernel\AbstractBundleDependencyProvider|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new DependencyProviderNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }

    /**
     * @param string $namespace
     * @param string|null $codeBucket
     *
     * @return string
     */
    protected function buildClassName($namespace, $codeBucket = null)
    {
        $searchAndReplace = [
            static::KEY_NAMESPACE => $namespace,
            static::KEY_BUNDLE => $this->getClassInfo()->getBundle(),
            static::KEY_CODE_BUCKET => $codeBucket,
        ];

        return str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern(),
        );
    }
}

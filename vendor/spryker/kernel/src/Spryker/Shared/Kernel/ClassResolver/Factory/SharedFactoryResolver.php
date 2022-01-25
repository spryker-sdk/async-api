<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Factory;

use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver;

/**
 * @method \Spryker\Shared\Kernel\AbstractSharedConfig getResolvedClassInstance()
 */
class SharedFactoryResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'SharedFactory';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Shared\Kernel\ClassResolver\Factory\SharedFactoryNotFoundException
     *
     * @return \Spryker\Shared\Kernel\AbstractSharedFactory
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Shared\Kernel\AbstractSharedFactory|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new SharedFactoryNotFoundException($this->getClassInfo());
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
            static::KEY_BUNDLE => $this->getClassInfo()->getModule(),
            static::KEY_CODE_BUCKET => $codeBucket,
        ];

        return str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern(),
        );
    }
}

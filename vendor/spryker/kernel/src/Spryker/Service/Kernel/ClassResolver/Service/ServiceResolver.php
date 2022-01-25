<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel\ClassResolver\Service;

use Spryker\Service\Kernel\ClassResolver\AbstractClassResolver;

class ServiceResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'ServiceService';

    /**
     * @param object|string $callerClass
     *
     * @throws \Spryker\Service\Kernel\ClassResolver\Service\ServiceNotFoundException
     *
     * @return \Spryker\Service\Kernel\AbstractService
     */
    public function resolve($callerClass)
    {
        /** @var \Spryker\Service\Kernel\AbstractService|null $resolved */
        $resolved = $this->doResolve($callerClass);
        if ($resolved === null) {
            throw new ServiceNotFoundException($this->getClassInfo());
        }

        return $resolved;
    }
}

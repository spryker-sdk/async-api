<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Cache\Provider;

use Spryker\Shared\Kernel\ClassResolver\Cache\AbstractProvider;
use Spryker\Shared\Kernel\ClassResolver\Cache\Storage\File as FileStorage;

/**
 * @deprecated Use {@link \Spryker\Shared\Kernel\KernelConstants::RESOLVABLE_CLASS_NAMES_CACHE_ENABLED} instead.
 */
class File extends AbstractProvider
{
    /**
     * @return \Spryker\Shared\Kernel\ClassResolver\Cache\StorageInterface
     */
    protected function buildStorage()
    {
        return new FileStorage();
    }
}

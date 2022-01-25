<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver;

use Spryker\Shared\Kernel\ClassResolver\ClassInfo as SharedClassInfo;

/**
 * @deprecated Use {@link \Spryker\Shared\Kernel\ClassResolver\ClassInfo} instead.
 */
class ClassInfo extends SharedClassInfo
{
    /**
     * @var int
     */
    public const KEY_LAYER = 3;

    /**
     * @return string
     */
    public function getLayer(): string
    {
        return $this->callerClassParts[static::KEY_LAYER];
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel;

use Spryker\Shared\Kernel\Locator\LocatorMatcherInterface;

class ResourceLocatorMatcher implements LocatorMatcherInterface
{
    /**
     * @var string
     */
    public const METHOD_PREFIX = 'resource';

    /**
     * @api
     *
     * @param string $method
     *
     * @return bool
     */
    public function match($method)
    {
        return (strpos($method, static::METHOD_PREFIX) === 0);
    }
}

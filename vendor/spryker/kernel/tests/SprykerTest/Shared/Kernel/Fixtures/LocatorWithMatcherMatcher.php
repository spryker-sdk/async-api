<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\Fixtures;

use Spryker\Shared\Kernel\Locator\LocatorMatcherInterface;

class LocatorWithMatcherMatcher implements LocatorMatcherInterface
{
    /**
     * @var string
     */
    public const NAME = 'locator';

    /**
     * @param string $method
     *
     * @return bool
     */
    public function match($method): bool
    {
        return (strpos($method, static::NAME) === 0);
    }

    /**
     * @param string $method
     *
     * @return string
     */
    public function filter(string $method): string
    {
        return substr($method, strlen(static::NAME));
    }
}

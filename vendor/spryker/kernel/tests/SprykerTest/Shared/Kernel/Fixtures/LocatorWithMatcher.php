<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\Fixtures;

use Spryker\Shared\Kernel\AbstractLocator;

class LocatorWithMatcher extends AbstractLocator
{
    /**
     * @var string
     */
    protected $bundle = 'foo';

    /**
     * @var string
     */
    protected $layer = 'bar';

    /**
     * @var string
     */
    protected $application = 'baz';

    /**
     * @param string $bundle
     *
     * @return object
     */
    public function locate($bundle): object
    {
        return $this;
    }
}

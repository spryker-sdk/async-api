<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel;

use Spryker\Shared\Kernel\Container\AbstractApplicationContainer;

class Container extends AbstractApplicationContainer
{
    /**
     * @return \Generated\Service\Ide\AutoCompletion&\Spryker\Shared\Kernel\LocatorLocatorInterface
     */
    public function getLocator()
    {
        /** @var \Generated\Service\Ide\AutoCompletion&\Spryker\Shared\Kernel\LocatorLocatorInterface $locator */
        $locator = Locator::getInstance();

        return $locator;
    }
}

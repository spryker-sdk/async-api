<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel;

trait PermissionAwareTrait
{
    /**
     * @uses \Spryker\Client\Permission\PermissionClientInterface
     *
     * @param string $permissionKey
     * @param array|string|int|null $context
     *
     * @return bool
     */
    protected function can($permissionKey, $context = null)
    {
        if (interface_exists('Spryker\Client\Permission\PermissionClientInterface')) {
            return Locator::getInstance()->permission()->client()->can($permissionKey, $context);
        }

        return true;
    }
}

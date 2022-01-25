<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\GeneratedFileFinder;

use Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer;

class EntityTransferFileFinder extends AbstractTransferFileFinder
{
    /**
     * @return string
     */
    protected function getBaseClassToMatch(): string
    {
        return AbstractEntityTransfer::class;
    }
}

<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Message;

class AbstractAsyncApiMessage
{
    protected static ?bool $isWindows = null;

    /**
     * @param bool|null $isWindows
     */
    public function __construct(?bool $isWindows = null)
    {
        static::$isWindows = $isWindows ?? stripos(PHP_OS, 'WIN') !== false;
    }
}

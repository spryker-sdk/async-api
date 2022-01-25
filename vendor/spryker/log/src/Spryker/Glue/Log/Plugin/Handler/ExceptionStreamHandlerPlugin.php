<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Log\Plugin\Handler;

use Monolog\Handler\HandlerInterface;

/**
 * @method \Spryker\Glue\Log\LogFactory getFactory()
 */
class ExceptionStreamHandlerPlugin extends AbstractHandlerPlugin
{
    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function getHandler(): HandlerInterface
    {
        if (!$this->handler) {
            $this->handler = $this->getFactory()->createExceptionStreamHandler();
        }

        return $this->handler;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\ClassResolver\QueryContainer;

use Exception;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\ClassResolver\ClassInfo;
use Spryker\Shared\Kernel\Exception\Backtrace;
use Spryker\Shared\Kernel\KernelConstants;

class QueryContainerNotFoundException extends Exception
{
    /**
     * @param \Spryker\Shared\Kernel\ClassResolver\ClassInfo $callerClassInfo
     */
    public function __construct(ClassInfo $callerClassInfo)
    {
        parent::__construct($this->buildMessage($callerClassInfo));
    }

    /**
     * @param \Spryker\Shared\Kernel\ClassResolver\ClassInfo $callerClassInfo
     *
     * @return string
     */
    protected function buildMessage(ClassInfo $callerClassInfo)
    {
        $message = 'Spryker Kernel Exception' . PHP_EOL;
        $message .= sprintf(
            'Can not resolve %1$sQueryContainer in persistence layer for your module "%1$s"',
            $callerClassInfo->getModule(),
        ) . PHP_EOL;

        $message .= 'You can fix this by adding the missing QueryContainer to your module.' . PHP_EOL;

        $message .= sprintf(
            'E.g. %1$s\\Zed\\%2$s\\Persistence\\%2$sQueryContainer',
            Config::getInstance()->get(KernelConstants::PROJECT_NAMESPACE),
            $callerClassInfo->getModule(),
        );

        $message .= new Backtrace();

        return $message;
    }
}

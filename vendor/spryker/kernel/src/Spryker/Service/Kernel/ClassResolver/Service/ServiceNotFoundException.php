<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Kernel\ClassResolver\Service;

use Exception;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\ClassResolver\ClassInfo;
use Spryker\Shared\Kernel\Exception\Backtrace;
use Spryker\Shared\Kernel\KernelConstants;

class ServiceNotFoundException extends Exception
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
            'Can not resolve %1$sService for your bundle "%1$s"',
            $callerClassInfo->getModule(),
        ) . PHP_EOL;

        $message .= 'You can fix this by adding the missing Service to your bundle.' . PHP_EOL;

        $message .= sprintf(
            'E.g. %s\\Service\\%2$s\\%2$sService',
            Config::getInstance()->get(KernelConstants::PROJECT_NAMESPACE),
            $callerClassInfo->getModule(),
        );

        $message .= PHP_EOL . new Backtrace();

        return $message;
    }
}

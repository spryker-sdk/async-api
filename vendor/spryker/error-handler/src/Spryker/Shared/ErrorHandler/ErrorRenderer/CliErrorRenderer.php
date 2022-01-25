<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler\ErrorRenderer;

class CliErrorRenderer implements ErrorRendererInterface
{
    /**
     * @param \Exception|\Throwable $exception
     *
     * @return string
     */
    public function render($exception)
    {
        $errorMessageTemplate = '%2$s - Exception: %3$s %1$s'
            . 'in %4$s (%5$d)%1$s%1$s'
            . 'Command: %6$s%1$s%1$s'
            . 'Trace: %1$s'
            . '%7$s';

        $errorMessage = sprintf(
            $errorMessageTemplate,
            PHP_EOL,
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $this->getUri(),
            $exception->getTraceAsString()
        );

        return $errorMessage;
    }

    /**
     * @return string
     */
    protected function getUri()
    {
        $uri = (isset($_SERVER['argv'])) ? implode(' ', $_SERVER['argv']) : 'n/a';

        return $uri;
    }
}

<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ErrorHandler;

use ErrorException;
use Spryker\Shared\Config\Config;
use Throwable;

class ErrorHandlerEnvironment
{
    public function __construct()
    {
        $errorCode = error_reporting();
        $configErrorCode = Config::get(ErrorHandlerConstants::ERROR_LEVEL);
        if ($configErrorCode !== $errorCode) {
            error_reporting($configErrorCode);
        }
    }

    /**
     * @return void
     */
    public function initialize()
    {
        $this->setErrorHandler();
        $this->setExceptionHandler();
        $this->registerShutDownFunction();
        $this->setAssertOptions();

        ini_set('display_errors', Config::get(ErrorHandlerConstants::DISPLAY_ERRORS, false));
    }

    /**
     * @return \Closure
     */
    protected function getErrorHandler()
    {
        $errorHandler = function () {
            $errorHandlerFactory = new ErrorHandlerFactory(APPLICATION);

            return $errorHandlerFactory->createErrorHandler();
        };

        return $errorHandler;
    }

    /**
     * @throws \ErrorException
     *
     * @return void
     */
    protected function setErrorHandler()
    {
        $errorLevel = error_reporting();
        $errorHandler = function ($severity, $message, $file, $line) {
            $exception = new ErrorException($message, 0, $severity, $file, $line);

            $levelsNotThrowingExceptions = Config::get(ErrorHandlerConstants::ERROR_LEVEL_LOG_ONLY, 0);
            $shouldThrowException = ($severity & $levelsNotThrowingExceptions) === 0;
            if ($shouldThrowException) {
                throw $exception;
            }

            ErrorLogger::getInstance()->log($exception);
        };

        set_error_handler($errorHandler, $errorLevel);
    }

    /**
     * @return void
     */
    protected function setExceptionHandler()
    {
        $exceptionHandler = function (Throwable $exception): void {
            $errorHandler = $this->getErrorHandler();
            $errorHandler()->handleException($exception);
        };

        set_exception_handler($exceptionHandler);
    }

    /**
     * @return void
     */
    protected function registerShutDownFunction()
    {
        $shutDownFunction = function (): void {
            $lastError = error_get_last();
            $fatalErrors = [
            E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR,
            ];
            if ($lastError && in_array($lastError['type'], $fatalErrors)) {
                $errorHandler = $this->getErrorHandler();
                $errorHandler()->handleFatal();
            }
        };

        register_shutdown_function($shutDownFunction);
    }

    /**
     * @return void
     */
    protected function setAssertOptions()
    {
        $assertHandler = function ($script, $line, $message) {
            $parsedMessage = trim(preg_replace('~^.*/\*(.*)\*/~i', '$1', $message));
            $message = $parsedMessage ?: 'Assertion failed: ' . $message;

            throw new ErrorException($message, 0, 0, $script, $line);
        };

        assert_options(ASSERT_CALLBACK, $assertHandler);
    }
}

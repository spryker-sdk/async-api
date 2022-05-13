<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Message;

class AsyncApiError
{
    /**
     * @return string
     */
    public static function couldNotGenerateCodeFromAsyncApi(): string
    {
        return static::format('Something went wrong while trying to generate code. Either no channels have been found or the channels do not have messages defined. Please run validation before generating code.');
    }

    /**
     * @return string
     */
    public static function asyncApiDoesNotDefineChannels(): string
    {
        return static::format('Async API file doesn\'t contain channels. You need at least one channel where messages should go through.');
    }

    /**
     * @return string
     */
    public static function asyncApiDoesNotDefineMessages(): string
    {
        return static::format('Async API file doesn\'t contain messages. You need at least one message.');
    }

    /**
     * @param string $messageName
     *
     * @return string
     */
    public static function messageDoesNotHaveAnOperationId(string $messageName): string
    {
        return static::format(sprintf('The message "%s" doesn\'t have an operationId defined.', $messageName));
    }

    /**
     * @param string $messageName
     *
     * @return string
     */
    public static function messageNameUsedMoreThanOnce(string $messageName): string
    {
        return static::format(sprintf('The message name "%s" is used more than once.', $messageName));
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public static function asyncApiFileDoesNotExist(string $fileName): string
    {
        return static::format(sprintf('Couldn\'t find AsyncAPI schema file "%s".', $fileName));
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public static function couldNotParseAsyncApiFile(string $fileName): string
    {
        return static::format(sprintf('Couldn\'t not parse AsyncAPI schema file "%s".', $fileName));
    }

    /**
     * Colorize output in CLI on Linux machines.
     *
     * Error text will be in red, everything in double quotes will be yellow, and quotes will be removed.
     *
     * @param string $message
     *
     * @return string
     */
    protected static function format(string $message): string
    {
        if (PHP_SAPI === PHP_SAPI && stripos(PHP_OS, 'WIN') === false) {
            $message = "\033[31m" . preg_replace_callback('/"(.+?)"/', function (array $matches) {
                    return sprintf("\033[0m\033[33m%s\033[0m\033[31m", $matches[1]);
            }, $message);
        }

        return $message;
    }
}

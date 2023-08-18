<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Message;

class AsyncApiInfo extends AbstractAsyncApiMessage
{
    /**
     * @return string
     */
    public static function asyncApiSchemaFileIsValid(): string
    {
        return static::format('Async API file doesn\'t contain any errors.');
    }

    /**
     * @return string
     */
    public static function generatedCodeFromAsyncApiSchema(): string
    {
        return static::format('Successfully generated code to work with asynchronous messages.');
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public static function asyncApiFileCreated(string $fileName): string
    {
        return static::format(sprintf('Successfully created "%s".', $fileName));
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public static function asyncApiFileUpdated(string $fileName): string
    {
        return static::format(sprintf('Successfully updated "%s".', $fileName));
    }

    /**
     * @param string $messageName
     * @param string $channelName
     *
     * @return string
     */
    public static function addedMessageToChannel(string $messageName, string $channelName): string
    {
        return static::format(sprintf('Successfully added the message "%s" to the channel "%s".', $messageName, $channelName));
    }

    /**
     * @return string
     */
    public static function asyncApiCliNotFound(): string
    {
        return static::format(
            sprintf(
                'AsyncAPI cli not found in your system, please make sure to install the "%s" package. More info at "%s".',
                '@asyncapi/cli',
                'https://www.asyncapi.com/docs/tools/cli/installation',
            ),
        );
    }

    /**
     * Colorize output in CLI on Linux machines.
     *
     * Info text will be in green, everything in double quotes will be yellow, and quotes will be removed.
     *
     * @param string $message
     *
     * @return string
     */
    protected static function format(string $message): string
    {
        if (!static::$isWindows) {
            return "\033[32m" . preg_replace_callback('/"(.+?)"/', function (array $matches) {
                return sprintf("\033[0m\033[33m%s\033[0m\033[32m", $matches[1]);
            }, $message);
        }

        return $message;
    }
}

<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Message;

class AsyncApiInfo
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
     * @param string $propertyName
     * @param string $type
     * @param string $asyncApiMessageName
     * @param string $moduleName
     *
     * @return string
     */
    public static function addedPropertyWithTypeTo(string $propertyName, string $type, string $asyncApiMessageName, string $moduleName): string
    {
        return static::format(sprintf('Added property "%s" with type "%s" to the "%sTransfer" transfer object of the module "%s".', $propertyName, $type, $asyncApiMessageName, $moduleName));
    }

    /**
     * @param string $asyncApiMessageName
     * @param string $moduleName
     *
     * @return string
     */
    public static function addedTransferDefinitionTo(string $asyncApiMessageName, string $moduleName): string
    {
        return static::format(sprintf('Added transfer definition for "%s" to the module "%s".', $asyncApiMessageName, $moduleName));
    }

    /**
     * @param string $asyncApiMessageName
     * @param string $moduleName
     *
     * @return string
     */
    public static function addedMessageHandlerPluginForMessageTo(string $asyncApiMessageName, string $moduleName): string
    {
        return static::format(sprintf('Added MessageHandlerPlugin for the message "%sTransfer" to the module "%s".', $asyncApiMessageName, $moduleName));
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
        if (PHP_SAPI === PHP_SAPI && stripos(PHP_OS, 'WIN') === false) {
            $message = "\033[32m" . preg_replace_callback('/"(.+?)"/', function (array $matches) {
                return sprintf("\033[0m\033[33m%s\033[0m\033[32m", $matches[1]);
            }, $message);
        }

        return $message;
    }
}

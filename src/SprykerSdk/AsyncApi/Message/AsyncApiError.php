<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Message;

class AsyncApiError
{
    /**
     * @var string
     */
    protected const SCHEMA_VALIDATION_ERROR_PREFIX = 'AsyncAPI schema validation error';

    /**
     * @var string
     */
    protected const CODE_GENERATION_ERROR_PREFIX = 'AsyncAPI code generation error';

    protected static ?bool $isNotWindows = null;

    /**
     * @param bool|null $isNotWindows
     */
    public function __construct(?bool $isNotWindows = null)
    {
        static::$isNotWindows = $isNotWindows ?? (PHP_SAPI === PHP_SAPI && stripos(PHP_OS, 'WIN') === false);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function couldNotGenerateCodeFromAsyncApi(string $path): string
    {
        return static::format(
            sprintf(
                '%s: Something went wrong while trying to generate code from Open API schema file "%s". Either no channels have been found or the channels do not have messages defined. Please run validation before generating code.',
                static::CODE_GENERATION_ERROR_PREFIX,
                $path,
            ),
        );
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function asyncApiDoesNotDefineChannels(string $path): string
    {
        return static::format(
            sprintf(
                '%s: Async API file "%s" doesn\'t contain channels. You need at least one channel where messages should go through.',
                static::SCHEMA_VALIDATION_ERROR_PREFIX,
                $path,
            ),
        );
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function asyncApiDoesNotDefineMessages(string $path): string
    {
        return static::format(
            sprintf(
                '%s: Async API file "%s" doesn\'t contain messages. You need at least one message.',
                static::SCHEMA_VALIDATION_ERROR_PREFIX,
                $path,
            ),
        );
    }

    /**
     * @param string $messageName
     * @param string $path
     *
     * @return string
     */
    public static function messageDoesNotHaveAModuleName(string $messageName, string $path): string
    {
        return static::format(
            sprintf(
                '%s: The message "%s" from file "%s" doesn\'t have a module name defined in the "x-spryker" extension.',
                static::SCHEMA_VALIDATION_ERROR_PREFIX,
                $messageName,
                $path,
            ),
        );
    }

    /**
     * @param string $messageName
     * @param string $path
     *
     * @return string
     */
    public static function messageNameUsedMoreThanOnce(string $messageName, string $path): string
    {
        return static::format(
            sprintf(
                '%s: The message name "%s" from file "%s" is used more than once.',
                static::SCHEMA_VALIDATION_ERROR_PREFIX,
                $messageName,
                $path,
            ),
        );
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function asyncApiFileDoesNotExist(string $path): string
    {
        return static::format(
            sprintf(
                '%s: Couldn\'t find AsyncAPI schema file "%s".',
                static::SCHEMA_VALIDATION_ERROR_PREFIX,
                $path,
            ),
        );
    }

    /**
     * @param string $path
     * @param string $message
     *
     * @return string
     */
    public static function couldNotParseAsyncApiFile(string $path, string $message): string
    {
        return static::format(
            sprintf(
                '%s: Couldn\'t not parse AsyncAPI schema file "%s". Message - "%s"',
                static::SCHEMA_VALIDATION_ERROR_PREFIX,
                $path,
                $message,
            ),
        );
    }

    /**
     * @param string $messageTypeOption
     * @param array $availableValues
     * @param string $path
     *
     * @return string
     */
    public static function messageTypeHasWrongValue(
        string $messageTypeOption,
        array $availableValues,
        string $path
    ): string {
        return static::format(
            sprintf(
                '%s: The option "%s" from file "%s" must be one of "%s"',
                static::SCHEMA_VALIDATION_ERROR_PREFIX,
                $messageTypeOption,
                $path,
                implode('","', $availableValues),
            ),
        );
    }

    /**
     * @param string $messageName
     *
     * @return string
     */
    public static function couldNotFindAnSprykerExtension(
        string $messageName
    ): string {
        return static::format(
            sprintf(
                'Could not find an `x-spryker` extension. Please add one to your schema file for the "%s" message.',
                $messageName,
            ),
        );
    }

    /**
     * @param string $messageName
     *
     * @return string
     */
    public static function couldNotFindAModulePropertyInTheSprykerExtension(
        string $messageName
    ): string {
        return static::format(
            sprintf(
                'Could not find a `module` name property in the `x-spryker` extension. Please add one to your schema file for the "%s" message.',
                $messageName,
            ),
        );
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function asyncApiCliValidationFailed(string $path): string
    {
        return static::format(
            sprintf(
                'AsyncAPI CLI failed to validate schema "%s".',
                $path,
            ),
        );
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
        if (static::$isNotWindows) {
            return "\033[31m" . preg_replace_callback('/"(.+?)"/', function (array $matches) {
                    return sprintf("\033[0m\033[33m%s\033[0m\033[31m", $matches[1]);
            }, $message);
        }

        return $message;
    }
}

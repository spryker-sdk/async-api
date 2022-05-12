<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Messages;

class AsyncApiMessages
{
    /**
     * @var string
     */
    public const VALIDATOR_MESSAGE_SUCCESS = 'Async API file doesn\'t contain any errors.';

    /**
     * @var string
     */
    public const VALIDATOR_ERROR_GENERATE_CODE = 'Something went wrong while trying to generate code. Either no channels have been found or the channels do not have messages defined. Please run validation before generating code.';

    /**
     * @var string
     */
    public const VALIDATOR_ERROR_NO_CHANNELS_DEFINED = 'Async API file doesn\'t contain channels. You need at least one channel where messages should go through.';

    /**
     * @var string
     */
    public const VALIDATOR_ERROR_NO_MESSAGES_DEFINED = 'Async API file doesn\'t contain messages. You need at least one message.';

    /**
     * @var string
     */
    public const VALIDATOR_ERROR_MESSAGES_DOES_NOT_HAVE_OPERATION_ID_PATTERN = 'The message "%s" doesn\'t have an operationId defined.';

    /**
     * @var string
     */
    public const VALIDATOR_ERROR_MESSAGES_MESSAGE_FOUND_MORE_THAN_ONCE = 'The message "%s" doesn\'t have an operationId defined.';

    /**
     * @var string
     */
    public const SUCCESS_MESSAGES_GENERATED_CODE = 'Successfully generated code to work with asynchronous messages.';

    /**
     * @var string
     */
    public const SUCCESS_MESSAGES_ASYNC_API_FILE_CREATED_PATTERN = 'Successfully created "%s".';

    /**
     * @var string
     */
    public const SUCCESS_MESSAGES_ASYNC_API_FILE_UPDATED_PATTERN = 'Successfully updated "%s".';

    /**
     * @var string
     */
    public const SUCCESS_MESSAGES_ADDED_MESSAGE_TO_CHANNEL_PATTERN = 'Successfully added the message "%s" to the channel "%s".';

    /**
     * @param string $messageName
     *
     * @return string
     */
    public static function errorMessageMessageDoesNotHaveAnOperationId(string $messageName): string
    {
        return sprintf(static::VALIDATOR_ERROR_MESSAGES_DOES_NOT_HAVE_OPERATION_ID_PATTERN, $messageName);
    }

    /**
     * @param string $messageName
     *
     * @return string
     */
    public static function errorMessageMessageNameUsedMoreThanOnce(string $messageName): string
    {
        return sprintf(static::VALIDATOR_ERROR_MESSAGES_MESSAGE_FOUND_MORE_THAN_ONCE, $messageName);
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public static function successMessageAsyncApiFileCreated(string $fileName): string
    {
        return sprintf(static::SUCCESS_MESSAGES_ASYNC_API_FILE_CREATED_PATTERN, $fileName);
    }

    /**
     * @param string $fileName
     *
     * @return string
     */
    public static function successMessageAsyncApiFileUpdated(string $fileName): string
    {
        return sprintf(static::SUCCESS_MESSAGES_ASYNC_API_FILE_UPDATED_PATTERN, $fileName);
    }

    /**
     * @param string $messageName
     * @param string $channelName
     *
     * @return string
     */
    public static function successMessageAddedMessageToChannel(string $messageName, string $channelName): string
    {
        return sprintf(static::SUCCESS_MESSAGES_ADDED_MESSAGE_TO_CHANNEL_PATTERN, $messageName, $channelName);
    }
}

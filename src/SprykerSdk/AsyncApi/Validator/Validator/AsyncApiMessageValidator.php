<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Validator\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\AsyncApi\AsyncApiConfig;
use SprykerSdk\AsyncApi\Messages\AsyncApiMessages;
use SprykerSdk\AsyncApi\Validator\FileValidatorInterface;

class AsyncApiMessageValidator implements FileValidatorInterface
{
    /**
     * @var \SprykerSdk\AsyncApi\AsyncApiConfig
     */
    protected AsyncApiConfig $config;

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApiConfig $config
     */
    public function __construct(AsyncApiConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Validates the schema for duplicated messages.
     *
     * @param array $asyncApi
     * @param string $asyncApiFileName
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer $validateResponseTransfer
     * @param array|null $context
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validate(
        array $asyncApi,
        string $asyncApiFileName,
        ValidateResponseTransfer $validateResponseTransfer,
        ?array $context = null
    ): ValidateResponseTransfer {
        $validateResponseTransfer = $this->validateAtLeastOneMessageExists($asyncApi, $validateResponseTransfer);

        return $this->validateMessageNamesAreOnlyUsedOnce($asyncApi, $validateResponseTransfer);
    }

    /**
     * @param array $asyncApi
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    protected function validateAtLeastOneMessageExists(array $asyncApi, ValidateResponseTransfer $validateResponseTransfer): ValidateResponseTransfer
    {
        if (!isset($asyncApi['components']['messages']) || count($asyncApi['components']['messages']) === 0) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(AsyncApiMessages::VALIDATOR_ERROR_NO_MESSAGES_DEFINED);
            $validateResponseTransfer->addError($messageTransfer);
        }

        return $validateResponseTransfer;
    }

    /**
     * @param array $asyncApi
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    protected function validateMessageNamesAreOnlyUsedOnce(array $asyncApi, ValidateResponseTransfer $validateResponseTransfer): ValidateResponseTransfer
    {
        if (!isset($asyncApi['components']['messages'])) {
            return $validateResponseTransfer;
        }

        $messageNames = [];

        foreach ($asyncApi['components']['messages'] as $message) {
            if (isset($messageNames[$message['name']])) {
                $messageTransfer = new MessageTransfer();
                $messageTransfer->setMessage(AsyncApiMessages::errorMessageMessageNameUsedMoreThanOnce($message['name']));
                $validateResponseTransfer->addError($messageTransfer);
            }
            $messageNames[$message['name']] = true;
        }

        return $validateResponseTransfer;
    }
}

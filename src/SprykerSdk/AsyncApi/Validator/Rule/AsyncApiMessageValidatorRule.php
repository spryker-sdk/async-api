<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Validator\Rule;

use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdk\AsyncApi\Message\MessageBuilderInterface;

class AsyncApiMessageValidatorRule implements ValidatorRuleInterface
{
 /**
  * @var \SprykerSdk\AsyncApi\Message\MessageBuilderInterface
  */
    protected MessageBuilderInterface $messageBuilder;

    /**
     * @param \SprykerSdk\AsyncApi\Message\MessageBuilderInterface $messageBuilder
     */
    public function __construct(MessageBuilderInterface $messageBuilder)
    {
        $this->messageBuilder = $messageBuilder;
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
            $validateResponseTransfer->addError($this->messageBuilder->buildMessage(
                AsyncApiError::asyncApiDoesNotDefineMessages(),
            ));
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
                $validateResponseTransfer->addError($this->messageBuilder->buildMessage(
                    AsyncApiError::messageNameUsedMoreThanOnce($message['name']),
                ));
            }
            $messageNames[$message['name']] = true;
        }

        return $validateResponseTransfer;
    }
}

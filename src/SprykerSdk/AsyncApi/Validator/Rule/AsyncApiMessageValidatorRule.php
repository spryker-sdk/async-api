<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Validator\Rule;

use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdk\AsyncApi\Message\MessageBuilderInterface;
use Transfer\ValidateResponseTransfer;

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
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     * @param array|null $context
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validate(
        array $asyncApi,
        string $asyncApiFileName,
        ValidateResponseTransfer $validateResponseTransfer,
        ?array $context = null
    ): ValidateResponseTransfer {
        $validateResponseTransfer = $this->validateAtLeastOneMessageExists($asyncApi, $asyncApiFileName, $validateResponseTransfer);

        return $this->validateMessageNamesAreOnlyUsedOnce($asyncApi, $asyncApiFileName, $validateResponseTransfer);
    }

    /**
     * @param array $asyncApi
     * @param string $asyncApiFileName
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    protected function validateAtLeastOneMessageExists(
        array $asyncApi,
        string $asyncApiFileName,
        ValidateResponseTransfer $validateResponseTransfer
    ): ValidateResponseTransfer {
        if (!isset($asyncApi['components']['messages']) || count($asyncApi['components']['messages']) === 0) {
            $validateResponseTransfer->addError($this->messageBuilder->buildMessage(
                AsyncApiError::asyncApiDoesNotDefineMessages($asyncApiFileName),
            ));
        }

        return $validateResponseTransfer;
    }

    /**
     * @param array $asyncApi
     * @param string $asyncApiFileName
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    protected function validateMessageNamesAreOnlyUsedOnce(
        array $asyncApi,
        string $asyncApiFileName,
        ValidateResponseTransfer $validateResponseTransfer
    ): ValidateResponseTransfer {
        if (!isset($asyncApi['components']['messages'])) {
            return $validateResponseTransfer;
        }

        $messageNames = [];

        foreach ($asyncApi['components']['messages'] as $message) {
            if (isset($messageNames[$message['name']])) {
                $validateResponseTransfer->addError($this->messageBuilder->buildMessage(
                    AsyncApiError::messageNameUsedMoreThanOnce($message['name'], $asyncApiFileName),
                ));
            }
            $messageNames[$message['name']] = true;
        }

        return $validateResponseTransfer;
    }
}

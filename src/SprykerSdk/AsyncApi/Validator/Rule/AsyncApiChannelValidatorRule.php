<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Validator\Rule;

use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdk\AsyncApi\Message\MessageBuilderInterface;

class AsyncApiChannelValidatorRule implements ValidatorRuleInterface
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
     * Validates the schema for existence of channels and that the existing channel at least one message to be handled.
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
        return $this->validateAtLeastOneChannelExists($asyncApi, $validateResponseTransfer);
    }

    /**
     * @param array $asyncApi
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    protected function validateAtLeastOneChannelExists(array $asyncApi, ValidateResponseTransfer $validateResponseTransfer): ValidateResponseTransfer
    {
        if (!isset($asyncApi['channels'])) {
            $validateResponseTransfer->addError($this->messageBuilder->buildMessage(
                AsyncApiError::asyncApiDoesNotDefineChannels(),
            ));
        }

        return $validateResponseTransfer;
    }
}

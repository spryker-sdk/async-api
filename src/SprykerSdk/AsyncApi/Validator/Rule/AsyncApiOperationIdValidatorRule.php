<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Validator\Rule;

use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdk\AsyncApi\Message\MessageBuilderInterface;
use Transfer\ValidateResponseTransfer;

class AsyncApiOperationIdValidatorRule implements ValidatorRuleInterface
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
        if (!isset($asyncApi['components']['messages'])) {
            return $validateResponseTransfer;
        }

        foreach ($asyncApi['components']['messages'] as $message) {
            if (!isset($message['operationId'])) {
                $validateResponseTransfer->addError($this->messageBuilder->buildMessage(AsyncApiError::messageDoesNotHaveAnOperationId($message['name'])));
            }
        }

        return $validateResponseTransfer;
    }
}

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

class AsyncApiChannelValidator implements FileValidatorInterface
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
        $validateResponseTransfer = $this->validateAtLeastOneChannelExists($asyncApi, $validateResponseTransfer);

        return $validateResponseTransfer;
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
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage(AsyncApiMessages::VALIDATOR_ERROR_NO_CHANNELS_DEFINED);
            $validateResponseTransfer->addError($messageTransfer);
        }

        return $validateResponseTransfer;
    }
}

<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Validator\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\AsyncApi\AsyncApiConfig;
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
        $messageNames = [];
        foreach ($asyncApi['components']['messages'] as $message) {
            if (isset($messageNames[$message['name']])) {
                $messageTransfer = new MessageTransfer();
                $messageTransfer->setMessage('Async API file contains duplicate message names.');
                $validateResponseTransfer->addError($messageTransfer);
            }
            $messageNames[$message['name']] = true;
        }

        return $validateResponseTransfer;
    }
}

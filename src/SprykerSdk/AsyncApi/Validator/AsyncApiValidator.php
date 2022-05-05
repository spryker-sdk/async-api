<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Validator;

use Exception;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ValidateRequestTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;
use Symfony\Component\Yaml\Yaml;

class AsyncApiValidator extends AbstractValidator
{
    /**
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer|null $validateResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validate(
        ValidateRequestTransfer $validateRequestTransfer,
        ?ValidateResponseTransfer $validateResponseTransfer = null
    ): ValidateResponseTransfer {
        $validateResponseTransfer ??= new ValidateResponseTransfer();
        $asyncApiFile = $validateRequestTransfer->getAsyncApiFileOrFail();
        if (!$this->finder->hasFiles($asyncApiFile)) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage('No AsyncAPI file given, you need to pass a valid filename.');
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        try {
            $asyncApi = Yaml::parseFile($asyncApiFile);
        } catch (Exception $e) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage('Could not parse AsyncApi file.');
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        if (!isset($asyncApi['components']['messages']) || count($asyncApi['components']['messages']) === 0) {
            $messageTransfer = new MessageTransfer();
            $messageTransfer->setMessage('Async API file does not contain messages.');
            $validateResponseTransfer->addError($messageTransfer);

            return $validateResponseTransfer;
        }

        return $this->validateFileData($asyncApi, $this->finder->getFile($asyncApiFile)->getFilename(), $validateResponseTransfer);
    }
}
